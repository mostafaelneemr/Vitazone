<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;


class PaymentController extends Controller
{
    public function index()
    {
    	$payments = Payment::where('seller_id', auth('api')->user()->id)->orderBy('created_at', 'desc')->get();

    	return response()->json([
            'payments' => $payments
        ]);
    }
}
