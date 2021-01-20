<?php

namespace App\Paypal;

use App\Models\PoSubscription;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;

class SubscriptionPlan extends Paypal
{
    public function create($data)
    {
        try {
            $plan = $this->Plan($data);

            $paymentDefinition = $this->PaymentDefinition($data);

            $chargeModel = $this->chargeModel();

            $paymentDefinition->setChargeModels(array($chargeModel));

            $merchantPreferences = $this->merchantPreferences();

            $plan->setPaymentDefinitions(array($paymentDefinition));
            $plan->setMerchantPreferences($merchantPreferences);
            // dd($this->apiContext, $patchRequest);

            $output = $plan->create($this->apiContext);

            $paypal_res = json_decode($output);

            // dd($paypal_res->payment_definitions[0]->id);
            $subscription = PoSubscription::updateOrCreate(
                [
                    'subscription' => $data['subscription']
                ],
                [
                    'user_id' => 1,
                    'subscription_description' => $data['subscription_description'],
                    // 'features' => $data['features'],
                    'subscription_amount' => $data['subscription_amount'],
                    'subscription_id' => $paypal_res->id,
                    'paypal_data' => $paypal_res
                ]
            );
            dd($paypal_res);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @return Plan
     */
    protected function Plan($data): Plan
    {
        // dd($data['subscription']);
        $plan = new Plan();
        $plan->setName($data['subscription'])
            ->setDescription($data['subscription_description'])
            ->setType('fixed');
        return $plan;
    }

    /**
     * @return PaymentDefinition
     */
    protected function PaymentDefinition($data): PaymentDefinition
    {
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('DAY')
            ->setFrequencyInterval("2")
            ->setCycles("12")
            ->setAmount(new Currency(array('value' => $data['subscription_amount'], 'currency' => 'USD')));
        return $paymentDefinition;
    }

    /**
     * @return ChargeModel
     */
    protected function chargeModel(): ChargeModel
    {
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
            ->setAmount(new Currency(array('value' => 0, 'currency' => 'USD')));
        return $chargeModel;
    }

    /**
     * @return MerchantPreferences
     */
    protected function merchantPreferences(): MerchantPreferences
    {
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(config('services.paypal.url.executeAgreement.success'))
            ->setCancelUrl(config('services.paypal.url.executeAgreement.failure'))
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));
        return $merchantPreferences;
    }

    /**
     *
     */
    public function listPlan()
    {
        try {
            $params = array('page_size' => '20');
            $planList = Plan::all($params, $this->apiContext);
            return $planList;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function planDetails($id)
    {
        $plan = Plan::get($id, $this->apiContext);
        return $plan;
    }

    public function activate($id)
    {
        try {
            $createdPlan = $this->planDetails($id);
            $patch = new Patch();
            $value = new PayPalModel('{
                    "state":"ACTIVE"
                    }');

            $patch->setOp('replace')
                ->setPath('/')
                ->setValue($value);
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);

            $createdPlan->update($patchRequest, $this->apiContext);

            $plan = Plan::get($createdPlan->getId(), $this->apiContext);

            $subscription_ = PoSubscription::where('subscription_id', $id)->first();
            $subscription_->status = 'Active';
            $subscription_->save();


            dd($plan);
        } catch (\Exception $e) {
            dd($e);
        }
    }


    public function deactivate($id)
    {
        try {
            $createdPlan = $this->planDetails($id);
            $result = $createdPlan->delete($this->apiContext);

            $subscription_ = PoSubscription::where('subscription_id', $id)->first();
            $subscription_->status = 'Inactive';
            $subscription_->save();

            dd($result);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function update_plan($data)
    {
        // dd($data);
        $id = $data['id'];
        try {
            $patch = new Patch();

            $createdPlan = $this->planDetails($id);

            $paymentDefinitions = $createdPlan->getPaymentDefinitions();
            $paymentDefinitionId = $paymentDefinitions[0]->getId();
            $patch->setOp('replace')
                ->setPath('/payment-definitions/' . $paymentDefinitionId)
                ->setValue(
                    //     json_decode(
                    //     '{
                    //             "name": "Updated Payment Definition",
                    //             "frequency": "Day",
                    //             "amount": {
                    //                 "currency": "USD",
                    //                 "value": "5"
                    //             }
                    //     }'
                    // )

                    [
                        "name" => $data['subscription'],
                        "frequency" => "Day",
                        "amount" => [
                            "currency" => "USD",
                            "value" => $data['subscription_amount']
                        ]

                    ]
                );
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            // dd($this->apiContext, $patchRequest);
            $createdPlan->update($patchRequest, $this->apiContext);

            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
            $paypal_res = json_decode($plan);
            $subscription = PoSubscription::updateOrCreate(
                [
                    'subscription' => $data['subscription']
                ],
                [
                    'user_id' => 1,
                    'subscription_description' => $data['subscription_description'],
                    // 'features' => $data['features'],
                    'subscription_amount' => $data['subscription_amount'],
                    'subscription_id' => $paypal_res->id,
                    'paypal_data' => $paypal_res
                ]
            );

            dd($subscription, $paypal_res);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function plan_price($id)
    {
        try {
            $patch = new Patch();

            $createdPlan = $this->planDetails($id);
            $paymentDefinitions = $createdPlan->getPaymentDefinitions();
            $paymentDefinitionId = $paymentDefinitions[0]->getId();
            $patch->setOp('replace')
                ->setPath('/payment-definitions/' . $paymentDefinitionId)
                ->setValue(json_decode(
                    '{
                            "name": "Updated Payment Definition",
                            "frequency": "Day",
                            "amount": {
                                "currency": "USD",
                                "value": "2"
                            }
                    }'
                ));
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);

            $createdPlan->update($patchRequest, $this->apiContext);

            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function suspend()
    {
        //Create an Agreement State Descriptor, explaining the reason to suspend.
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Suspending the agreement");

        /** @var Agreement $createdAgreement */
        $createdAgreement = null; // Replace this with your fetched agreement object

        try {
            $createdAgreement->suspend($agreementStateDescriptor, $this->apiContext);

            $agreement = Agreement::get($createdAgreement->getId(), $this->apiContext);
            return $agreement;
        } catch (\Exception $e) {

            dd($e);
        }
    }
    /*
    public function reactivate()
    {
        //Create an Agreement State Descriptor, explaining the reason to suspend.
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Reactivating the agreement");

        $suspendedAgreement = new SuspendBillingAgreement;
        try {
            $suspendedAgreement->reActivate($agreementStateDescriptor, $this->apiContext);

            $agreement = Agreement::get($suspendedAgreement->getId(), $this->apiContext);
            return $agreement;
        } catch (\Exception $e) {
            dd($e);
        }
    } */
}
