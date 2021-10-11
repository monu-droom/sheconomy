<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use PDF;
use Auth;

class InvoiceController extends Controller
{
    //downloads customer invoice
    public function customer_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $order_details = \App\OrderDetail::where('order_id', $order->id)->first();
        // dd($order_details);
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('invoices.customer_invoice', compact('order', 'order_details'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads seller invoice
    public function seller_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $order_details = \App\OrderDetail::where('order_id', $order->id)->first();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('invoices.seller_invoice', compact('order', 'order_details'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }

    //downloads admin invoice
    public function admin_invoice_download($id)
    {
        $order = Order::findOrFail($id);
        $order_details = \App\OrderDetail::where('order_id', $order->id)->first();
        $pdf = PDF::setOptions([
                        'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                        'logOutputFile' => storage_path('logs/log.htm'),
                        'tempDir' => storage_path('logs/')
                    ])->loadView('invoices.admin_invoice', compact('order', 'order_details'));
        return $pdf->download('order-'.$order->code.'.pdf');
    }
}
