<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScFrailty extends Model
{
    protected $table = 'sc_frailties';
    protected $fillable = ['senior_citizen_id','frailty_id','is_active','created_by','updated_by'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function frailty() { return $this->belongsTo(Frailty::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
