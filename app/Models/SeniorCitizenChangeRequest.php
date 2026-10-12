<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeniorCitizenChangeRequest extends Model
{
    protected $fillable = [
        'senior_citizen_id',
        'requested_by',
        'changes',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function seniorCitizen()
    {
        return $this->belongsTo(SeniorCitizen::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }}
