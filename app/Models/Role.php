<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function businesses()
    {
        return $this->belongsToMany(Business::class)->withPivot('role_id')->withTimestamps();
    }
}
