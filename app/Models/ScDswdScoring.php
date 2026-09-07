<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScDswdScoring extends Model
{
    protected $table = 'sc_dswd_scorings';
    protected $fillable = [
        'senior_citizen_id',
        'consideration_scoring_detail_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function seniorCitizen()
    {
        return $this->belongsTo(SeniorCitizen::class);
    }

    public function scoringDetail()
    {
        return $this->belongsTo(ConsiderationScoringDetail::class, 'consideration_scoring_detail_id');
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
