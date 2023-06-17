<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'business_name', 'business_type', 'business_mobile_number',
        'business_address', 'business_details', 'people_capacity_min',
        'people_capacity_max', 'start_time', 'end_time', 'price_per_person',
        'services_and_amenities', 'cover_photo', 'images'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
