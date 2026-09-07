<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactorConsideration extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function scoringDetails()
    {
        return $this->hasMany(ConsiderationScoringDetail::class, 'factor_consideration_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });
    }
}
