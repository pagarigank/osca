<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScPreExistingCondition extends Model
{
    protected $table = 'sc_pre_existing_conditions';
    protected $fillable = ['senior_citizen_id','diagnosis_id','is_active','created_by','updated_by'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function diagnosis() { return $this->belongsTo(Diagnosis::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
