<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings\CountryProvider;
use App\Models\Insurance\InsuranceSubscription;
use App\Models\Payments\SubscriptionPayment;
use App\Services\Payments\PaymentServiceFactory;
use App\Models\Ussd\UssdSessionData;

class ProcessInsuranceSubscriptionPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:process-insurance-subscription-payment';

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
                                        ->whereNotNull('insurance_session_id')
                                        ->whereNotNull('payment_api')
                                        ->whereNotNull('reference_id')
                                        ->whereIn('provider',function($query) {
                                            $query->select('name')->from(with(new CountryProvider)->getTable());
                                        })
                                        ->orWhere('status', 'PENDING')
                                        ->whereNotNull('insurance_session_id')
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
                                if ($session = UssdSessionData::whereId($payment->insurance_session_id)->first()) {
                                    $data = [
                                        'agent_phone' => $session->referee_phone ?? null,
                                        'district_id'   => $session->insurance_district_id,
                                        'subcounty_id'  => $session->insurance_subcounty_id,

                                        'first_name'    => 'None',
                                        'last_name'     => 'None',
                                        // 'location_id'   => $session->insurance_district_id,
                                        'phone'         => $session->insurance_subscriber,
                                        'season_id'     => $session->insurance_season_id,
                                        'enterprise_id' => $session->insurance_enterprise_id,
                                        'acreage'       => $session->insurance_acreage,
                                        'sum_insured'   => $session->insurance_sum_insured,
                                        'premium'       => $session->insurance_premium,
                                        'status'        => TRUE,
                                        'payment_id' => $payment->id
                                    ];
                                }
                            }

                            if (isset($data) && $data) {
                                InsuranceSubscription::create($data);
                                $this->saveOtherInsurance($session, $payment);
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
                            logger(['UpdateInsuranceSubscriptionPayment' => 'Payment failed for TxnID: '.$payment->id]);
                        }
                    }
                    else{
                        logger(['UpdateInsuranceSubscriptionPayment' => 'NULL response for TxnID: '.$payment->id]);
                    }
                }
            }
        }
    }

    private function saveOtherInsurance($subscription, $payment)
    {
        if (count($subscription->insurance_list) > 0) {
            foreach ($subscription->insurance_list as $list) {

                $data = [
                            'agent_phone'   => $subscription->referee_phone ?? null,
                            'district_id'   => $subscription->insurance_district_id,
                            'subcounty_id'  => $subscription->insurance_subcounty_id,

                            'first_name'    => 'None',
                            'last_name'     => 'None',
                            // 'location_id'   => $session->insurance_district_id,
                            'phone'         => $subscription->insurance_subscriber,
                            'season_id'     => $subscription->insurance_season_id,
                            'enterprise_id' => $list->insurance_enterprise_id,
                            'acreage'       => $list->insurance_acreage,
                            'sum_insured'   => $list->insurance_sum_insured,
                            'premium'       => $list->insurance_premium,
                            'status'        => TRUE,
                            'payment_id' => $payment->id
                        ];

                InsuranceSubscription::create($data);
            }
        }
    }
}
