<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScAuthorizedRepresentative extends Model
{
    protected $table = 'sc_authorized_representatives';
    protected $fillable = ['senior_citizen_id','last_name','first_name','middle_name','suffix','date_of_birth','civil_status_id','phone','is_active','created_by','updated_by'];
    protected $casts = ['date_of_birth' => 'date'];
    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function civilStatus() { return $this->belongsTo(CivilStatus::class); }
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
