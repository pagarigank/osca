# OSCA Application - Database Schema Reference

This document tracks the tables, fields, and relationships used in the OSCA Web Application. It serves as a central reference for database design.

> **Note on Audit Fields:** ALL planned tables going forward must include the standard audit fields: `created_by` (unsignedBigInteger, nullable), `updated_by` (unsignedBigInteger, nullable), `created_at` (timestamp), and `updated_at` (timestamp).

---

## 1. System & Authentication Tables (Default Laravel)

### `users`
Stores application user accounts, authentication credentials, and OSCA-specific profile data.
- `id` (bigint, unsigned, primary key)
- `name` (varchar) - Full name
- `username` (varchar, unique, nullable) - Login username
- `email` (varchar, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (varchar, hashed)
- `office_id` (bigint, unsigned, nullable, foreign key to offices.id)
- `access_level_id` (bigint, unsigned, nullable, foreign key to access_levels.id)
- `is_active` (boolean, default: true)
- `approval_status` (enum: PENDING/APPROVED/REJECTED, default: APPROVED) - Self-registered users start as PENDING; admin-created users default to APPROVED
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `remember_token` (varchar, nullable)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `sessions`
Stores active user sessions (because `SESSION_DRIVER=database` is configured).
- `id` (varchar, primary key)
- `user_id` (bigint, unsigned, nullable, foreign key to `users.id`)
- `ip_address` (varchar, nullable)
- `user_agent` (text, nullable)
- `payload` (longtext)
- `last_activity` (integer, index)

### `password_reset_tokens`
Stores tokens for the password reset flow.
- `email` (varchar, primary key)
- `token` (varchar)
- `created_at` (timestamp, nullable)

### `jobs` / `failed_jobs` / `job_batches`
Standard Laravel queue and background job management tables.

---

## 2. Master / Library Maintenance Tables (Planned)
*Note: These tables correspond to the Master/Library Maintenance menu items. Fields will be detailed as migrations are built.*

### `payroll_providers`

**Table: `payroll_providers`**
Stores available payroll providers (e.g., GCash, PayMaya, Bank Transfer, Cash).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Represents the Payroll Provider Name
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `fundings`

**Table: `fundings`**
Stores available funding sources (e.g., National Government, LGU, Private NGO) and their allocation details.
- `id` (bigint, unsigned, primary key)
- `provider` (varchar, nullable) - e.g. NATIONAL, LGU
- `fund_source` (varchar, unique) - Represents the Funding Source Name (e.g. DBM, GAD)
- `amount` (decimal 15,2, default: 0.00) - Base amount per beneficiary
- `effective_date` (date, nullable)
- `beneficiary` (integer, default: 0) - Target number of beneficiaries
- `total` (decimal 15,2, default: 0.00) - Total allocation (amount * beneficiary)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `locations` / `barangays`

**Table: `barangays`**
Stores the barangay geographical data and assignment configurations.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `prefix` (varchar, nullable)
- `id_sequence` (integer, default: 0)
- `pay_type` (varchar, nullable)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `puroks`

**Table: `puroks`**
Stores purok subdivisions within a barangay (cascade from `barangays`). A purok name must be unique within its barangay.
- `id` (bigint, unsigned, primary key)
- `barangay_id` (bigint, unsigned, foreign key to barangays.id, cascade delete)
- `name` (varchar) - Purok name (e.g. Purok 1, Riverside)
- `prefix` (varchar, nullable)
- `id_sequence` (integer, default: 0)
- `pay_type` (varchar, nullable)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)
- *Note: Composite unique constraint on `[barangay_id, name]`*

### `purok_leaders`

**Table: `purok_leaders`**
Stores purok leader information linked to a purok (cascade from `puroks`). Each purok can have one designated leader.
- `id` (bigint, unsigned, primary key)
- `purok_id` (bigint, unsigned, foreign key to puroks.id, cascade delete)
- `last_name` (varchar)
- `first_name` (varchar)
- `middle_name` (varchar, nullable)
- `suffix` (varchar, nullable) - Jr., Sr., III, etc.
- `mobile_no` (varchar, nullable)
- `date_of_birth` (date, nullable)
- `civil_status_id` (bigint, unsigned, nullable, foreign key to civil_statuses.id)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

