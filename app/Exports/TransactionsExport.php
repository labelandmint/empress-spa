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

class TransactionsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return  Transaction::join('users','users.id','transactions.member_id')
        ->join('subscription_plans','subscription_plans.id','transactions.subscription_plan_id')
        ->select('transactions.*','users.f_name','users.l_name','subscription_plans.title' )
        ->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Subscription',
            'Price (ex. GST)',
            'Payment Date',
        ];
    }

    // Add styles to the worksheet
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],  // Apply to headings
        ];
    }

    // After sheet event to handle custom formatting
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $setting = Setting::first();
                $subsCount = User::where('user_role',2)->count();
                $subsCountThisMonth = User::where('user_role',2)
                ->whereMonth('created_at',date('m'))
                ->count();

                $subsAmount = Transaction::where('status',1)->sum('amount');
                $subsAmountLastMonth = Transaction::where('status', 1)
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount');

                $sheet = $event->sheet->getDelegate();





                // Merge and style header rows for company logo and details...
                // $sheet->mergeCells('A1:H8');
                // Clear cells from A1 to D8

                // Insert image into the sheet
                $imagePath = public_path('images/colored-empress-spa-logo.png'); // Correct full path to image

                $drawing = new Drawing();
                $drawing->setName('Empress Spa Logo');
                $drawing->setDescription('This is the logo of Empress Spa');
                $drawing->setPath($imagePath); // Path to the image file
                $drawing->setHeight(70); // Adjust the height accordingly
                $drawing->setWidth(340); // Adjust the height accordingly
                $drawing->setCoordinates('A1'); // Set image position (starting at A1)
                // Set margin (offset) in pixels
                $drawing->setOffsetX(7); // Margin from the left (in pixels)
                $drawing->setOffsetY(7); // Margin from the top (in pixels)

                $drawing->setWorksheet($sheet); // Attach the image to the current sheet

                foreach (range('A', 'D') as $col) {
                    foreach (range(1, 8) as $row) {
                        $sheet->setCellValue($col . $row, ''); // Set each cell in the range to empty
                    }
                }



                $sheet->setCellValue('E1', '');
                $sheet->setCellValue('E2', 'Website: ');
                $sheet->setCellValue('E3', 'Phone: ');
                $sheet->setCellValue('E4', 'Email: ');
                $sheet->setCellValue('E5', 'Address: ');
                $sheet->setCellValue('E6', '');
                $sheet->setCellValue('E7', '');
                $sheet->setCellValue('E8', '');



                $sheet->setCellValue('F1', '');
                $sheet->setCellValue('F2', $setting->business_website_address);
                $sheet->setCellValue('F3', "$setting->business_phone_number");

                $sheet->setCellValue('F4', $setting->business_email_address);
                $sheet->setCellValue('F5', $setting->business_address1.',');
                $sheet->setCellValue('F6', $setting->business_address2.','.$setting->city.',');
                $sheet->setCellValue('F7', $setting->state.','.$setting->postcode);
                $sheet->setCellValue('F8', '');

                // Clear cells from A1 to D8
                foreach (range('G', 'H') as $col) {
                    foreach (range(1, 8) as $row) {
                        $sheet->setCellValue($col . $row, ''); // Set each cell in the range to empty
                    }
                }
                foreach (range('H', 'P') as $col) {
                    foreach (range(1, 22) as $row) {
                        $sheet->setCellValue($col . $row, ''); // Set each cell in the range to empty
                    }
                }

                $sheet->setCellValue('A1', '');
                // $sheet->getStyle('A1:H8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // $sheet->getStyle('A1:H8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                // $sheet->getStyle('A1:H8')->getFont()->setBold(true);

                // Set the background color to black and font color to white for the header
                $sheet->getStyle('A1:G8')->getFill()->setFillType(Fill::FILL_SOLID);
                $sheet->getStyle('A1:G8')->getFill()->getStartColor()->setARGB('FF000000'); // Black background
                $sheet->getStyle('A1:G8')->getFont()->getColor()->setARGB('FFFFFFFF'); // White font

                // Clear row 9 from A9 to G9
                foreach (range('A', 'G') as $col) {
                    $sheet->setCellValue($col . '9', ''); // Set each cell in the range to empty
                }

                // Merge cells for report and date details
                $sheet->setCellValue('A10', 'Report Produced By: ');
                $sheet->getStyle('A10')->getFont()->setBold(true);

                $sheet->setCellValue('B10', Auth::guard('admin')->user()->f_name.' '.Auth::guard('admin')->user()->l_name);

                $sheet->setCellValue('C10', '');

                $sheet->setCellValue('D10', 'Total Subscriptions: ');
                $sheet->getStyle('D10')->getFont()->setBold(true);

                $sheet->setCellValue('E10', $subsCount);

                $sheet->setCellValue('F10', 'Total Subscriptions Value:');
                $sheet->getStyle('F10')->getFont()->setBold(true);

                $sheet->setCellValue('G10', "$ $subsAmount");

                // $sheet->setCellValue('E10', 'Total Subscriptions: ');
                // $sheet->setCellValue('F10', '[Number]');

                $sheet->setCellValue('A11', 'Date Produced: ');
                $sheet->getStyle('A11')->getFont()->setBold(true);

                $sheet->setCellValue('B11', date('Y-m-d'));

                $sheet->setCellValue('C11', '');

                $sheet->setCellValue('D11', 'Total This Month: ');
                $sheet->getStyle('D11')->getFont()->setBold(true);

                $sheet->setCellValue('E11', $subsCountThisMonth);

                $sheet->setCellValue('F11', 'Total Last Month: ');
                $sheet->getStyle('F11')->getFont()->setBold(true);

                $sheet->setCellValue('G11', '$'.$subsAmountLastMonth);

                // Clear row 12 from A12 to G12
                foreach (range('A', 'G') as $col) {
                    $sheet->setCellValue($col . '12', ''); // Set each cell in the range to empty
                }

                // Clear row 13 from A13 to G13
                foreach (range('A', 'G') as $col) {
                    $sheet->setCellValue($col . '13', ''); // Set each cell in the range to empty
                }


                $sheet->setCellValue('A14', 'Transactions Summary');
                $sheet->getStyle('A14')->getFont()->setBold(true);
                // Clear row 14 from A14 to G14
                foreach (range('B', 'G') as $col) {
                    $sheet->setCellValue($col . '14', ''); // Set each cell in the range to empty
                }

                 // Clear row 15 from A15 to G15
                foreach (range('A', 'G') as $col) {
                    $sheet->setCellValue($col . '15', ''); // Set each cell in the range to empty
                }

                // Header for data
                $sheet->setCellValue('A16', 'First Name');
                $sheet->setCellValue('B16', 'Last Name');
                $sheet->setCellValue('C16', 'Subscription');
                $sheet->setCellValue('D16', 'Price (ex. GST)');
                $sheet->setCellValue('E16', 'Payment Date');

                // Set font color to white and background color to black for the header row
                $sheet->getStyle('A16:G16')->getFont()->getColor()->setARGB('FFFFFFFF'); // White font
                $sheet->getStyle('A16:G16')->getFill()->setFillType(Fill::FILL_SOLID);
                $sheet->getStyle('A16:G16')->getFill()->getStartColor()->setARGB('FF000000'); // Black background
                $sheet->getStyle('A16:G16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A16:G16')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


                // Populate data from row 18
                $dataRow = 17;
                $transactions = $this->collection();

                foreach ($transactions as $transaction) {
                    $sheet->setCellValue('A'.$dataRow, $transaction->f_name);
                    $sheet->setCellValue('B'.$dataRow, $transaction->l_name);
                    $sheet->setCellValue('C'.$dataRow, $transaction->title); // Assuming subscription is just a placeholder for '1'
                    $sheet->setCellValue('D'.$dataRow, number_format($transaction->amount, 2));
                    $sheet->setCellValue('E'.$dataRow, \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d'));
                    $dataRow++;
                }

                // Auto-size columns
                foreach (range('A', 'G') as $col) {
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

            },
        ];
    }
}
