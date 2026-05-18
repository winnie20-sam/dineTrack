<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'business_id',
        'auto_number',
        'code',
        'name',
        'category',
        'price',
        'status_id',
        'available',
        'created_by',
        'updated_by'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
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
