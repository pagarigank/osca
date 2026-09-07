<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsiderationScoringDetail extends Model
{
    protected $fillable = [
        'factor_consideration_id',
        'particular',
        'guide_points',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function factorConsideration()
    {
        return $this->belongsTo(FactorConsideration::class, 'factor_consideration_id');
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
