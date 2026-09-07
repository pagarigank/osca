<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\Province;
use App\Models\SeniorCitizen;
use App\Models\SeniorCitizenLivingChild;
use App\Models\SeniorCitizenFunding;
use App\Models\CivilStatus;
use App\Models\Gender;
use App\Models\Nationality;
use App\Models\Barangay;
use App\Models\LivingCondition;
use App\Models\PlaceOfLiving;
use App\Models\PensionSource;
use App\Models\Funding;
use App\Models\PayrollProvider;
use App\Models\OtherIncome;
use App\Models\Diagnosis;
use App\Models\Frailty;
use App\Models\RegularIncome;
use App\Models\QuadParameter;
use App\Models\Purok;
use App\Models\ScAuthorizedRepresentative;
use App\Models\ScCaregiver;
use App\Models\ScPreExistingCondition;
use App\Models\ScDisabilityDiagnosis;
use App\Models\ScFrailty;
use App\Models\ScPension;
use App\Models\ScRegularIncome;
use App\Models\ScOtherIncome;
use App\Models\ScDswdAssessment;
use App\Models\FactorConsideration;

#[Layout('layouts.app')]
class PersonalInformation extends Component
{
    use WithFileUploads;

    // --- UI State ---
    public $activeTab = 'personal';
    public $isFormOpen = false;
    public $isEditMode = false;
    public $isViewMode = false;
    public $pending_updates = [];
    public $seniorCitizenId = null;
    public $search = '';
    public $activeSearchTerm = ''; // For on-demand search

    // --- Lookup Lists ---
    public $civilStatuses = [];
    public $genders = [];
    public $nationalities = [];
    public $barangays = [];
    public $puroks = [];
    public $livingConditions = [];
    public $placesOfLiving = [];
    public $pensionSources = [];
    public $fundings = [];
    public $payrollProviders = [];
    public $otherIncomes = [];
    public $diagnoses = [];
    public $frailties = [];
    public $regularIncomes = [];
    public $factorConsiderations = [];
    public $quad2Options = [];
    public $quad3Options = [];
    public $quad4Options = [];

    public $provinces = [];
    public $availableCities = [];

    // --- Personal Information Fields ---
    public $id_number = '';
    public $ncsc_rrn = '';
    public $national_id_no = '';
    public $voter_id_no = '';
    public $precinct_no = '';
    public $precinct_place = '';
    public $new_id_number = '';
    public $last_name = '';
    public $first_name = '';
    public $middle_name = '';
    public $suffix = '';
    public $date_of_birth = '';
    public $country_of_birth = '';
    public $civil_status_id = '';
    public $gender_id = '';
    public $nationality_id = '';
    public $citizenship = '';
    public $dual_citizen_document = '';
    public $blood_type = '';
    public $affiliation = '';
    public $mobile_no = '';
    public $email = '';
    public $messenger_account = '';
    public $house_no = '';
    public $street = '';
    public $purok = '';
    public $purok_name = '';
    public $purok_code = '';
    public $purok_description = '';
    public $purok_id = '';
    public $barangay_id = '';
    public $city_municipality = '';
    public $province = '';
    public $living_condition_id = '';
    public $place_of_living_id = '';
    public $emergency_contact = '';
    public $emergency_contact_no = '';
    public $transferred_to_lgu = '';
    public $transferred_first_name = '';
    public $transferred_suffix = '';
    public $transferred_relation = '';
    public $effective_date = '';
    public $status = 'ACTIVE';
    public $inactive_reason = '';
    public $qr_token = '';

    // --- Media / Uploads ---
    public $signature_upload;
    public $id_picture_upload;
    public $thumbmark_upload;
    public $webcam_image_base64;
    public $existing_signature;
    public $existing_id_picture;
    public $existing_thumbmark;
    public $attachment_uploads = [];
    public $existing_attachments = [];

    // --- NCSC Tab ---
    public $ncsc_validation_date = '';
    public $sc_category = '';
    public $ncsc_remarks = '';
    public $ncsc_address_abroad_house_no = '';
    public $ncsc_address_abroad_street = '';
    public $ncsc_address_abroad_county = '';
    public $ncsc_address_abroad_state = '';
    public $ncsc_address_abroad_country = '';
    public $ncsc_address_abroad_zip = '';
    public $ncsc_primary_beneficiary_name = '';
    public $ncsc_primary_beneficiary_rel = '';
    public $ncsc_secondary_beneficiary_name = '';
    public $ncsc_secondary_beneficiary_rel = '';

    // --- CSWDO Tab ---
    public $cswdo_assessment_date = '';
    public $cswdo_total_score = '';
    public $cswdo_remarks = '';

    // --- DSWD Tab — Repeatable rows ---
    public $authorizedReps = [];   // max 3: [last_name,first_name,middle_name,suffix,date_of_birth,civil_status_id,phone]
    public $caregivers = [];        // [last_name,first_name,middle_name,suffix,relationship,phone]
    public $preConditions = [];    // max 5: [diagnosis_id]
    public $disabilityDiags = [];  // max 5: [diagnosis_id]
    public $frailtyRows = [];      // max 3: [frailty_id]
    public $pensionRows = [];      // max 2: [pension_source_id, amount]
    public $regularIncomeRows = [];// max 2: [regular_income_id, amount]
    public $otherIncomeRows = [];  // max 2: [other_income_id, amount]
    // --- DSWD Tab — Single fields ---
    public $ambulatory = '';
    public $dswd_place_of_living_id = '';
    public $dswd_living_condition_id = '';
    // Factor Considerations Datatable Selection
    public $selectedScorings = [];
    public $totalGuidePoints = 0;

