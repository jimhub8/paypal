<?php

namespace App\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class CreatePlan extends Paypal
{

    public function execute()
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdrdXAB7OLpZJXDBnggg46GjYLBWd9zP7S-PXYXbUEGRZTmDPONyZHh7xNwVtuks2SPEvlkvst59D03f',     // ClientID
                'EFAOEdJRuOIrE3HcLK2__1MBFCu7_WY-NgsBy7EU7t7aedDW4SVqjzLPyfPJXNzU_gR7FUKN6zO9tb_u'      // ClientSecret
            )
        );
        $paymentId = request('paymentId');
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();


        $details->setShipping(2.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);

        $amount->setCurrency('USD');
        $amount->setTotal(21);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        return $payment;
    }
}