**Table: `locations`**
Stores the province and city/municipality combinations.
- `id` (bigint, unsigned, primary key)
- `province` (varchar) - Name of the Province
- `city_municipality` (varchar) - Name of the City or Municipality
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)
- *Note: Unique constraint on `[province, city_municipality]`*

### `quad_parameters` (QUAD1=Age, QUAD2=Health, QUAD3=Economic, QUAD4=Living Condition)

**Table: `quad_parameters`**
Single table discriminated by `quad_type` column for all 4 quadrant parameter sets.
- `id` (bigint, unsigned, primary key)
- `quad_type` (enum: QUAD1/QUAD2/QUAD3/QUAD4)
- `name` (varchar) - Description/label
- `points` (decimal 8,2, default 0)
- `age_from` (integer, nullable) - QUAD1 only: age range start
- `age_to` (integer, nullable) - QUAD1 only: age range end
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

### `frailties`

**Table: `frailties`**
Lookup table for frailty condition types (LUT-FRAILTY).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

### `regular_incomes`

**Table: `regular_incomes`**
Lookup table for regular income types (LUT-REGULAR INCOME).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

### `diagnoses` (ICD10-CM)
- *(Fields pending schema definition)*

### `factor_considerations`

**Table: `factor_considerations`**
Stores the list of factors for consideration.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Factor Name
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `consideration_scoring_details`

**Table: `consideration_scoring_details`**
Stores the scoring details linked to a specific factor for consideration.
- `id` (bigint, unsigned, primary key)
- `factor_consideration_id` (bigint, unsigned, foreign key to factor_considerations.id)
- `particular` (varchar) - Description or condition
- `guide_points` (integer) - Points assigned
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `pension_sources`

**Table: `pension_sources`**
Stores the list of pension sources.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Pension Source Name
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `income_types` (Regular, Other)

**Table: `other_incomes`**
Stores types of alternative or secondary income sources.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Represents the Other Income Source
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

- *(regular_incomes fields pending schema definition)*

### `living_conditions` / `places_of_living`

**Table: `living_conditions`**
Stores available living arrangements (e.g. Living Alone, Living with Family).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Represents the Living Arrangement
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

**Table: `place_of_livings`**
Stores available places of living (e.g. Own House, Rent, Living with Relatives).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - Represents the Place of Living
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `demographics` (Civil Status, Gender, Nationality)

**Table: `civil_statuses`**
Stores available civil status options (e.g. Single, Married, Widowed).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

**Table: `genders`**
Stores available gender options for the application.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - e.g. Male, Female
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

- *(Other demographic fields pending schema definition)*

**Table: `nationalities`**
Stores available nationality options for the application.
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique) - e.g. Filipino, American, Japanese
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `offices` / `positions` / `access_levels`

**Table: `offices`**
Stores organizational departments or offices (e.g. HR, IT, Finance).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

**Table: `access_levels`**
Stores access/permission levels for system users (e.g. Administrator, Encoder, Viewer).
- `id` (bigint, unsigned, primary key)
- `name` (varchar, unique)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)


**Table: `positions`**
Stores position/job titles under a specific office (cascade from `offices`). A position name must be unique within its office.
- `id` (bigint, unsigned, primary key)
- `office_id` (bigint, unsigned, foreign key to offices.id, cascade delete)
- `name` (varchar) - Position/job title name
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)
- *Note: Composite unique constraint on `[office_id, name]`*


---

## 3. Transaction & Core Tables

### `senior_citizens`

**Table: `senior_citizens`**
Core senior citizen masterfile record. Links to all major library tables.

**Identification**
- `id` (bigint, unsigned, primary key)
- `id_number` (varchar, unique) - Generated OSCA ID Number
- `qr_token` (varchar, 64, unique, nullable) - UUID token for secure public QR code verification
- `signature` (varchar, nullable) - Path to the uploaded signature image
- `id_picture` (varchar, nullable) - Path to the uploaded or webcam-captured ID picture
- `thumbmark` (varchar, nullable) - Path to the uploaded thumbmark image
- `ncsc_rrn` (varchar, nullable) - NCSC Reference Registration Number
- `national_id_no` (varchar, nullable) - PhilSys National ID
- `voter_id_no` (varchar, nullable) - Voter's ID Number
- `precinct_no` (varchar, nullable)
- `precinct_place` (varchar, nullable)
- `new_id_number` (varchar, nullable) - Replacement ID Number
- `pending_updates` (json, nullable) - Stores temporarily unapproved edits to Name/DOB

