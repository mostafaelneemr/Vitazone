<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use PDF;
use Auth;
use MPDF;
use App\GeneralSetting;

class InvoiceController extends Controller
{
    //downloads customer invoice
    public function customer_invoice_download($id)
    {
        $generalsetting = GeneralSetting::select(['*','site_name_'.locale().' as site_name', 'address_'.locale().' as address', 'invoice_instructions_'.locale().' as instruction'])->first();
        $order = Order::findOrFail($id);
        $name = $generalsetting->site_name;
        $address = $generalsetting->address;
        $instruction = $generalsetting->instruction;
        $pdf = MPDF::loadView('invoices.customer_invoice', compact('order', 'generalsetting','name', 'address', 'instruction'));
        // $pdf = PDF::setOptions([
        //                 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
        //                 'logOutputFile' => storage_path('logs/log.htm'),
        //                 'tempDir' => storage_path('logs/')
        //             ])->loadView('invoices.customer_invoice', compact('order', 'generalsetting'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads seller invoice
    public function seller_invoice_download($id)
    {
        $generalsetting = GeneralSetting::select(['*','site_name_'.locale().' as site_name', 'address_'.locale().' as address', 'invoice_instructions_'.locale().' as instruction'])->first();
        $name = $generalsetting->site_name;
        $address = $generalsetting->address;
        $instruction = $generalsetting->instruction;

        $order = Order::findOrFail($id);
        // $pdf = new \Mpdf\Mpdf(['autoArabic' => true]);
        // $pdf->WriteHTML(\View::make('email.email-invoice')->with('data', $response)->render());

        $pdf = MPDF::loadView('invoices.seller_invoice', compact('order', 'generalsetting', 'name', 'address', 'instruction'));
        // $pdf = PDF::setOptions([
        //                 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
        //                 'logOutputFile' => storage_path('logs/log.htm'),
        //                 'tempDir' => storage_path('logs/')
        //             ])->loadView('invoices.seller_invoice', compact('order', 'generalsetting'));
        // return $pdf->stream('order-'.$order->code.'.pdf');
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads admin invoice
    public function admin_invoice_download($id)
    {
        $generalsetting = GeneralSetting::select(['*','site_name_'.locale().' as site_name', 'address_'.locale().' as address', 'invoice_instructions_'.locale().' as instruction'])->first();
        $order = Order::findOrFail($id);
        $name = $generalsetting->site_name;
        $address = $generalsetting->address;
        $instruction = $generalsetting->instruction;
        $pdf = MPDF::loadView('invoices.admin_invoice', compact('order', 'generalsetting','name', 'address', 'instruction'));
        // $pdf = PDF::setOptions([
        //                 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
        //                 'logOutputFile' => storage_path('logs/log.htm'),
        //                 'tempDir' => storage_path('logs/')
        //             ])->loadView('invoices.admin_invoice', compact('order', 'generalsetting'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }
}
