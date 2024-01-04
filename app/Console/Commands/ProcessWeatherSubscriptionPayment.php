<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings\CountryProvider;
use App\Models\Weather\WeatherSubscription;
use App\Models\Payments\SubscriptionPayment;
use App\Services\Payments\PaymentServiceFactory;
use App\Models\Ussd\UssdSessionData;
use App\Services\OtpServices\ServiceFactory;

class ProcessWeatherSubscriptionPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:process-weather-subscription-payment';

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
                                        ->whereNotNull('weather_session_id')
                                        ->whereNotNull('payment_api')
                                        ->whereNotNull('reference_id')
                                        ->whereIn('provider',function($query) {
                                            $query->select('name')->from(with(new CountryProvider)->getTable());
                                        })
                                        ->orWhere('status', 'PENDING')
                                        ->whereNotNull('weather_session_id')
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

                    if(isset($response) && $response->Status=='OK') {
                        $new_status = $response->TransactionStatus === "SUCCEEDED" ? 'SUCCESSFUL' : $response->TransactionStatus;
                        $update = $payment->update(['status' => $new_status]);

                        if(is_null($payment->reference)) $payment->update(['reference' => $response->TransactionReference]);

                        if ($response->TransactionStatus === "SUCCEEDED" || $response->TransactionStatus === "SUCCESSFUL") {

                            if ($payment->tool=="USSD") {
                                if ($session = UssdSessionData::whereId($payment->weather_session_id)->first()) {
                                    $data = [
                                        'phone' => $payment->account,
                                        'district_id'   => $session->weather_district_id,
                                        'subcounty_id'  => $session->weather_subcounty_id,
                                        'parish_id'     => $session->weather_parish_id,
                                        'frequency'     => ucfirst($session->weather_frequency),
                                        'period_paid'   => $session->weather_frequency_count,
                                        'start_date'    => date("Y-m-d"),
                                        'end_date'      => getSubscritionEndDate(ucfirst($session->weather_frequency), $session->weather_frequency_count, date("Y-m-d")),
                                        'status'        => TRUE,
                                        'payment_id' => $payment->id
                                    ];
                                }
                            }

                            // TODO for App & Web

                            if (isset($data) && $data) {
                                // Subscription already exists -- Payment has been reset
                                if ($subscription = WeatherSubscription::wherePaymentId($payment->id)->first()) {
                                    $subscription->update($data);
                                }
                                else{
                                    $subscription = WeatherSubscription::create($data);                                    
                                }

                                if ($subscription) {
                                    $message = "Hello, your weather info subscription worth UGX ".number_format($payment->amount).", ".$subscription->frequency."(".$subscription->period_paid.") was successful. Alerts will be sent between midnight and 6AM. M-Omulimisa";  
                                    $recipient = $subscription->phone;                                  
                                }
                            }
                            else{
                                logger(['ProcessMarketSubscriptionPayment' => 'No session found for TxnID: '.$payment->id]);
                            }
                        }
                        elseif ($response->TransactionStatus === "FAILED") {
                            $message = "Hello, your weather info subscription worth UGX ".number_format($payment->amount)." failed. Please try again. M-Omulimisa";
                            $recipient = $payment->account;
                        }
                        
                        if (!$update) logger(['ProcessMarketSubscriptionPayment' => 'Not updating for TxnID: '.$payment->id]);
                    }
                    elseif(isset($response)) {
                        $new_status = isset($response->TransactionStatus) && $response->TransactionStatus!='' ? $response->TransactionStatus : 'FAILED';

                        $payment->update([
                            'status'        => $new_status, 
                            'error_message' => $response->StatusMessage
                        ]);

                        if ($this->debug) logger($response->StatusMessage);

                        if ($new_status === "FAILED") {
                            $message = "Hello, your weather info subscription worth UGX ".number_format($payment->amount)." failed. Please try again. M-Omulimisa";
                            $recipient = $payment->account;
                            logger(['UpdateWeatherSubscriptionPayment' => 'Payment failed for TxnID: '.$payment->id]);
                        }
                    }
                    else{
                        logger(['UpdateWeatherSubscriptionPayment' => 'NULL response for TxnID: '.$payment->id]);
                    }

                    if (isset($message)) {
                        $SMSFactory = new ServiceFactory();
                        $service = $SMSFactory->getService(config("otp.otp_default_service", null));

                        if ($service) {
                            $result = $service->sendTextMessage($recipient, $message);
                        }
                    }
                }
            }
        }
    }
}
