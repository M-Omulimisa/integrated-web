<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings\CountryProvider;
use App\Models\Market\MarketSubscription;
use App\Models\Payments\SubscriptionPayment;
use App\Services\Payments\PaymentServiceFactory;
use App\Models\Ussd\UssdSessionData;

class ProcessMarketSubscriptionPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:process-market-subscription-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $debug = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = SubscriptionPayment::whereStatus('INITIATED')
                                        ->whereNotNull('market_session_id')
                                        ->whereNotNull('payment_api')
                                        ->whereNotNull('reference_id')
                                        ->whereIn('provider',function($query) {
                                            $query->select('name')->from(with(new CountryProvider)->getTable());
                                        })
                                        ->orWhere('status', 'PENDING')
                                        ->whereNotNull('market_session_id')
                                        ->whereNotNull('reference')
                                        ->get();

        if ($this->debug) logger('count: '.count($payments));

        if (count($payments) > 0) {

            foreach ($payments as $payment) {

                $initial_status = $payment->status;

                $payment->update(['status' => 'PROCESSING']);

                $PaymentFactory = new PaymentServiceFactory();
                $service = $PaymentFactory->getService($payment->payment_api);

                if ($service) {
                    $service->set_URL();
                    $service->set_username();
                    $service->set_password();

                    if ($initial_status=="INITIATED") {
                        $response = $service->depositFunds($payment->account, $payment->amount, $payment->narrative, $payment->reference_id);
                    }
                    elseif ($initial_status=="PENDING") {
                        $response = $service->getTransactionStatus($payment->reference);
                    }

                    if(isset($response) && $response->Status=='OK'){
                        $new_status = $response->TransactionStatus === "SUCCEEDED" ? 'SUCCESSFUL' : $response->TransactionStatus;
                        $update = $payment->update(['status' => $new_status]);

                        if(is_null($payment->reference)) $payment->update(['reference' => $response->TransactionReference]);

                        if ($response->TransactionStatus === "SUCCEEDED" || $response->TransactionStatus === "SUCCESSFUL") {

                            // TODO Send notification to the subscriber

                            if ($payment->tool=="USSD") {
                                if ($session = UssdSessionData::whereId($payment->market_session_id)->first()) {
                                    $data = [
                                        'phone' => $payment->account,
                                        'region_id' => $session->market_region_id,
                                        'language_id' => $session->market_language_id,
                                        'package_id' => $session->market_package_id,

                                        'frequency'     => ucfirst($session->market_frequency),
                                        'period_paid'   => $session->market_frequency_count,
                                        'start_date'    => date("Y-m-d"),
                                        'end_date'      => getSubscritionEndDate(ucfirst($session->market_frequency), $session->market_frequency_count, date("Y-m-d")),
                                        'status'        => TRUE,
                                        'payment_id' => $payment->id
                                    ];
                                }
                            }

                            if (isset($data) && $data) {
                                // TODO Multiple subscriptions for 1 payment                                

                                // Subscription already exists -- Payment has been reset
                                if ($subscription = MarketSubscription::wherePaymentId($payment->id)->first()) {
                                    $subscription->update($data);
                                }
                                else{
                                    MarketSubscription::create($data);                                    
                                }
                            }
                            else{
                                logger(['ProcessMarketSubscriptionPayment' => 'No session found for TxnID: '.$payment->id]);
                            }
                        }
                        
                        if (!$update) logger(['ProcessMarketSubscriptionPayment' => 'Not updating for TxnID: '.$payment->id]);
                    }
                    elseif(isset($response)) {
                        $new_status = $response->TransactionStatus!='' ? $response->TransactionStatus : 'FAILED';

                        $payment->update([
                            'status'        => $new_status, 
                            'error_message' => $response->StatusMessage
                        ]);

                        if ($this->debug) logger($response->StatusMessage);

                        if ($new_status === "FAILED") {
                            // TODO Send notification to the subscriber
                            logger(['UpdateMarketSubscriptionPayment' => 'Payment failed for TxnID: '.$payment->id]);
                        }
                    }
                    else{
                        logger(['UpdateMarketSubscriptionPayment' => 'NULL response for TxnID: '.$payment->id]);
                    }
                }
            }
        }
    }
}
