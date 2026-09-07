<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScCaregiver extends Model
{
    protected $table = 'sc_caregivers';
    protected $fillable = ['senior_citizen_id','last_name','first_name','middle_name','suffix','relationship','phone','is_active','created_by','updated_by'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
