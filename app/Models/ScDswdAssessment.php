<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScDswdAssessment extends Model
{
    protected $table = 'sc_dswd_assessments';
    protected $fillable = [
        'senior_citizen_id', 'ambulatory', 'place_of_living_id', 'living_condition_id',
        'assessment', 'recommendation',
        'encoded_by', 'encoded_at', 'validated_by', 'validated_at',
        'is_active', 'created_by', 'updated_by',
    ];
    protected $casts = ['ambulatory' => 'boolean', 'encoded_at' => 'datetime', 'validated_at' => 'datetime'];

    public function seniorCitizen() { return $this->belongsTo(SeniorCitizen::class); }
    public function placeOfLiving() { return $this->belongsTo(PlaceOfLiving::class); }
    public function livingCondition() { return $this->belongsTo(LivingCondition::class); }
    public function encodedBy() { return $this->belongsTo(User::class, 'encoded_by'); }
    public function validatedBy() { return $this->belongsTo(User::class, 'validated_by'); }

    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
