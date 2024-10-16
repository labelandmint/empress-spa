<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Setting;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;
use Auth;
use DB;

class MembersExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return User::leftjoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'subscriptions.subscription_plan_id')
            ->leftjoin('bookings', function ($join) {
                $join->on('bookings.member_id', '=', 'users.id')
                    ->where('bookings.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('bookings as b')
                            ->whereColumn('b.member_id', 'bookings.member_id');
                    });
            })
            ->leftjoin('services', 'services.id', '=', 'bookings.service_id')
            ->leftjoin('slots','bookings.slot_id','slots.id')
            ->leftjoin('transactions', function ($join) {
                $join->on('transactions.member_id', '=', 'users.id')
                    ->where('transactions.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('transactions as t')
                            ->where('t.status',1)
                            ->whereColumn('t.member_id', 'transactions.member_id');
                    });
            })
            ->where('user_role', 2)
            ->select('users.f_name', 'users.l_name', 'subscription_plans.title', 'subscriptions.status', 'services.title AS service', 'bookings.booking_date', 'transactions.created_at as payment_date', 'users.rating', 'slots.start_time', 'slots.end_time')
            ->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Subscription',
            'Subscription Status',
            'Last Service',
            'Last Booking Date & Time',
            'Last Payment Date',
            'Rating',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],  // Apply to headings
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $setting = Setting::first();
                $subsCount = User::where('user_role', 2)->count();
                $subsCountThisMonth = User::where('user_role', 2)
                    ->whereMonth('created_at', date('m'))
                    ->count();

                $subsAmount = Transaction::where('status', 1)->sum('amount');
                $subsAmountLastMonth = Transaction::where('status', 1)
                    ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount');

                $sheet = $event->sheet->getDelegate();

                // Insert logo into the sheet
                $imagePath = public_path('images/colored-empress-spa-logo.png'); // Correct full path to image

                $drawing = new Drawing();
                $drawing->setName('Empress Spa Logo');
                $drawing->setDescription('This is the logo of Empress Spa');
                $drawing->setPath($imagePath); // Path to the image file
                $drawing->setHeight(70); // Adjust the height accordingly
                $drawing->setWidth(340); // Adjust the width accordingly
                $drawing->setCoordinates('A1'); // Set image position (starting at A1)
                $drawing->setOffsetX(7); // Margin from the left (in pixels)
                $drawing->setOffsetY(7); // Margin from the top (in pixels)
                $drawing->setWorksheet($sheet); // Attach the image to the current sheet

                // Clear cells from A1 to D8
                foreach (range('A', 'D') as $col) {
                    foreach (range(1, 8) as $row) {
                        $sheet->setCellValue($col . $row, ''); // Set each cell in the range to empty
                    }
                }

                // Company details
                $sheet->setCellValue('B14', '');
                $sheet->setCellValue('B15', '');
                $sheet->setCellValue('A15', '');
                 // Clear row 9 from A9 to G9
                foreach (range('A', 'H') as $col) {
                    $sheet->setCellValue($col . '9', ''); // Set each cell in the range to empty
                }
                
                $sheet->setCellValue('E1', '');
                $sheet->setCellValue('E2', 'Website: ');
                $sheet->setCellValue('F1', '');
                $sheet->setCellValue('F2', $setting->business_website_address);
                $sheet->setCellValue('E3', 'Phone: ');
                $sheet->setCellValue('F3', $setting->business_phone_number);
                $sheet->setCellValue('E4', 'Email: ');
                $sheet->setCellValue('F4', $setting->business_email_address);
                $sheet->setCellValue('E5', 'Address: ');
                $sheet->setCellValue('F5', $setting->business_address1 . ',');
                $sheet->setCellValue('F6', $setting->business_address2 . ',' . $setting->city . ',');
                $sheet->setCellValue('F7', $setting->state . ',' . $setting->postcode);

                // Clear cells in G and H
                foreach (range('G', 'H') as $col) {
                    foreach (range(1, 8) as $row) {
                        $sheet->setCellValue($col . $row, ''); // Set each cell in the range to empty
                    }
                }

                // Set the background color to black and font color to white for the header
                $sheet->getStyle('A1:I8')->getFill()->setFillType(Fill::FILL_SOLID);
                $sheet->getStyle('A1:I8')->getFill()->getStartColor()->setARGB('FF000000'); // Black background
                $sheet->getStyle('A1:I8')->getFont()->getColor()->setARGB('FFFFFFFF'); // White font

                // Report details
                $sheet->setCellValue('A10', 'Report Produced By: ')
                      ->getStyle('A10')->getFont()->setBold(true);
                $sheet->setCellValue('B10', Auth::guard('admin')->user()->f_name . ' ' . Auth::guard('admin')->user()->l_name);
                $sheet->setCellValue('D10', 'Total Subscriptions: ')
                      ->getStyle('D10')->getFont()->setBold(true);
                $sheet->setCellValue('E10', $subsCount);
                $sheet->setCellValue('F10', 'Total Subscriptions Value:')
                      ->getStyle('F10')->getFont()->setBold(true);
                $sheet->setCellValue('G10', "$ $subsAmount");

                // Date Produced
                $sheet->setCellValue('A11', 'Date Produced: ')
                      ->getStyle('A11')->getFont()->setBold(true);
                $sheet->setCellValue('B11', date('Y-m-d'));
                $sheet->setCellValue('D11', 'Total This Month: ')
                      ->getStyle('D11')->getFont()->setBold(true);
                $sheet->setCellValue('E11', $subsCountThisMonth);
                $sheet->setCellValue('F11', 'Total Last Month: ')
                      ->getStyle('F11')->getFont()->setBold(true);
                $sheet->setCellValue('G11', "$ $subsAmountLastMonth");

                // Clear additional rows
                foreach (range('A', 'G') as $col) {
                    $sheet->setCellValue($col . '12', '');
                    $sheet->setCellValue($col . '13', '');
                }

                // Transactions Summary
                $sheet->setCellValue('A14', 'Subscription Summary')
                      ->getStyle('A14')->getFont()->setBold(true);

                // Header for data
                $sheet->setCellValue('A16', 'First Name');
                $sheet->setCellValue('B16', 'Last Name');
                $sheet->setCellValue('C16', 'Subscription');
                $sheet->setCellValue('D16', 'Subscription Status');
                $sheet->setCellValue('E16', 'Last Service');
                $sheet->setCellValue('F16', 'Last Booking Date & Time');
                $sheet->setCellValue('G16', 'Last Payment Date');
                $sheet->setCellValue('H16', 'Rating');

                // Set font color to white and background color to black for the header row
                $sheet->getStyle('A16:H16')->getFont()->getColor()->setARGB('FFFFFFFF'); 
                $sheet->getStyle('A16:H16')->getFill()->setFillType(Fill::FILL_SOLID);
                $sheet->getStyle('A16:H16')->getFill()->getStartColor()->setARGB('FF000000'); 
                $sheet->getStyle('A16:H16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Populate data from row 18
                $dataRow = 17;
                $transactions = $this->collection();

                foreach ($transactions as $transaction) {

                    $status = $transaction->status == 1 ? 'Active' :
                              ($transaction->status == 2 ? 'Paused' : 'Cancelled');
                    $datetime = $transaction->booking_date;
                    if($transaction->start_time){
                        $formattedStartTime = Carbon::parse($transaction->start_time)->format('g:i A');
                        $formattedEndTime = Carbon::parse($transaction->end_time)->format('g:i A');
                        $datetime = $datetime. ', '. $formattedStartTime . ' - ' . $formattedEndTime;
                    }
                    

                    $sheet->setCellValue('A' . $dataRow, $transaction->f_name);
                    $sheet->setCellValue('B' . $dataRow, $transaction->l_name);
                    $sheet->setCellValue('C' . $dataRow, $transaction->title);
                    $sheet->setCellValue('D' . $dataRow, $status);
                    $sheet->setCellValue('E' . $dataRow, $transaction->service); 
                    $sheet->setCellValue('F' . $dataRow, $datetime); 
                    $sheet->setCellValue('G' . $dataRow, $transaction->payment_date); 
                    $sheet->setCellValue('H' . $dataRow, $transaction->rating);

                    // ->select('users.f_name', 'users.l_name', 'subscription_plans.title', 'subscriptions.status', 'services.title AS service', 'bookings.booking_date', 'bookings.payment_date', 'users.rating')

                    $dataRow++;
                }

                // Auto-adjust column widths
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Apply left alignment to F3
                $sheet->getStyle('F3')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Apply left alignment to E10
                $sheet->getStyle('E10')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Apply left alignment to E11
                $sheet->getStyle('E11')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Set the entire H column to left alignment
                $sheet->getStyle('H:H')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


                $sheet->getStyle('A18')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            }
        ];
    }
}
