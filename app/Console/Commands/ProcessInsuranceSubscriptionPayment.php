<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings\CountryProvider;
use App\Models\Payments\SubscriptionPayment;
use App\Models\Insurance\InsuranceSubscription;
use App\Services\Payments\PaymentServiceFactory;

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
        $payments = SubscriptionPayment::whereStatus('INITIATED')->whereNotNull('insurance_subscription_id')->whereNotNull('payment_api')->whereNotNull('reference_id')->whereIn('provider',function($query) {
            $query->select('name')->from(with(new CountryProvider)->getTable());
        })->get();

        if ($this->debug) logger(count($payments));

        if (count($payments) > 0) {

            foreach ($payments as $payment) {

                $payment->update(['status' => 'PROCESSING']);

                $PaymentFactory = new PaymentServiceFactory();
                $service = $PaymentFactory->getService($payment->payment_api);

                if ($service) {
                    $response = $service->depositFunds($payment->account, $payment->amount, $payment->narrative, $payment->reference_id);

                    if($response->Status=='OK'){
                        // Save this transaction for future reference
                        $update = $payment->update([
                            'status'    => $response->TransactionStatus === "SUCCEEDED" ? 'SUCCESSFUL' : $response->TransactionStatus, 
                            'reference' => $response->TransactionReference
                        ]);

                        if ($response->TransactionStatus === "SUCCEEDED" || $response->TransactionStatus === "SUCCESSFUL") {

                            // TODO Send notification to the subscriber

                            $subscription = InsuranceSubscription::find($payment->insurance_subscription_id);
                            $subscription->update([ 'status' => true ]);
                        }
                        
                        if (!$update) logger(['ProcessInsuranceSubscriptionPayment' => 'Not updating for TxnID: '.$payment->id]);
                    }
                    else{
                        $payment->update([
                            'status'        => $response->TransactionStatus!='' ? $response->TransactionStatus : 'FAILED', 
                            'error_message' => $response->StatusMessage
                        ]);

                        if ($this->debug) logger($response->StatusMessage);
                    }
                }
            }
        }
    }
}