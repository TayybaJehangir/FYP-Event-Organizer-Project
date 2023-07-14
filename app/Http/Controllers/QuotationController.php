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
        $quotations = Quotation::where('vendor_id', $vendor_id)->with(['business', 'quotationResponse'])->get();

        $quotations = $quotations->map(function ($quotation) {
            $quotation->cover_photo = $quotation->business->cover_photo;
            $quotation->business_name = $quotation->business->business_name;
            unset($quotation->business);
            return $quotation;
        });

        return response()->json([
            'status' => 'success',
            'data' => $quotations
        ]);
    }

    public function getUserQuotations(Request $request, $user_id)
    {
        $quotations = Quotation::where('user_id', $user_id)
            ->with(['business', 'quotationResponse'])
            ->get();

        $quotations = $quotations->map(function ($quotation) {
            $quotation->cover_photo = $quotation->business->cover_photo;
            $quotation->business_name = $quotation->business->business_name;
            unset($quotation->business);
            return $quotation;
        });

        return response()->json([
            'status' => 'success',
            'data' => $quotations
        ]);
    }


    public function show($id)
    {
        $quotation = Quotation::with(['business', 'quotationResponse'])->find($id);

        if (!$quotation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quotation not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $quotation,
        ], 200);
    }

    public function updateQuotationStatus(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->status = $request->input('status');
        $quotation->save();

        return response()->json([
            'status' => 'success',
            'data' => $quotation
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Resolved,Not Satisfied',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->status = $request->status;
        $quotation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Quotation status updated successfully',
            'data' => $quotation,
        ], 200);
    }

    public function getUserQuotationsByBusiness($user_id, $business_id)
    {
        $quotations = Quotation::where('user_id', $user_id)
            ->where('business_id', $business_id)
            ->with('business', 'quotationResponse')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $quotations
        ]);
    }
}
