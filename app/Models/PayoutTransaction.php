<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutTransaction extends Model
{
    protected $fillable = [
        'payout_cutoff_id',
        'senior_citizen_funding_id',
        'amount',
        'received_date',
        'proof_path',
        'is_received',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'received_date' => 'date',
        'is_received' => 'boolean',
    ];

    public function payoutCutoff()
    {
        return $this->belongsTo(PayoutCutoff::class, 'payout_cutoff_id');
    }

    public function seniorCitizenFunding()
    {
        return $this->belongsTo(SeniorCitizenFunding::class, 'senior_citizen_funding_id');
    }

    public function seniorCitizen()
    {
        return $this->belongsToThrough(SeniorCitizen::class, SeniorCitizenFunding::class, 'senior_citizen_id');
    }

    public function proofUrl()
    {
        if (!$this->proof_path) {
            return null;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->proof_path);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