    public $dswd_assessment = '';
    public $recommendation = '';
    public $encoded_by = '';
    public $encoded_at = '';
    public $validated_by = '';
    public $validated_at = '';

    // --- Funding Tab ---
    public $pension_source_id = '';
    public $monthly_pension = '';
    public $funding_id = '';
    public $payroll_provider_id = '';
    public $regular_income_type = '';
    public $regular_income_amount = '';
    public $other_income_id = '';
    public $other_income_amount = '';

    // --- Living Children Tab ---
    public $livingChildren = [];

    public function mount()
    {
        $this->loadLookups();
        $this->addChildRow();
        $this->initDswdRows();
    }

    public function updatedBarangayId($value)
    {
        if ($value) {
            $this->puroks = Purok::where('barangay_id', $value)->where('is_active', true)->orderBy('name')->get();
        } else {
            $this->puroks = Purok::where('is_active', true)->get();
        }
        $this->purok_id = '';
        $this->purok = '';
        $this->purok_name = '';
        $this->purok_code = '';
        $this->purok_description = '';
    }

    // When a Purok is selected from the CRUD-driven dropdown, auto-fill the
    // legacy free-text purok / purok_name fields so saved data stays consistent.
    public function updatedPurokId($value)
    {
        if ($value) {
            $purok = Purok::find($value);
            $this->purok = $purok?->name ?? '';
            $this->purok_name = $purok?->name ?? '';
            $this->purok_code = $purok?->purok_code ?? '';
            $this->purok_description = $purok?->description ?? '';
        } else {
            $this->purok = '';
            $this->purok_name = '';
            $this->purok_code = '';
            $this->purok_description = '';
        }
    }

    private function initDswdRows()
    {
        $this->authorizedReps = [['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','date_of_birth'=>'','civil_status_id'=>'','phone'=>'']];
        $this->caregivers     = [['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','relationship'=>'','phone'=>'']];
        $this->preConditions  = [['diagnosis_id'=>'']];
        $this->disabilityDiags= [['diagnosis_id'=>'']];
        $this->frailtyRows    = [['frailty_id'=>'']];
        $this->pensionRows    = [['pension_source_id'=>'','amount'=>'']];
        $this->regularIncomeRows=[['regular_income_id'=>'','amount'=>'']];
        $this->otherIncomeRows= [['other_income_id'=>'','amount'=>'']];
        $this->selectedScorings= [['factor_consideration_id' => '', 'consideration_scoring_detail_id' => '', 'points' => 0]];
    }


    public function loadLookups()
    {
        $this->civilStatuses    = CivilStatus::where('is_active', true)->get();
        $this->genders          = Gender::where('is_active', true)->get();
        $this->nationalities    = Nationality::where('is_active', true)->get();
        $this->barangays        = Barangay::where('is_active', true)->get();
        $this->puroks           = Purok::where('is_active', true)->get();
        $this->livingConditions = LivingCondition::where('is_active', true)->get();
        $this->placesOfLiving   = PlaceOfLiving::where('is_active', true)->get();
        $this->pensionSources   = PensionSource::where('is_active', true)->get();
        $this->fundings         = Funding::where('is_active', true)->get();
        $this->payrollProviders = PayrollProvider::where('is_active', true)->get();
        $this->otherIncomes     = OtherIncome::where('is_active', true)->get();
        $this->diagnoses        = Diagnosis::where('is_active', true)->orderBy('generic_name')->get();
        $this->frailties        = Frailty::where('is_active', true)->get();
        $this->regularIncomes   = RegularIncome::where('is_active', true)->get();
        $this->factorConsiderations = FactorConsideration::with(['scoringDetails' => fn($q) => $q->where('is_active', true)])->where('is_active', true)->get();
        $this->provinces        = Province::where('is_active', true)->orderBy('name')->get();
        $this->quad2Options     = QuadParameter::quad2()->where('is_active', true)->get();
        $this->quad3Options     = QuadParameter::quad3()->where('is_active', true)->get();
        $this->quad4Options     = QuadParameter::quad4()->where('is_active', true)->get();
    }


    // --- DSWD Row Helpers ---
    public function addRepRow()    { if(count($this->authorizedReps)<3)  $this->authorizedReps[]=  ['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','date_of_birth'=>'','civil_status_id'=>'','phone'=>'']; }
    public function removeRepRow($i) { array_splice($this->authorizedReps,$i,1); }
    public function addCaregiverRow()    { $this->caregivers[]=['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','relationship'=>'','phone'=>'']; }
    public function removeCaregiverRow($i) { array_splice($this->caregivers,$i,1); }
    public function addPreCondRow()  { if(count($this->preConditions)<5)  $this->preConditions[]=['diagnosis_id'=>'']; }
    public function removePreCondRow($i) { array_splice($this->preConditions,$i,1); }
    public function addDisabilityRow() { if(count($this->disabilityDiags)<5) $this->disabilityDiags[]=['diagnosis_id'=>'']; }
    public function removeDisabilityRow($i) { array_splice($this->disabilityDiags,$i,1); }
    public function addFrailtyRow()  { if(count($this->frailtyRows)<3)    $this->frailtyRows[]=['frailty_id'=>'']; }
    public function removeFrailtyRow($i) { array_splice($this->frailtyRows,$i,1); }
    public function addPensionRow()  { if(count($this->pensionRows)<2)    $this->pensionRows[]=['pension_source_id'=>'','amount'=>'']; }
    public function removePensionRow($i) { array_splice($this->pensionRows,$i,1); }
    public function addRegIncomeRow(){ if(count($this->regularIncomeRows)<2) $this->regularIncomeRows[]=['regular_income_id'=>'','amount'=>'']; }
    public function removeRegIncomeRow($i) { array_splice($this->regularIncomeRows,$i,1); }
    public function addOtherIncRow() { if(count($this->otherIncomeRows)<2) $this->otherIncomeRows[]=['other_income_id'=>'','amount'=>'']; }
    public function removeOtherIncRow($i) { array_splice($this->otherIncomeRows,$i,1); }

