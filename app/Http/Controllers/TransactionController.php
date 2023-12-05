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

    public function generateQRCode(Request $request)
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
}
