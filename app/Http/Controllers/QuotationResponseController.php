<?php

namespace App\Http\Controllers;
use App\Models\QuotationResponse;

use Illuminate\Http\Request;

class QuotationResponseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'vendor_id' => 'required|exists:users,id',
            'offered_price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $quotationResponse = QuotationResponse::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Quotation response successfully created',
            'data' => $quotationResponse,
        ], 201);
    }
}
