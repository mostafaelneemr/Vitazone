<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client as GuzzleHttpClient;
use Http\Adapter\Guzzle6\Client;
use Illuminate\Http\Request;

class FawryController extends Controller
{
    public function FawryApi(Request $request)
    {
        $merchantCode    = '1tSa6uxz2nSJkUcUKfUOMw==';
        $merchantRefNum  = $request->order_id . '_' . $request->total;
        $merchant_cust_prof_id  = $request->order_id;
        $payment_method = 'PAYATFAWRY';
        $amount = $request->total;
        $merchant_sec_key =  '07ca5b517d0748b5a4f6842b53f9c946'; // For the sake of demonstration
        $signature = hash('sha256', $merchantCode . $merchantRefNum . $merchant_cust_prof_id . $payment_method . $amount . $merchant_sec_key);
        $httpClient = new GuzzleHttpClient();
        $response = $httpClient->post('https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ],
            'body' => json_encode([
                'merchantCode' => $merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'customerName' => 'Ahmed Ali',
                'customerMobile' => '01234567891',
                'customerEmail' => 'example@gmail.com',
                'customerProfileId' => '777777',
                'amount' => '580.55',
                'paymentExpiry' => 1631138400000,
                'currencyCode' => 'EGP',
                'language' => 'en-gb',
                'chargeItems' => [
                    'itemId' => '897fa8e81be26df25db592e81c31c',
                    'description' => 'Item Description',
                    'price' => '580.55',
                    'quantity' => '1'
                ],
                'signature' => $signature,
                'payment_method' => $payment_method,
                'description' => 'example description'
            ], true)
        ]);
        // $response = json_decode($response->getBody()->getContents(), true);
        // return $response;
        // $paymentStatus = $response['type']; // get response values
    }
}
