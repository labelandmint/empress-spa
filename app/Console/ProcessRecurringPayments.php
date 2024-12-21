<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Square\SquareClient;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;

class ProcessRecurringPayments extends Command
{
    protected $signature = 'payments:process-recurring';
    protected $description = 'Process recurring payments for active subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = now();

        // Query users with active subscriptions (status = 1) whose subscription_end is less than current date
        $users = User::join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.status', 1)
            ->whereNotNull('users.profile_id')
            ->whereNotNull('users.card_id')
            ->where('subscriptions.subscription_end', '<', $currentDate)
            ->get(['users.*', 'subscriptions.amount', 'subscriptions.subscription_end', 'subscription_plans.id as plan_id', 'subscription_plans.payment_frequency']);

        // Process payment for each user
        foreach ($users as $user) {
            $this->info("Processing payment for user: {$user->name}");

            try {
                // Initialize Square client
                $client = new SquareClient([
                    'environment' => 'production',
                    'accessToken' => get_setting('square_access_token'), // Use your Square access token
                ]);

                $paymentsApi = $client->getPaymentsApi();

                // Create the payment amount in cents
                $money = new Money();
                $money->setAmount($user->amount * 100); // Assuming amount is in dollars
                $money->setCurrency(Currency::AUD); 

                // Create payment request (you need to have customer profile ID and nonce or card info)
                $paymentRequest = new CreatePaymentRequest($user->card_id, uniqid('', true));
                $paymentRequest->setCustomerId($user->profile_id);
                $paymentRequest->setAmountMoney($money);
                $paymentRequest->setCurrency(Currency::AUD);
                // Process the payment
                $paymentResponse = $paymentsApi->createPayment($paymentRequest);

                if ($paymentResponse->isSuccess()) {
                    $this->info("Payment processed successfully for user: {$user->name}");

                    $currentDate = now();

                    $newSubscriptionEndDate = match ($subscriptionPlan->frequency) {
                        1 => $currentDate->addWeek()->format('Y-m-d'), // Weekly
                        2 => $currentDate->addMonth()->format('Y-m-d'), // Monthly
                        3 => $currentDate->addMonths(3)->format('Y-m-d'), // Quarterly
                        4 => $currentDate->addMonths(6)->format('Y-m-d'), // Half-Yearly
                        5 => $currentDate->addYear()->format('Y-m-d'), // Yearly
                        default => $currentDate, // Handle unexpected frequency values, if necessary
                    };
                    
                    $newSubscriptionStartDate = $currentDate;

                    Subscription::where('user_id', $user->id)
                        ->update([
                            'subscription_end' => $newSubscriptionEndDate,
                            'subscription_start' => $newSubscriptionStartDate,
                        ]);


                        $paymentMethod = get_setting('payment_gateway');

                        // Store the transaction
                        Transaction::create([
                            'member_id' => $user->id,
                            'transaction_id' => $paymentResponse->getResult()->getPayment()->getId(),
                            'subscription_plan_id' => $user->plan_id,
                            'transaction_type' => 'registration_fee',
                            'payment_method' => $paymentMethod,
                            'amount' => $user->amount, 
                            'status' => 1,
                        ]);   
                } else {
                    $this->error("Failed to process payment for user: {$user->name}");
                }
            } catch (\Exception $e) {
                $this->error("Error processing payment for user: {$user->name} - {$e->getMessage()}");
            }
        }

        $this->info('Recurring payments process completed.');
    }
}
