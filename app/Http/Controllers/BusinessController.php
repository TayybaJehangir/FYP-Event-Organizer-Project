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

    public function getVendorBusinesses($vendor_id)
    {
        $businesses = Business::where('vendor_id', $vendor_id)->get();

        return response()->json([
            'status' => 'Success',
            'data' => $businesses
        ], 200);
    }

    public function show(Business $business)
    {
        return response()->json([
            'status' => 'Success',
            'data' => $business
        ], 200);
    }

    public function update(Request $request, Business $business)
    {
        $request->validate([
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

        $business->update($request->all());

        return response()->json([
            'status' => 'Success',
            'message' => 'Business updated successfully',
            'data' => $business
        ], 200);
    }
}
