<?php

namespace App\Http\Controllers;

use App\Models\Token;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function subscription_transaction()
    {
        try {
            $client = new Client();
            $token = 'Bearer A21AAIqXMqHnEsIB0YnluAPQ_1YWu6JKqSI_-eiXvPDatcHGfGzdYQtfkFgfefzxH6z2h9R18l0nh5JKpN7H2YQ3cHKHviCrg';
            $url = 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-DE41HL5XBL16/transactions?start_time=2018-01-21T07:50:20.940Z&end_time=2021-01-26T07:50:20.940Z';
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => $token
                ]
            ]);

            return $response = $response->getBody()->getContents();
            dd($response);
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function token()
    {
        try {
            $client = new Client();
            $token = 'Basic QWRyZFhBQjdPTHBaSlhEQm5nZ2c0NkdqWUxCV2Q5elA3Uy1QWFlYYlVFR1JaVG1EUE9OeVpIaDd4TndWdHVrczJTUEV2bGt2c3Q1OUQwM2Y6RUZBT0VkSlJ1T0lyRTNIY0xLMl9fMU1CRkN1N19XWS1OZ3NCeTdFVTd0N2FlZERXNFNWcWp6TFB5ZlBKWE56VV9nUjdGVUtONnpPOXRiX3U=';
            $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => $token
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            return $response = $response->getBody()->getContents();
            $token = new Token;
            $token->token_type = $response['token_type'];
            $token->access_token = $response['access_token'];
            $token->expires_in = $response['expires_in'];
            $token->platform = 'Paypal';
            $token->save();

            dd($response);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
