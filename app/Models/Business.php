<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'auto_number',
        'code',
        'name',
        'email',
        'phone',
        'status_id',
        'created_by',
        'updated_by'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
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