    public function addScoringRow() { $this->selectedScorings[] = ['factor_consideration_id' => '', 'consideration_scoring_detail_id' => '', 'points' => 0]; }
    public function removeScoringRow($i) { array_splice($this->selectedScorings, $i, 1); $this->recalcTotalGuidePoints(); }

    public function updatedSelectedScorings($value, $key)
    {
        if (str_ends_with($key, '.factor_consideration_id')) {
            $index = explode('.', $key)[0];
            $this->selectedScorings[$index]['consideration_scoring_detail_id'] = '';
            $this->selectedScorings[$index]['points'] = 0;
        } elseif (str_ends_with($key, '.consideration_scoring_detail_id')) {
            $index = explode('.', $key)[0];
            $detailId = $this->selectedScorings[$index]['consideration_scoring_detail_id'];
            
            $points = 0;
            foreach ($this->factorConsiderations as $fc) {
                foreach ($fc->scoringDetails as $detail) {
                    if ($detail->id == $detailId) {
                        $points = $detail->guide_points;
                        break 2;
                    }
                }
            }
            $this->selectedScorings[$index]['points'] = $points;
        }
        $this->recalcTotalGuidePoints();
    }


    private function recalcTotalGuidePoints()
    {
        $points = 0;
        foreach ($this->selectedScorings as $row) {
            $points += (float)($row['points'] ?? 0);
        }
        $this->totalGuidePoints = $points;
    }


