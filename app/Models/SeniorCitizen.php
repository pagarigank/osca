<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeniorCitizen extends Model
{
    protected $fillable = [
        'id_number', 'ncsc_rrn', 'national_id_no', 'voter_id_no', 'precinct_no', 'precinct_place', 'new_id_number',
        'last_name', 'first_name', 'middle_name', 'suffix',
        'date_of_birth', 'country_of_birth', 'civil_status_id', 'gender_id', 'nationality_id',
        'citizenship', 'dual_citizen_document', 'blood_type', 'affiliation',
        'mobile_no', 'email', 'messenger_account',
        'house_no', 'street', 'purok', 'purok_name', 'barangay_id', 'purok_id', 'city_municipality', 'province',
        'living_condition_id', 'place_of_living_id',
        'emergency_contact', 'emergency_contact_no',
        'transferred_to_lgu', 'transferred_first_name', 'transferred_suffix', 'transferred_relation',
        'ncsc_validation_date', 'sc_category', 'ncsc_remarks',
        'ncsc_address_abroad_house_no', 'ncsc_address_abroad_street', 'ncsc_address_abroad_county', 'ncsc_address_abroad_state', 'ncsc_address_abroad_country', 'ncsc_address_abroad_zip',
        'ncsc_primary_beneficiary_name', 'ncsc_primary_beneficiary_rel', 'ncsc_secondary_beneficiary_name', 'ncsc_secondary_beneficiary_rel',
        'cswdo_assessment_date', 'cswdo_total_score', 'cswdo_remarks',
        'effective_date', 'status', 'inactive_reason', 'is_active',
        'qr_token', 'signature', 'id_picture', 'thumbmark',
        'created_by', 'updated_by', 'pending_updates'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'effective_date' => 'date',
        'ncsc_validation_date' => 'date',
        'cswdo_assessment_date' => 'date',
        'pending_updates' => 'array',
    ];

    public function civilStatus() { return $this->belongsTo(\App\Models\CivilStatus::class, 'civil_status_id'); }
    public function gender() { return $this->belongsTo(\App\Models\Gender::class, 'gender_id'); }
    public function nationality() { return $this->belongsTo(\App\Models\Nationality::class, 'nationality_id'); }
    public function barangay() { return $this->belongsTo(\App\Models\Barangay::class, 'barangay_id'); }
    public function purok() { return $this->belongsTo(\App\Models\Purok::class, 'purok_id'); }
    public function purokLeader() { return $this->belongsTo(\App\Models\PurokLeader::class, 'purok_leader_id'); }
    public function placeOfLiving() { return $this->belongsTo(\App\Models\PlaceOfLiving::class, 'place_of_living_id'); }
    public function livingChildren() { return $this->hasMany(\App\Models\SeniorCitizenLivingChild::class, 'senior_citizen_id'); }
    public function funding() { return $this->hasOne(\App\Models\SeniorCitizenFunding::class, 'senior_citizen_id'); }
    // DSWD relationships
    public function authorizedRepresentatives() { return $this->hasMany(\App\Models\ScAuthorizedRepresentative::class, 'senior_citizen_id'); }
    public function caregivers() { return $this->hasMany(\App\Models\ScCaregiver::class, 'senior_citizen_id'); }
    public function preExistingConditions() { return $this->hasMany(\App\Models\ScPreExistingCondition::class, 'senior_citizen_id'); }
    public function disabilityDiagnoses() { return $this->hasMany(\App\Models\ScDisabilityDiagnosis::class, 'senior_citizen_id'); }
    public function frailties() { return $this->hasMany(\App\Models\ScFrailty::class, 'senior_citizen_id'); }
    public function pensions() { return $this->hasMany(\App\Models\ScPension::class, 'senior_citizen_id'); }
    public function regularIncomes() { return $this->hasMany(\App\Models\ScRegularIncome::class, 'senior_citizen_id'); }
    public function otherIncomes() { return $this->hasMany(\App\Models\ScOtherIncome::class, 'senior_citizen_id'); }
    public function dswdAssessment() { return $this->hasOne(\App\Models\ScDswdAssessment::class, 'senior_citizen_id'); }
    public function dswdScorings() { return $this->hasMany(\App\Models\ScDswdScoring::class, 'senior_citizen_id'); }
    public function attachments() { return $this->hasMany(\App\Models\SeniorCitizenAttachment::class, 'senior_citizen_id'); }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1;
            // Generate a secure, immutable UUID token for QR code verification
            // This is set ONCE and never changed so printed IDs remain valid
            if (empty($model->qr_token)) {
                $model->qr_token = Str::uuid()->toString();
            }
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });
    }
}