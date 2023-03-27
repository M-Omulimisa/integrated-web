<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings\CountryProvider;
use App\Models\Payments\SubscriptionPayment;
use App\Models\Insurance\InsuranceSubscription;
use App\Services\Payments\PaymentServiceFactory;

class UpdateInsuranceSubscriptionPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:update-insurance-subscription-payment';

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
        $payments = SubscriptionPayment::whereStatus('PENDING')->whereNotNull('insurance_subscription_id')->whereNotNull('reference')->get();

        if ($this->debug) logger(count($payments));

        if (count($payments) > 0) {

            foreach ($payments as $payment) {

                $payment->update(['status' => 'PROCESSING']);

                $PaymentFactory = new PaymentServiceFactory();
                $service = $PaymentFactory->getService($payment->payment_api);

                if ($service) {
                    $response = $service->getTransactionStatus($payment->reference);

                    if($response->Status=='OK'){

                        $new_status = $response->TransactionStatus === "SUCCEEDED" ? 'SUCCESSFUL' : $response->TransactionStatus;
                        $update = $payment->update([ 'status' => $new_status ]);

                        if ($response->TransactionStatus === "SUCCEEDED" || $response->TransactionStatus === "FAILED" || $response->TransactionStatus === "INDETERMINATE") {

                            if ($this->debug) logger('Updated with new status');

                            if ($response->TransactionStatus === "SUCCEEDED" || $response->TransactionStatus === "SUCCESSFUL") {
                                // TODO Send notification to the subscriber

                                $subscription = InsuranceSubscription::find($payment->insurance_subscription_id);
                                $subscription->update([ 'status' => true ]); 
                            }
                        }

                        if (!$update) logger(['UpdateInsuranceSubscriptionPayment' => 'Not updating for TxnID: '.$payment->id]);
                    }
                    else{

                        $new_status = $response->TransactionStatus!='' ? $response->TransactionStatus : 'FAILED';

                        $payment->update([
                            'status'        => $new_status, 
                            'error_message' => $response->StatusMessage
                        ]);

                        if ($this->debug) logger($response->StatusMessage);

                        if ($new_status === "FAILED") {
                            // TODO Send notification to the subscriber
                        }
                    }
                }
            }
        }
    }
}

