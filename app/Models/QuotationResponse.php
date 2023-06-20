<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'vendor_id',
        'offered_price',
        'description'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
