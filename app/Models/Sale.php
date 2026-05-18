<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'business_id',
        'staff_id',
        'item_id',
        'quantity',
        'unit_price',
        'total',
        'sale_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sale_date'  => 'date',
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
