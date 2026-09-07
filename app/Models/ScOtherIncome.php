<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScOtherIncome extends Model
{
    protected $table = 'sc_other_incomes';
    protected $fillable = ['senior_citizen_id','other_income_id','amount','is_active','created_by','updated_by'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function otherIncome() { return $this->belongsTo(OtherIncome::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
