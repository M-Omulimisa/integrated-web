<?php
namespace App\Services\Payments;

interface PaymentServiceInterface
{
    /**
     * Sends the OTP to the user with optionally using the reference number
     *
     * @param Country $country : The country of the beneficiary
     * @param string $beneficiary : The recipient of the funds
     * @param string $amount : The mount to receive
     * @param string $details : The payment details
     * @param string $ref : Reference Number to compare with
     * @return void
     */
    public function depositFunds($beneficiary, $amount, $details, $ref = null);
}
