<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutCutoff extends Model
{
    protected $fillable = [
        'funding_id',
        'from_date',
        'to_date',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function funding()
    {
        return $this->belongsTo(Funding::class, 'funding_id');
    }

    public function transactions()
    {
        return $this->hasMany(PayoutTransaction::class, 'payout_cutoff_id');
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