    public function openCreateForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
        $this->isEditMode = false;
        $this->activeTab = 'personal';
        $this->livingChildren = [['last_name' => '', 'first_name' => '', 'mobile_no' => '']];
    }


    public function openEditForm($id)
    {
        $this->isEditMode = true;
        $this->isViewMode = false;
        $this->seniorCitizenId = $id;
        $this->fillFormFromModel();
        $this->isFormOpen = true;
        $this->activeTab = 'personal';
    }


    public function openViewMode($id)
    {
        $this->isEditMode = true; // Needs to be true to show Audit Info and Print button
        $this->isViewMode = true;
        $this->seniorCitizenId = $id;
        $this->fillFormFromModel();
        $this->isFormOpen = true;
        $this->activeTab = 'personal';
    }



    private function fillFormFromModel()
    {
        $sc = SeniorCitizen::with([
            'livingChildren','funding','dswdAssessment',
            'authorizedRepresentatives','caregivers',
            'preExistingConditions','disabilityDiagnoses',
            'frailties','pensions','regularIncomes','otherIncomes','dswdScorings',
            'attachments'
        ])->findOrFail($this->seniorCitizenId);
        $this->id_number = $sc->id_number; $this->ncsc_rrn = $sc->ncsc_rrn;
        $this->national_id_no = $sc->national_id_no; $this->voter_id_no = $sc->voter_id_no;
        $this->precinct_no = $sc->precinct_no; $this->precinct_place = $sc->precinct_place;
        $this->new_id_number = $sc->new_id_number; $this->qr_token = $sc->qr_token;
        $this->last_name = $sc->last_name; $this->first_name = $sc->first_name;
        $this->middle_name = $sc->middle_name; $this->suffix = $sc->suffix;
        $this->date_of_birth = $sc->date_of_birth ? $sc->date_of_birth->format('Y-m-d') : '';
        $this->pending_updates = $sc->pending_updates ?? [];
        $this->country_of_birth = $sc->country_of_birth;
        $this->civil_status_id = $sc->civil_status_id; $this->gender_id = $sc->gender_id;
        $this->nationality_id = $sc->nationality_id; $this->citizenship = $sc->citizenship;
        $this->dual_citizen_document = $sc->dual_citizen_document;
        $this->blood_type = $sc->blood_type; $this->affiliation = $sc->affiliation;
        $this->mobile_no = $sc->mobile_no; $this->email = $sc->email;
        $this->messenger_account = $sc->messenger_account;
        $this->house_no = $sc->house_no; $this->street = $sc->street;
        $this->purok = $sc->purok; $this->purok_name = $sc->purok_name;
        $this->purok_id = $sc->purok_id ?? "";
        // Populate the read-only Purok Code / Description from the linked Purok master record.
        if ($sc->purok_id) {
            $purok = Purok::find($sc->purok_id);
            $this->purok_code = $purok?->purok_code ?? '';
            $this->purok_description = $purok?->description ?? '';
        } else {
            $this->purok_code = '';
            $this->purok_description = '';
        }
        // Refresh the Purok dropdown options so the saved selection is visible.
        if ($sc->barangay_id) {
            $this->puroks = Purok::where('barangay_id', $sc->barangay_id)->where('is_active', true)->orderBy('name')->get();
        } else {
            $this->puroks = Purok::where('is_active', true)->orderBy('name')->get();
        }
        $this->barangay_id = $sc->barangay_id;
        $this->province = $sc->province;
        $prov = Province::where('name', $sc->province)->first();
        if ($prov) {
            $this->availableCities = City::where('province_id', $prov->id)->where('is_active', true)->orderBy('name')->get();
        } else {
            $this->availableCities = [];
        }
        $this->city_municipality = $sc->city_municipality;
        $this->living_condition_id = $sc->living_condition_id;
        $this->place_of_living_id = $sc->place_of_living_id;
        $this->emergency_contact = $sc->emergency_contact;
        $this->emergency_contact_no = $sc->emergency_contact_no;
        $this->transferred_to_lgu = $sc->transferred_to_lgu;
        $this->transferred_first_name = $sc->transferred_first_name;
        $this->transferred_suffix = $sc->transferred_suffix;
        $this->transferred_relation = $sc->transferred_relation;
        $this->effective_date = $sc->effective_date ? $sc->effective_date->format('Y-m-d') : '';
        $this->status = $sc->status; $this->inactive_reason = $sc->inactive_reason;
        
        $this->existing_signature = $sc->signature;
        $this->existing_id_picture = $sc->id_picture;
        $this->existing_thumbmark = $sc->thumbmark;

        $this->ncsc_validation_date = $sc->ncsc_validation_date ? $sc->ncsc_validation_date->format('Y-m-d') : '';
        $this->sc_category = $sc->sc_category; $this->ncsc_remarks = $sc->ncsc_remarks;
        $this->ncsc_address_abroad_house_no = $sc->ncsc_address_abroad_house_no;
        $this->ncsc_address_abroad_street = $sc->ncsc_address_abroad_street;
        $this->ncsc_address_abroad_county = $sc->ncsc_address_abroad_county;
        $this->ncsc_address_abroad_state = $sc->ncsc_address_abroad_state;
        $this->ncsc_address_abroad_country = $sc->ncsc_address_abroad_country;
        $this->ncsc_address_abroad_zip = $sc->ncsc_address_abroad_zip;
        $this->ncsc_primary_beneficiary_name = $sc->ncsc_primary_beneficiary_name;
        $this->ncsc_primary_beneficiary_rel = $sc->ncsc_primary_beneficiary_rel;
        $this->ncsc_secondary_beneficiary_name = $sc->ncsc_secondary_beneficiary_name;
        $this->ncsc_secondary_beneficiary_rel = $sc->ncsc_secondary_beneficiary_rel;
        
        $this->cswdo_assessment_date = $sc->cswdo_assessment_date ? $sc->cswdo_assessment_date->format('Y-m-d') : '';
        $this->cswdo_total_score = $sc->cswdo_total_score;
        $this->cswdo_remarks = $sc->cswdo_remarks;
        // Funding
        if ($sc->funding) {
            $f = $sc->funding;
            $this->pension_source_id = $f->pension_source_id; $this->monthly_pension = $f->monthly_pension;
            $this->funding_id = $f->funding_id; $this->payroll_provider_id = $f->payroll_provider_id;
            $this->regular_income_type = $f->regular_income_type; $this->regular_income_amount = $f->regular_income_amount;
            $this->other_income_id = $f->other_income_id; $this->other_income_amount = $f->other_income_amount;
        }
        // Living Children
        $this->livingChildren = $sc->livingChildren->map(fn($c) => ['id'=>$c->id,'last_name'=>$c->last_name,'first_name'=>$c->first_name,'mobile_no'=>$c->mobile_no])->toArray();
        if (empty($this->livingChildren)) $this->livingChildren = [['last_name'=>'','first_name'=>'','mobile_no'=>'']];
        // DSWD — repeatable rows
        $this->authorizedReps = $sc->authorizedRepresentatives->map(fn($r)=>['last_name'=>$r->last_name,'first_name'=>$r->first_name,'middle_name'=>$r->middle_name,'suffix'=>$r->suffix,'date_of_birth'=>$r->date_of_birth?$r->date_of_birth->format('Y-m-d'):'','civil_status_id'=>$r->civil_status_id,'phone'=>$r->phone])->toArray();
        if(empty($this->authorizedReps)) $this->authorizedReps=[['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','date_of_birth'=>'','civil_status_id'=>'','phone'=>'']];
        $this->caregivers = $sc->caregivers->map(fn($c)=>['last_name'=>$c->last_name,'first_name'=>$c->first_name,'middle_name'=>$c->middle_name,'suffix'=>$c->suffix,'relationship'=>$c->relationship,'phone'=>$c->phone])->toArray();
        if(empty($this->caregivers)) $this->caregivers=[['last_name'=>'','first_name'=>'','middle_name'=>'','suffix'=>'','relationship'=>'','phone'=>'']];
        $this->preConditions = $sc->preExistingConditions->map(fn($r)=>['diagnosis_id'=>$r->diagnosis_id])->toArray();
        if(empty($this->preConditions)) $this->preConditions=[['diagnosis_id'=>'']];
        $this->disabilityDiags = $sc->disabilityDiagnoses->map(fn($r)=>['diagnosis_id'=>$r->diagnosis_id])->toArray();
        if(empty($this->disabilityDiags)) $this->disabilityDiags=[['diagnosis_id'=>'']];
        $this->frailtyRows = $sc->frailties->map(fn($r)=>['frailty_id'=>$r->frailty_id])->toArray();
        if(empty($this->frailtyRows)) $this->frailtyRows=[['frailty_id'=>'']];
        $this->pensionRows = $sc->pensions->map(fn($r)=>['pension_source_id'=>$r->pension_source_id,'amount'=>$r->amount])->toArray();
        if(empty($this->pensionRows)) $this->pensionRows=[['pension_source_id'=>'','amount'=>'']];
        $this->regularIncomeRows = $sc->regularIncomes->map(fn($r)=>['regular_income_id'=>$r->regular_income_id,'amount'=>$r->amount])->toArray();
        if(empty($this->regularIncomeRows)) $this->regularIncomeRows=[['regular_income_id'=>'','amount'=>'']];
        $this->otherIncomeRows = $sc->otherIncomes->map(fn($r)=>['other_income_id'=>$r->other_income_id,'amount'=>$r->amount])->toArray();
        if(empty($this->otherIncomeRows)) $this->otherIncomeRows=[['other_income_id'=>'','amount'=>'']];
        // DSWD assessment & scorings
        $this->selectedScorings = $sc->dswdScorings->map(function ($scoring) {
            return [
                'factor_consideration_id' => $scoring->scoringDetail->factor_consideration_id ?? '',
                'consideration_scoring_detail_id' => $scoring->consideration_scoring_detail_id,
                'points' => $scoring->scoringDetail->guide_points ?? 0,
            ];
        })->toArray();
        if (empty($this->selectedScorings)) $this->selectedScorings = [['factor_consideration_id' => '', 'consideration_scoring_detail_id' => '', 'points' => 0]];
        $this->recalcTotalGuidePoints();

        if ($d = $sc->dswdAssessment) {
            $this->ambulatory = $d->ambulatory === null ? '' : ($d->ambulatory ? '1' : '0');
            $this->dswd_place_of_living_id = $d->place_of_living_id;
            $this->dswd_living_condition_id = $d->living_condition_id;
            $this->dswd_assessment = $d->assessment; $this->recommendation = $d->recommendation;
            $this->encoded_by = $d->encoded_by;        $this->encoded_at = $sc->dswdAssessment->encoded_at ?? '';
            $this->validated_by = $sc->dswdAssessment->validated_by ?? '';
            $this->validated_at = $sc->dswdAssessment->validated_at ?? '';

            $this->existing_attachments = $sc->attachments->toArray();
        }
    }


    public function addChildRow()
    {
        $this->livingChildren[] = ['last_name' => '', 'first_name' => '', 'mobile_no' => ''];
    }


    public function removeChildRow($index)
    {
        array_splice($this->livingChildren, $index, 1);
    }




    public function updatedProvince($value)
    {
        $prov = Province::where('name', $value)->first();
        if ($prov) {
            $this->availableCities = City::where('province_id', $prov->id)->where('is_active', true)->orderBy('name')->get();
            $this->city_municipality = $this->availableCities->first()->name ?? '';
        } else {
            $this->availableCities = [];
            $this->city_municipality = '';
        }
    }


    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->isEditMode = false;
        $this->isViewMode = false;
        $this->pending_updates = [];
        $this->resetForm();
    }


    public function approvePendingUpdates()
    {
        if (!$this->seniorCitizenId) return;
        $sc = SeniorCitizen::find($this->seniorCitizenId);
        if ($sc && !empty($sc->pending_updates)) {
            $updates = $sc->pending_updates;
            if (isset($updates['first_name'])) $sc->first_name = $updates['first_name'];
            if (isset($updates['last_name'])) $sc->last_name = $updates['last_name'];
            if (isset($updates['middle_name'])) $sc->middle_name = $updates['middle_name'];
            if (isset($updates['date_of_birth'])) $sc->date_of_birth = $updates['date_of_birth'];
            $sc->pending_updates = null;
            $sc->save();
            
            $this->pending_updates = [];
            $this->fillFormFromModel();
            $this->dispatch('notify', message: 'Pending changes approved successfully.');
        }
    }


    public function rejectPendingUpdates()
    {
        if (!$this->seniorCitizenId) return;
        $sc = SeniorCitizen::find($this->seniorCitizenId);
        if ($sc) {
            $sc->pending_updates = null;
            $sc->save();
            
            $this->pending_updates = [];
            $this->fillFormFromModel();
            $this->dispatch('notify', message: 'Pending changes rejected.');
        }
    }


    private function resetForm()
    {
        $fields = [
            'id_number','ncsc_rrn','national_id_no','voter_id_no','precinct_no','precinct_place','new_id_number',
            'last_name','first_name','middle_name','suffix','date_of_birth','country_of_birth',
            'civil_status_id','gender_id','nationality_id','citizenship','dual_citizen_document','blood_type','affiliation',
            'mobile_no','email','messenger_account','house_no','street','purok','purok_name',
            'barangay_id','city_municipality','province','living_condition_id','place_of_living_id',
            'emergency_contact','emergency_contact_no','transferred_to_lgu','transferred_first_name',
            'transferred_suffix','transferred_relation','effective_date','inactive_reason', 'qr_token',
            'ncsc_validation_date','sc_category','ncsc_remarks',
            'ncsc_address_abroad_house_no','ncsc_address_abroad_street','ncsc_address_abroad_county',
            'ncsc_address_abroad_state','ncsc_address_abroad_country','ncsc_address_abroad_zip',
            'ncsc_primary_beneficiary_name','ncsc_primary_beneficiary_rel',
            'ncsc_secondary_beneficiary_name','ncsc_secondary_beneficiary_rel',
            'cswdo_assessment_date','cswdo_total_score','cswdo_remarks',
            'pension_source_id','monthly_pension','funding_id','payroll_provider_id',
            'regular_income_type','regular_income_amount','other_income_id','other_income_amount',
            'ambulatory','dswd_place_of_living_id','dswd_living_condition_id',
            'dswd_assessment','recommendation','encoded_by','encoded_at','validated_by','validated_at',
        ];
        foreach ($fields as $f) { $this->$f = ''; }
        $this->totalGuidePoints = 0;
        $this->status = 'ACTIVE';
        $this->seniorCitizenId = null;
        $this->livingChildren = [['last_name'=>'','first_name'=>'','mobile_no'=>'']];
        $this->availableCities = [];
        
        $this->signature_upload = null;
        $this->id_picture_upload = null;
        $this->thumbmark_upload = null;
        $this->webcam_image_base64 = null;
        $this->existing_signature = null;
        $this->existing_id_picture = null;
        $this->existing_thumbmark = null;
        $this->attachment_uploads = [];
        $this->existing_attachments = [];
        
        $this->authorizedReps = [];
        $this->caregivers = [];
        $this->preConditions = [];
        $this->disabilityDiags = [];
        $this->frailtyRows = [];
        $this->pensionRows = [];
        $this->regularIncomeRows = [];
        $this->otherIncomeRows = [];
        $this->selectedScorings = [];
        $this->initDswdRows();
    }


    /**
     * Generate the next available M-XXXX ID number.
     * Must be called inside a DB::transaction with a table-level lock
     * to prevent race conditions when multiple users save simultaneously.
     */
    private function generateIdNumber(): string
    {
        // Lock all existing M-XXXX rows so no concurrent request can read the same max
        $last = SeniorCitizen::where('id_number', 'LIKE', 'M-%')
            ->lockForUpdate()
            ->get()
            ->map(function ($sc) {
                // Extract the numeric part after 'M-'
                $num = ltrim(substr($sc->id_number, 2), '0');
                return is_numeric($num) ? (int)$num : 0;
            })
            ->max() ?? 0;

        $next = $last + 1;
        return 'M-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }


    public function save()
    {
        $isNew = !$this->seniorCitizenId;
        $manualId = trim($this->id_number);

        // Validation rules differ for new vs edit
        $rules = [
            'last_name'          => 'required|string|max:100',
            'first_name'         => 'required|string|max:100',
            'date_of_birth'      => 'required|date',
            'gender_id'          => 'required|exists:genders,id',
            'signature_upload'   => 'nullable|image|max:2048',
            'id_picture_upload'  => 'nullable|image|max:4096',
            'thumbmark_upload'   => 'nullable|image|max:2048',
        ];

        if ($isNew) {
            // On create: id_number is optional — blank triggers auto-numbering
            // If user typed something, validate it is unique
            if ($manualId !== '') {
                $rules['id_number'] = 'string|max:50|unique:senior_citizens,id_number';
            }
        } else {
            // On edit: id_number is always required and unique (excluding self)
            $rules['id_number'] = 'required|string|max:50|unique:senior_citizens,id_number,' . $this->seniorCitizenId;
        }

        $this->validate($rules);

        $updateData = [
                'id_number' => $this->id_number, 'ncsc_rrn' => $this->ncsc_rrn,
                'national_id_no' => $this->national_id_no, 'voter_id_no' => $this->voter_id_no,
                'precinct_no' => $this->precinct_no, 'precinct_place' => $this->precinct_place,
                'new_id_number' => $this->new_id_number,
                'suffix' => $this->suffix,
                'country_of_birth' => $this->country_of_birth,
                'civil_status_id' => $this->civil_status_id ?: null,
                'gender_id' => $this->gender_id ?: null,
                'nationality_id' => $this->nationality_id ?: null,
                'citizenship' => $this->citizenship, 'dual_citizen_document' => $this->dual_citizen_document,
                'blood_type' => $this->blood_type, 'affiliation' => $this->affiliation,
                'mobile_no' => $this->mobile_no, 'email' => $this->email,
                'messenger_account' => $this->messenger_account,
                'house_no' => $this->house_no, 'street' => $this->street,
                'purok' => $this->purok, 'purok_name' => $this->purok_name,
                'barangay_id' => $this->barangay_id ?: null,
                'purok_id' => $this->purok_id ?: null,
                'city_municipality' => $this->city_municipality, 'province' => $this->province,
                'living_condition_id' => $this->living_condition_id ?: null,
                'place_of_living_id' => $this->place_of_living_id ?: null,
                'emergency_contact' => $this->emergency_contact,
                'emergency_contact_no' => $this->emergency_contact_no,
                'transferred_to_lgu' => $this->transferred_to_lgu,
                'transferred_first_name' => $this->transferred_first_name,
                'transferred_suffix' => $this->transferred_suffix,
                'transferred_relation' => $this->transferred_relation,
                'ncsc_validation_date' => $this->ncsc_validation_date ?: null,
                'sc_category' => $this->sc_category, 'ncsc_remarks' => $this->ncsc_remarks,
                'ncsc_address_abroad_house_no' => $this->ncsc_address_abroad_house_no,
                'ncsc_address_abroad_street' => $this->ncsc_address_abroad_street,
                'ncsc_address_abroad_county' => $this->ncsc_address_abroad_county,
                'ncsc_address_abroad_state' => $this->ncsc_address_abroad_state,
                'ncsc_address_abroad_country' => $this->ncsc_address_abroad_country,
                'ncsc_address_abroad_zip' => $this->ncsc_address_abroad_zip,
                'ncsc_primary_beneficiary_name' => $this->ncsc_primary_beneficiary_name,
                'ncsc_primary_beneficiary_rel' => $this->ncsc_primary_beneficiary_rel,
                'ncsc_secondary_beneficiary_name' => $this->ncsc_secondary_beneficiary_name,
                'ncsc_secondary_beneficiary_rel' => $this->ncsc_secondary_beneficiary_rel,
                'cswdo_assessment_date' => $this->cswdo_assessment_date ?: null,
                'cswdo_total_score' => $this->cswdo_total_score ?: null,
                'cswdo_remarks' => $this->cswdo_remarks,
                'effective_date' => $this->effective_date ?: null,
                'status' => $this->status, 'inactive_reason' => $this->inactive_reason,
                'is_active' => true,
        ];

        if ($this->seniorCitizenId) {
            $existing = SeniorCitizen::find($this->seniorCitizenId);
            $pending = $existing->pending_updates ?? [];
            
            if ($existing->first_name !== $this->first_name) $pending['first_name'] = $this->first_name;
            if ($existing->last_name !== $this->last_name) $pending['last_name'] = $this->last_name;
            if ($existing->middle_name !== $this->middle_name) $pending['middle_name'] = $this->middle_name;
            
            $existingDob = $existing->date_of_birth ? $existing->date_of_birth->format('Y-m-d') : null;
            if ($existingDob !== $this->date_of_birth) $pending['date_of_birth'] = $this->date_of_birth;

            $updateData['pending_updates'] = empty($pending) ? null : $pending;
            $updateData['first_name'] = $existing->first_name;
            $updateData['last_name'] = $existing->last_name;
            $updateData['middle_name'] = $existing->middle_name;
            $updateData['date_of_birth'] = $existing->date_of_birth;
            
            $existing->update($updateData);
            $sc = $existing;
            $this->pending_updates = $sc->pending_updates ?? [];
            
            // Revert Livewire state so inputs show the approved values
            $this->first_name = $existing->first_name;
            $this->last_name = $existing->last_name;
            $this->middle_name = $existing->middle_name;
            $this->date_of_birth = $existingDob;
        } else {
            $updateData['first_name'] = $this->first_name;
            $updateData['last_name'] = $this->last_name;
            $updateData['middle_name'] = $this->middle_name;
            $updateData['date_of_birth'] = $this->date_of_birth;
            $updateData['pending_updates'] = null;

            // If user left id_number blank, temporarily set null — assign after save
            $needsAutoNumber = ($manualId === '');
            if ($needsAutoNumber) {
                $updateData['id_number'] = null;
            }

            $sc = DB::transaction(function () use ($updateData, $needsAutoNumber) {
                $record = SeniorCitizen::create($updateData);
                if ($needsAutoNumber) {
                    // Generate inside the transaction so the lock prevents duplicates
                    $record->id_number = $this->generateIdNumber();
                    $record->save();
                }
                return $record;
            });

            $this->seniorCitizenId = $sc->id;
            $this->id_number = $sc->id_number; // reflect generated number in UI
            $this->pending_updates = [];
        }

        // Handle Media Uploads
        $idNum = $sc->id_number;
        if ($this->signature_upload) {
            $sc->signature = $this->signature_upload->store('senior_citizens/' . $idNum . '/signature', 'public');
        }
        if ($this->thumbmark_upload) {
            $sc->thumbmark = $this->thumbmark_upload->store('senior_citizens/' . $idNum . '/thumbmark', 'public');
        }
        if ($this->id_picture_upload) {
            $sc->id_picture = $this->id_picture_upload->store('senior_citizens/' . $idNum . '/id_picture', 'public');
        } elseif ($this->webcam_image_base64) {
            // Decode Base64 and store as image
            $image_parts = explode(";base64,", $this->webcam_image_base64);
            if (count($image_parts) == 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'png';
                $image_base64 = base64_decode($image_parts[1]);
                $filename = 'senior_citizens/' . $idNum . '/id_picture/' . uniqid() . '.' . $image_type;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
                $sc->id_picture = $filename;
            }
        }
        
        if ($this->signature_upload || $this->thumbmark_upload || $this->id_picture_upload || $this->webcam_image_base64) {
            $sc->save();
        }

        // Handle Multiple Attachments
        if (!empty($this->attachment_uploads)) {
            foreach ($this->attachment_uploads as $upload) {
                $path = $upload->store('senior_citizens/' . $idNum . '/attachments', 'public');
                $sc->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $upload->getClientOriginalName()
                ]);
            }
        }

        // Save Funding
        SeniorCitizenFunding::updateOrCreate(
            ['senior_citizen_id' => $sc->id],
            [
                'pension_source_id' => $this->pension_source_id ?: null,
                'monthly_pension' => $this->monthly_pension ?: null,
                'funding_id' => $this->funding_id ?: null,
                'payroll_provider_id' => $this->payroll_provider_id ?: null,
                'regular_income_type' => $this->regular_income_type,
                'regular_income_amount' => $this->regular_income_amount ?: null,
                'other_income_id' => $this->other_income_id ?: null,
                'other_income_amount' => $this->other_income_amount ?: null,
                'is_active' => true,
            ]
        );

        // Save Living Children
        $sc->livingChildren()->delete();
        foreach ($this->livingChildren as $child) {
            if (!empty($child['last_name']) || !empty($child['first_name'])) {
                SeniorCitizenLivingChild::create(['senior_citizen_id'=>$sc->id,'last_name'=>$child['last_name']??'','first_name'=>$child['first_name']??'','mobile_no'=>$child['mobile_no']??'']);
            }
        }
        // Save DSWD repeatable rows
        $sc->authorizedRepresentatives()->delete();
        foreach ($this->authorizedReps as $r) {
            if (!empty($r['last_name'])||!empty($r['first_name'])) ScAuthorizedRepresentative::create(array_merge($r,['senior_citizen_id'=>$sc->id]));
        }
        $sc->caregivers()->delete();
        foreach ($this->caregivers as $r) {
            if (!empty($r['last_name'])||!empty($r['first_name'])) ScCaregiver::create(array_merge($r,['senior_citizen_id'=>$sc->id]));
        }
        $sc->preExistingConditions()->delete();
        foreach ($this->preConditions as $r) {
            if (!empty($r['diagnosis_id'])) ScPreExistingCondition::create(['senior_citizen_id'=>$sc->id,'diagnosis_id'=>$r['diagnosis_id']]);
        }
        $sc->disabilityDiagnoses()->delete();
        foreach ($this->disabilityDiags as $r) {
            if (!empty($r['diagnosis_id'])) ScDisabilityDiagnosis::create(['senior_citizen_id'=>$sc->id,'diagnosis_id'=>$r['diagnosis_id']]);
        }
        $sc->frailties()->delete();
        foreach ($this->frailtyRows as $r) {
            if (!empty($r['frailty_id'])) ScFrailty::create(['senior_citizen_id'=>$sc->id,'frailty_id'=>$r['frailty_id']]);
        }
        $sc->pensions()->delete();
        foreach ($this->pensionRows as $r) {
            if (!empty($r['pension_source_id'])) ScPension::create(['senior_citizen_id'=>$sc->id,'pension_source_id'=>$r['pension_source_id'],'amount'=>$r['amount']??null]);
        }
        $sc->regularIncomes()->delete();
        foreach ($this->regularIncomeRows as $r) {
            if (!empty($r['regular_income_id'])) ScRegularIncome::create(['senior_citizen_id'=>$sc->id,'regular_income_id'=>$r['regular_income_id'],'amount'=>$r['amount']??null]);
        }
        $sc->otherIncomes()->delete();
        foreach ($this->otherIncomeRows as $r) {
            if (!empty($r['other_income_id'])) ScOtherIncome::create(['senior_citizen_id'=>$sc->id,'other_income_id'=>$r['other_income_id'],'amount'=>$r['amount']??null]);
        }
        // Save DSWD assessment
        ScDswdAssessment::updateOrCreate(['senior_citizen_id'=>$sc->id],[
            'ambulatory'             => $this->ambulatory === '' ? null : (bool)$this->ambulatory,
            'place_of_living_id'     => $this->dswd_place_of_living_id ?: null,
            'living_condition_id'    => $this->dswd_living_condition_id ?: null,
            'assessment'             => $this->dswd_assessment,
            'recommendation'         => $this->recommendation ?: null,
            'encoded_by'             => auth()->id(),
            'encoded_at'             => now(),
            'is_active'              => true,
        ]);

        // Save DSWD Scorings pivot table
        $sc->dswdScorings()->delete();
        foreach ($this->selectedScorings as $row) {
            if (!empty($row['consideration_scoring_detail_id'])) {
                \App\Models\ScDswdScoring::create(['senior_citizen_id'=>$sc->id,'consideration_scoring_detail_id'=>$row['consideration_scoring_detail_id']]);
            }
        }

        $this->dispatch('notify', message: $this->seniorCitizenId ? 'Record updated successfully.' : 'Record created successfully.');
        $this->closeForm();
    }


    public function deactivate($id)
    {
        $sc = SeniorCitizen::find($id);
        if ($sc) {
            $sc->is_active = false;
            $sc->status = 'INACTIVE';
            $sc->save();
            $this->dispatch('notify', message: 'Record deactivated.');
        }
    }


    public function deleteAttachment($id)
    {
        $attachment = \App\Models\SeniorCitizenAttachment::find($id);
        if ($attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
            
            // Refresh list
            if ($this->seniorCitizenId) {
                $sc = SeniorCitizen::with('attachments')->find($this->seniorCitizenId);
                $this->existing_attachments = $sc->attachments->toArray();
            }
            $this->dispatch('notify', message: 'Attachment deleted.');
        }
    }


    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }


    public function executeSearch()
    {
        $this->activeSearchTerm = trim($this->search);
        if (strlen($this->activeSearchTerm) > 0 && strlen($this->activeSearchTerm) < 3) {
            $this->dispatch('notify', message: 'Please enter at least 3 characters to search.');
            $this->activeSearchTerm = '';
        }
    }


    public function render()
    {
        $seniors = SeniorCitizen::whereRaw('1 = 0')->paginate(15); // Default to empty paginator to avoid heavy loading and links() error

        if (strlen($this->activeSearchTerm) >= 3) {
            $seniors = SeniorCitizen::with(['barangay', 'gender', 'civilStatus'])
                ->where('last_name', 'like', '%' . $this->activeSearchTerm . '%')
                ->orWhere('first_name', 'like', '%' . $this->activeSearchTerm . '%')
                ->orWhere('id_number', 'like', '%' . $this->activeSearchTerm . '%')
                ->orderBy('last_name')
                ->paginate(15);
        }

        return view('livewire.personal-information', compact('seniors'));
    }

}
