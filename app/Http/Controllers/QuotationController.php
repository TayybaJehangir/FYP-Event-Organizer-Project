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

    public function checkUserQuotation(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required',
            'business_id' => 'required',
        ]);

        $userId = $request->user_id;
        $businessId = $request->business_id;

        $quotations = Quotation::where('user_id', $userId)
            ->where('business_id', $businessId)
            ->get();

        $count = $quotations->count();

        return response()->json([
            'status' => $count > 0,
            'count' => $count
        ]);
    }

    public function getVendorQuotations(Request $request, $vendor_id)
    {
        $quotations = Quotation::where('vendor_id', $vendor_id)->with('business')->get();

        $quotations = $quotations->map(function ($quotation) {
            $quotation->cover_photo = $quotation->business->cover_photo;
            unset($quotation->business);
            return $quotation;
        });

        return response()->json([
            'status' => 'success',
            'data' => $quotations
        ]);
    }
}