**Name**
- `last_name` (varchar)
- `first_name` (varchar)
- `middle_name` (varchar, nullable)
- `suffix` (varchar, nullable) - Jr., Sr., III, etc.

**Personal Details**
- `date_of_birth` (date)
- `country_of_birth` (varchar, nullable)
- `civil_status_id` (bigint, unsigned, nullable, FK → civil_statuses.id)
- `gender_id` (bigint, unsigned, nullable, FK → genders.id)
- `nationality_id` (bigint, unsigned, nullable, FK → nationalities.id)
- `citizenship` (varchar, nullable)
- `dual_citizen_document` (varchar, nullable)
- `blood_type` (varchar, nullable)
- `affiliation` (varchar, nullable)

**Contact**
- `mobile_no` (varchar, nullable)
- `email` (varchar, nullable)
- `messenger_account` (varchar, nullable)

**Address**
- `house_no` (varchar, nullable)
- `street` (varchar, nullable)
- `purok` (varchar, nullable)
- `purok_name` (varchar, nullable)
- `barangay_id` (bigint, unsigned, nullable, FK → barangays.id)
- `city_municipality` (varchar, nullable)
- `province` (varchar, nullable)

**Living Situation**
- `living_condition_id` (bigint, unsigned, nullable, FK → living_conditions.id)
- `place_of_living_id` (bigint, unsigned, nullable, FK → place_of_livings.id)

**Emergency Contact**
- `emergency_contact` (varchar, nullable)
- `emergency_contact_no` (varchar, nullable)

**Transfer to Other LGU**
- `transferred_to_lgu` (varchar, nullable)
- `transferred_first_name` (varchar, nullable)
- `transferred_suffix` (varchar, nullable)
- `transferred_relation` (varchar, nullable)

**NCSC Tab Fields**
- `ncsc_validation_date` (date, nullable)
- `sc_category` (varchar, nullable) - e.g. Centenarian, PWD Senior
- `ncsc_remarks` (text, nullable)
- `ncsc_address_abroad_house_no` (varchar, nullable)
- `ncsc_address_abroad_street` (varchar, nullable)
- `ncsc_address_abroad_county` (varchar, nullable)
- `ncsc_address_abroad_state` (varchar, nullable)
- `ncsc_address_abroad_country` (varchar, nullable)
- `ncsc_address_abroad_zip` (varchar, nullable)
- `ncsc_primary_beneficiary_name` (varchar, nullable)
- `ncsc_primary_beneficiary_rel` (varchar, nullable)
- `ncsc_secondary_beneficiary_name` (varchar, nullable)
- `ncsc_secondary_beneficiary_rel` (varchar, nullable)

**CSWDO Tab Fields**
- `cswdo_assessment_date` (date, nullable)
- `cswdo_total_score` (decimal 8,2, nullable)
- `cswdo_remarks` (text, nullable)

