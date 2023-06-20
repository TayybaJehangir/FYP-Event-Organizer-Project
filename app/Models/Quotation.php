<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'number_of_guests',
        'event_date',
        'budget_range',
        'event_details',
        'user_name',
        'user_contact_number',
        'vendor_id',
        'user_id',
        'business_id'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function quotationResponse()
    {
        return $this->hasOne(QuotationResponse::class);
    }
}
