<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScPension extends Model
{
    protected $table = 'sc_pensions';
    protected $fillable = ['senior_citizen_id','pension_source_id','amount','is_active','created_by','updated_by'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function pensionSource() { return $this->belongsTo(PensionSource::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
