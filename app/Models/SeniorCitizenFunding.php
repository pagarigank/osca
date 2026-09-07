<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeniorCitizenFunding extends Model
{
    protected $fillable = [
        'senior_citizen_id', 'pension_source_id', 'monthly_pension',
        'funding_id', 'payroll_provider_id',
        'regular_income_type', 'regular_income_amount',
        'other_income_id', 'other_income_amount',
        'is_active', 'created_by', 'updated_by',
    ];

    public function seniorCitizen() { return $this->belongsTo(\App\Models\SeniorCitizen::class); }
    public function pensionSource() { return $this->belongsTo(\App\Models\PensionSource::class, 'pension_source_id'); }
    public function funding() { return $this->belongsTo(\App\Models\Funding::class, 'funding_id'); }
    public function payrollProvider() { return $this->belongsTo(\App\Models\PayrollProvider::class, 'payroll_provider_id'); }
    public function otherIncome() { return $this->belongsTo(\App\Models\OtherIncome::class, 'other_income_id'); }

    protected static function booted()
    {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
