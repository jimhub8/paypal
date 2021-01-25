<?php

namespace App\Paypal;

use App\Models\BillingAgreement;
use App\Models\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
                ->setStartDate(Carbon::now());

            $agreement->setPlan($this->plan($id));

            $agreement->setPayer($this->payer());

            $agreement->setShippingAddress($this->shippingAddress());

            $agreement = $agreement->create($this->apiContext);

            // return redirect($agreement->getApprovalLink());
            // return Redirect::to($agreement->getApprovalLink());
            // redirect()->away($agreement->getApprovalLink());
            // return redirect()->away('www.google.com');

            return ($agreement->getApprovalLink());
        } catch (\Exception $e) {
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


        $logs = json_decode($agreement);
        // dd($logs->plan->payment_definitions[0]->amount->value);
        $billing = new BillingAgreement();
        $billing->billing($logs);

        dd($agreement);
    }

    public function agreement_details()
    {
        $createdAgreement = "I-XM06WLA1VV4E";
        try {
            $agreement = Agreement::get($createdAgreement, $this->apiContext);
        } catch (\Exception $e) {
            dd($e);
            // \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            // return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }

        // ResultPrinter::printResult("Retrieved an Agreement", "Agreement", $agreement->getId(), $createdAgreement->getId(), $agreement);

        return $agreement;
    }

    public function upgrade()
    {
        try {
            // $client = new Client();
            // $url = 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-FL016PU1HWDB/revise';
            // $response = $client->request('POST', $url, [
            //     'headers' => [
            //         'Accept' => 'application/json',
            //         'Content-Type' => 'application/json',
            //         'Authorization' => env('PAYPAL_TOKEN')
            //     ],
            //     'form_params' => [
            //         "plan_id" => "P-62P63683BE465901UFDS576A"
            //     ],
            // ]);


            // curl -v -X GET https://api-m.sandbox.paypal.com/v1/payments/billing-plans?page_size=3&status=ALL&page_size=2&page=1&total_required=yes \
            // -H "Content-Type: application/json" \
            // -H "Authorization: Bearer A21AAKKafR8j0UqnR8IQdppwTKqeD1wF3MEQwvg5HhdEvMp4J652hzxztdhFAvW3zwFon3TBVJ8fulGanI3Wa04wHe2POJAJw"

            $url = 'https://api-m.sandbox.paypal.com/v1/payments/billing-plans?page_size=3&status=ALL&page_size=2&page=1&total_required=yes';
            // $url = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-FL016PU1HWDB/revise";



            $token =  'Bearer A21AAKKafR8j0UqnR8IQdppwTKqeD1wF3MEQwvg5HhdEvMp4J652hzxztdhFAvW3zwFon3TBVJ8fulGanI3Wa04wHe2POJAJw';
            // dd($token);


            $data = [
                "plan_id" => "P-62P63683BE465901UFDS576A"
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    // Set here requred headers
                    // "accept: */*",
                    // "accept-language: en-US,en;q=0.8",
                    // 'Accept' => 'application/json',
                    "content-type: application/json",
                    'Authorization' => $token
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                dd($err);
                // echo "cURL Error #:" . $err;
            } else {
                dd($response);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    public function token()
    {
        try {
            // $client = new Client();
            // $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
            // $response = $client->request('POST', $url, [
            //     'headers' => [
            //         'Accept' => 'application/json',
            //         'Content-Type' => 'application/json',
            //         'Authorization' => env('PAYPAL_TOKEN')
            //     ],
            //     'form_params' => [
            //         "plan_id" => "P-5ML4271244454362WXNWU5NQ"
            //     ],
            // ]);

            $data = [
                "plan_id" => "P-5ML4271244454362WXNWU5NQ"
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/revise",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    // Set here requred headers
                    // "accept: */*",
                    // "accept-language: en-US,en;q=0.8",
                    'Accept' => 'application/json',
                    "content-type: application/json",
                    'Authorization' => env('PAYPAL_TOKEN')
                ),
            ));



            // curl -v -X POST https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/revise
            //     -H "Content-Type: application/json" \
            //     -H "Authorization: Bearer <Access-Token>" \
            //     -d '{
            //     "plan_id": "P-5ML4271244454362WXNWU5NQ"
            //     }'
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
