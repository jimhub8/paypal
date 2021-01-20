<?php

namespace App\Paypal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

class PaypalAgreement extends Paypal
{
    public function create($id)
    {
        return redirect($this->agreement($id));
    }

    /**
     * @param $id
     * @return string
     */
    protected function agreement($id): string
    {
        try {
            $agreement = new Agreement();
            $agreement->setName('Base Agreement')
                ->setDescription('Basic Agreement')
                ->setStartDate(Carbon::now()->addMonth());

            $agreement->setPlan($this->plan($id));

            $agreement->setPayer($this->payer());

            $agreement->setShippingAddress($this->shippingAddress());

            $agreement = $agreement->create($this->apiContext);

            // return redirect($agreement->getApprovalLink());
            // return Redirect::to($agreement->getApprovalLink());
            // redirect()->away($agreement->getApprovalLink());
            // return redirect()->away('www.google.com');

            return ($agreement->getApprovalLink());
        }  catch (\Exception $e) {
            dd($e);
            // \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            // return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    /**
     * @param $id
     * @return Plan
     */
    protected function plan($id): Plan
    {
        $plan = new Plan();
        $plan->setId($id);
        return $plan;
    }

    /**
     * @return Payer
     */
    protected function payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        return $payer;
    }

    /**
     * @return ShippingAddress
     */
    protected function shippingAddress(): ShippingAddress
    {
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1('111 First Street')
            ->setCity('Saratoga')
            ->setState('CA')
            ->setPostalCode('95070')
            ->setCountryCode('US');
        return $shippingAddress;
    }

    public function execute($token)
    {
        $agreement = new Agreement();
        $agreement->execute($token, $this->apiContext);

        dd($agreement);
    }
}
