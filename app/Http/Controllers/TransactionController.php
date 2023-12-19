<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function generateUni5payQRCode(Request $request)
    {
        $response = Http::withHeaders([
            'apiKey'  => env("UNI5PAY_API_KEY"),
        ])
        ->timeout(30)
        ->retry(5, 250)
        ->post("https://payment.uni5pay.sr/v1/qrcode_get", [
            "mchtOrderNo" => "200",
            "terminalId" => "SAD",
            "amount" => "30",
            "currency" => "968",
            "url_success" => "https://google.com"
        ]);

        $response = json_decode($response->body());

        return response()->json($response, 200);
    }

    public function generateUni5payLink(Request $request)
    {
        $response = Http::withHeaders([
            'apiKey' => env('UNI5PAY_API_KEY')
        ])
        ->timeout(30)
        ->retry(5, 250)
        ->post("https://payment.uni5pay.sr/v1/qrcode_online", [
            "mchtOrderNo" => "200",
            "terminalId" => "SAR",
            "payment_desc" => "This is a test payment",
            "amount" => "30",
            "currency" => "968",
            "url_success" => "https://www.google.com/",
            "url_failure" => "https://www.newegg.com/",
            "url_notify" => "https://www.facebook.com/"
        ]);

        $response = json_decode($response->body());

        return response()->json($response, 200);
    }

    public function generateMopeLink(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer " . env('MOPE_API_KEY')
        ])
        ->retry(3, 250)
        ->post("https://api.mope.sr/api/shop/payment_request", [
            "description" => "Order for a lot of beer",
            "amount" => "123400",
            "order_id" => "beer-order-id-87835822",
            "currency" => "SRD",
            "redirect_url" => "https://google.com"
        ]);

        $response = json_decode($response->body());

        return response()->json($response, 200);
    }
}
