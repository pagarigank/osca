<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeniorCitizenLivingChild extends Model
{
    protected $fillable = ['senior_citizen_id', 'last_name', 'first_name', 'mobile_no', 'is_active', 'created_by', 'updated_by'];

    public function seniorCitizen() { return $this->belongsTo(\App\Models\SeniorCitizen::class); }

    protected static function booted()
    {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
