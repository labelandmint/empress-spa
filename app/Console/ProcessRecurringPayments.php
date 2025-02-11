<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Square\SquareClient;
use Illuminate\Support\Facades\Log;
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
        Log::info("Starting payment deduction cron at {$currentDate}");

        $users = User::join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.status', 1)
            ->whereNotNull('users.profile_id')
            ->whereNotNull('users.card_id')
            ->where('subscriptions.subscription_end', '<', $currentDate)
            ->get(['users.*', 'subscription_plans.price_of_subscription AS amount', 'subscriptions.subscription_end', 'subscription_plans.id as plan_id', 'subscription_plans.payment_frequency']);

        Log::info("Found " . count($users) . " users with expired subscriptions.");

        foreach ($users as $user) {
            Log::info("Processing payment for user: {$user->id} ({$user->name})");

            try {
                $client = new SquareClient([
                    'environment' => 'production',
                    'accessToken' => get_setting('square_access_token'),
                ]);

                $paymentsApi = $client->getPaymentsApi();

                $money = new Money();
                $money->setAmount(floatval($user->amount) * 100);
                $money->setCurrency(Currency::AUD);

                $paymentRequest = new CreatePaymentRequest($user->card_id, uniqid('', true));
                $paymentRequest->setCustomerId($user->profile_id);
                $paymentRequest->setAmountMoney($money);

                $paymentResponse = $paymentsApi->createPayment($paymentRequest);

                if ($paymentResponse->isSuccess()) {
                    Log::info("Payment successful for user: {$user->id}");

                    $currentDate = now();
                    $newSubscriptionEndDate = match ($user->payment_frequency) {
                        1 => $currentDate->addWeek()->format('Y-m-d'),
                        2 => $currentDate->addMonth()->format('Y-m-d'),
                        3 => $currentDate->addMonths(3)->format('Y-m-d'),
                        4 => $currentDate->addMonths(6)->format('Y-m-d'),
                        5 => $currentDate->addYear()->format('Y-m-d'),
                        default => $currentDate,
                    };

                    $updateStatus = Subscription::where('user_id', $user->id)->update([
                        'subscription_end' => $newSubscriptionEndDate,
                        'subscription_start' => $currentDate,
                    ]);

                    if ($updateStatus) {
                        Log::info("Updated subscription for user: {$user->id} to end on {$newSubscriptionEndDate}");
                    } else {
                        Log::error("Failed to update subscription for user: {$user->id}");
                    }

                    $paymentMethod = get_setting('payment_gateway');

                    $transaction = Transaction::create([
                        'member_id' => $user->id,
                        'transaction_id' => $paymentResponse->getResult()->getPayment()->getId(),
                        'subscription_plan_id' => $user->plan_id,
                        'transaction_type' => 'registration_fee',
                        'payment_method' => $paymentMethod,
                        'amount' => $user->amount,
                        'status' => 1,
                    ]);

                    if ($transaction) {
                        Log::info("Transaction recorded for user: {$user->id}, transaction ID: {$transaction->transaction_id}");
                    } else {
                        Log::error("Failed to store transaction for user: {$user->id}");
                    }
                } else {
                    Log::error("Payment failed for user: {$user->id}, Error: " . json_encode($paymentResponse->getErrors()));
                }
            } catch (\Exception $e) {
                Log::error("Exception during payment for user: {$user->id} - {$e->getMessage()}");
            }
        }

        Log::info('Recurring payments process completed.');
    }
}
