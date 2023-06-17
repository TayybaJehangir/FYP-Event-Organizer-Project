<?php

namespace App\Http\Controllers;
use App\Models\Business;

use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'business_name' => 'required',
            'business_type' => 'required',
            'business_mobile_number' => 'required',
            'business_address' => 'required',
            'business_details' => 'required',
            'people_capacity_min' => 'required|integer',
            'people_capacity_max' => 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required',
            'price_per_person' => 'required|numeric',
            'services_and_amenities' => 'required',
        ]);

        $business = Business::create($request->all());

        return response()->json([
            'status' => 'Success',
            'message' => 'Business created successfully',
            'data' => $business
        ], 201);
    }
}
