<?php

namespace App\Http\Controllers;
use App\Models\Quotation;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        $quotation = Quotation::create($request->all());

        return response()->json([
            'status' => 'Success',
            'message' => 'Quotation request created successfully',
            'data' => $quotation
        ], 201);
    }
}