**Status**
- `effective_date` (date, nullable)
- `status` (varchar, default: 'ACTIVE') - ACTIVE or INACTIVE
- `inactive_reason` (text, nullable)
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, FK → users.id)
- `updated_by` (bigint, unsigned, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

---

### `senior_citizen_living_children`

**Table: `senior_citizen_living_children`**
Stores living children records linked to a senior citizen (Living Children tab).
- `id` (bigint, unsigned, primary key)
- `senior_citizen_id` (bigint, unsigned, FK → senior_citizens.id, cascade delete)
- `last_name` (varchar)
- `first_name` (varchar)
- `mobile_no` (varchar, nullable)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

### `sc_dswd_assessments`

**Table: `sc_dswd_assessments`**
Stores the main assessment data from DSWD.
- `id` (bigint, unsigned, primary key)
- `senior_citizen_id` (bigint, unsigned, foreign key)
- `ambulatory` (boolean, nullable)
- `place_of_living_id` (bigint, unsigned, nullable, foreign key)
- `living_condition_id` (bigint, unsigned, nullable, foreign key)
- `assessment` (text, nullable)
- `recommendation` (varchar, nullable) - e.g., Eligible, Not Eligible
- `encoded_by` (bigint, unsigned, nullable, foreign key)
- `encoded_at` (timestamp, nullable)
- `validated_by` (bigint, unsigned, nullable, foreign key)
- `validated_at` (timestamp, nullable)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

### `sc_dswd_scorings`

**Table: `sc_dswd_scorings`**
Pivot table storing the selected consideration scoring details for the senior citizen's assessment.
- `id` (bigint, unsigned, primary key)
- `senior_citizen_id` (bigint, unsigned, foreign key)
- `consideration_scoring_detail_id` (bigint, unsigned, foreign key)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

---

### `senior_citizen_fundings`

**Table: `senior_citizen_fundings`**
Stores pension, funding, payroll, and income details per senior citizen (Funding tab). One-to-one with senior_citizens.
- `id` (bigint, unsigned, primary key)
- `senior_citizen_id` (bigint, unsigned, FK → senior_citizens.id, cascade delete)
- `pension_source_id` (bigint, unsigned, nullable, FK → pension_sources.id)
- `monthly_pension` (decimal 10,2, nullable)
- `funding_id` (bigint, unsigned, nullable, FK → fundings.id)
- `payroll_provider_id` (bigint, unsigned, nullable, FK → payroll_providers.id)
- `regular_income_type` (varchar, nullable)
- `regular_income_amount` (decimal 10,2, nullable)
- `other_income_id` (bigint, unsigned, nullable, FK → other_incomes.id)
- `other_income_amount` (decimal 10,2, nullable)
- `is_active` (boolean, default: true)
- `created_by` / `updated_by` (bigint, nullable, FK → users.id)
- `created_at` / `updated_at` (timestamps)

---

### `senior_citizen_attachments`

**Table: `senior_citizen_attachments`**
Stores multiple supporting documents and files linked to a senior citizen.
- `id` (bigint, unsigned, primary key)
- `senior_citizen_id` (bigint, unsigned, FK → senior_citizens.id, cascade delete)
- `file_path` (varchar) - Storage path of the file
- `file_name` (varchar) - Original uploaded file name
- `created_at` / `updated_at` (timestamps)

---

### `diagnoses`

**Table: `diagnoses`**
Stores the list of medical diagnoses including ICD10-CM codes.
- `id` (bigint, unsigned, primary key)
- `generic_name` (varchar) - Generic Diagnosis Name
- `icd10_code` (varchar, nullable) - ICD10-CM Code
- `icd10_short_name` (varchar, nullable) - ICD10-CM Short Name
- `icd10_long_name` (varchar, nullable) - ICD-10CM Long Name
- `is_active` (boolean, default: true)
- `created_by` (bigint, unsigned, nullable, foreign key to users.id)
- `updated_by` (bigint, unsigned, nullable, foreign key to users.id)
- `created_at` (timestamp, nullable)
- `updated_at` (timestamp, nullable)

### `payrolls`
- *(Fields pending schema definition)*

### `chat_sessions`

**Table: `chat_sessions`**
Stores the active and closed live chat sessions from public guests.
- `id` (bigint, unsigned, primary key)
- `guest_identifier` (varchar, unique) - Session UUID token for the guest
- `guest_name` (varchar) - Name provided by the guest
- `status` (varchar, default: 'active') - 'active' or 'closed'
- `created_at` / `updated_at` (timestamps)

### `chat_messages`

**Table: `chat_messages`**
Stores individual chat messages linked to a chat session.
- `id` (bigint, unsigned, primary key)
- `chat_session_id` (bigint, unsigned, FK → chat_sessions.id, cascade delete)
- `sender_type` (varchar) - 'guest' or 'admin'
- `user_id` (bigint, unsigned, nullable, FK → users.id) - ID of the admin if sender_type is admin
- `message` (text)
- `is_read` (boolean, default: false)
- `created_at` / `updated_at` (timestamps)
