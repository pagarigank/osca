# OSCA Application - Change Tracking & Notes

This document serves as a change log and technical reference (RAG data) for the OSCA Web Application development.

## Architecture & Stack
- **Framework**: Laravel 12
- **Frontend Engine**: Livewire 3 + Alpine.js
- **Database**: MySQL (Database: `osca`)
- **Styling**: Vanilla CSS (Modern Light Blue Theme)
- **Routing**: SPA-like dynamic page loading via `wire:navigate`

---

## Change Log

### [Bugfix: ID Card Print Layout — Blank Pages Fix]
**Date:** 2026-07-14
**Description:** Fixed the ID card printing layout that was producing extra blank pages and cutting off the back side of the card.
**Root Causes:**
- `preview-wrap` used `display: flex` in print — browsers miscalculate page breaks inside flex containers, causing content overflow and blank pages.
- `html, body` had `height: 54mm` and `overflow: hidden` in `@media print`, which constrained the body to exactly one page height and clipped the back card entirely.
- `@page` rule was incorrectly scoped inside `@media print` instead of being a top-level rule.
- Paper size was set to CR80 standard (85.6mm × 53.98mm) instead of the actual printer paper size (86mm × 54mm).
**Fixes Applied (`print-id.blade.php`):**
- Moved `@page` rule outside `@media print` to top-level (required by CSS spec).
- Changed paper size to **86mm × 54mm** in both `@page` and CSS variables (`--card-w`, `--card-h`).
- Changed `.preview-wrap` to `display: block` in print mode — eliminates flex-based page-break miscalculation.
- Removed `height: 54mm` and `overflow: hidden` from `html, body` in print — lets the browser paginate naturally across pages.
- Added `page-break-inside: avoid` on `.preview-wrap > div` wrappers and `.card` elements to prevent content splitting.
- Added explicit `width: 86mm` on `html, body` and `.preview-wrap` to constrain layout width.
- Added `page-break-after: auto` on `.card-back` as explicit override.
- Added `!important` to `.screen-label` hide rule for print.
**Result:** Print output is now exactly 2 pages — front on page 1, back on page 2, no blank pages.

### [Feature: ID Card Print Layout Refinements]
**Date:** 2026-07-14
**Description:** Adjusted the front ID card layout for better visual balance and replaced the text watermark with the official Mabalacat City LGU seal.
**Implementation Details (`print-id.blade.php`):**
- **Header:** Enlarged both logo images from 11mm to 13mm. Increased font sizes: Republic text 5pt → 5.5pt, City Name 6.5pt → 7.5pt, Office Name 5pt → 5.5pt.
- **ID Photo:** Enlarged left column from 19mm to 21mm, photo box from 19mm×22mm to 21mm×24mm. Signature area width matched to 21mm.
- **Personal Info:** Increased name values 7.5pt → 8pt, middle name 6.5pt → 7pt, detail values 5.5pt → 6pt, address value 5pt → 5.5pt. Added 1mm left padding to `.front-right` to shift text away from the photo edge.
- **Address Positioning:** Tightened spacing above address — reduced `.name-block` margin-bottom 1.5mm → 1mm, `.details-row` margin-bottom 1mm → 0.5mm, `.address-block` margin-top 0.5mm → 0mm — moving the address ~1mm upward for better visibility within the card boundary.
- **Watermark:** Replaced the "BAGONG PILIPINAS" text watermark on the front card with `mabalacat_seal.png` rendered as a centered background image at 38mm size and 7% opacity via the new `.watermark-logo` CSS class. Back card retains the original text watermark.

### [Feature: Payroll Slot Tracking — Occupied & Available]
**Date:** 2026-06-17
**Description:** Enhanced the Payroll listing to display real-time slot utilization based on the configured `beneficiary` count in Funding Maintenance.
**Implementation Details:**
- **Livewire Component (`Payroll.php`):** Added `getSelectedFundingProperty()` computed property. Calculates `$totalSlots` (from `fundings.beneficiary`), `$occupiedSlots` (count of enrolled `SeniorCitizenFunding` rows), and `$availableSlots` (`totalSlots - occupiedSlots`) in `render()`. Fixed `getBeneficiariesProperty()` to return `collect()` for consistent collection handling.
- **Blade View (`payroll.blade.php`):** Added 4 summary stat cards visible when a funding source is selected: **Total Slots** (purple), **Occupied Slots** (amber), **Available Slots** (green/red based on capacity), and **Total Budget** (blue, showing per-beneficiary rate). Added a `#` row counter column. Added a `<tfoot>` row showing `X / Y slots filled` and a running total amount. Added ₱ peso sign to all monetary values.

### [Feature: Thumbmark Scanning & Uploading]
**Date:** 2026-06-17
**Description:** Added support for uploading thumbmark files on the Personal Information form and displaying them on the printed ID card.
**Implementation Details:**
- **Database:** Added `thumbmark` column to `senior_citizens` table via migration.
- **Storage:** Uses Laravel's `public` disk, storing the file in `storage/app/public/senior_citizens/{id_number}/thumbmark`.
- **Livewire:** Upgraded `PersonalInformation.php` component to handle `$thumbmark_upload` property alongside signatures and ID pictures.
- **UI:** Added a thumbmark upload block in the Attachments & Media section, supporting image preview via Alpine.js full-screen modal.
- **Print Layout:** Updated `print-id.blade.php` to dynamically render the uploaded thumbmark image inside the designated 12x14mm Thumbmark box on the back of the ID card.

### [Feature: Auto-Numbering ID Generation (M-XXXX)]
**Date:** 2026-06-17
**Description:** Implemented an automated ID numbering system (`M-0000` format) for the `id_number` field during Senior Citizen registration.
**Implementation Details:**
- **Database:** Created a migration to make `id_number` nullable in the `senior_citizens` table to allow temporary nulls during the two-step create-and-update process.
- **Logic (`PersonalInformation.php`):** If the ID field is left blank on creation, the system uses pessimistic locking (`DB::transaction` with `lockForUpdate()`) to safely generate the next sequence (e.g., `M-0001`) and update the record, preventing race conditions.
- **UI (`personal-information.blade.php`):** The `id_number` field is optional on create with a hint, and becomes read-only on edit mode to prevent modification of assigned IDs. Manual entry is still supported on creation with uniqueness validation.

### [Feature: Printable ID Card Redesign]
**Date:** 2026-06-17
**Description:** Completely overhauled the `print-id.blade.php` layout to match the official Mabalacat City/OSCA government design.
**Implementation Details:**
- **Visuals:** Integrated official City and OSCA assets, a "Bagong Pilipinas" watermark, and standardized typography on a white card background.
- **Data Mapping:** Reorganized personal data into a professional 3-row name format, added long-date DOB and Issue Date. Fixed data artifacts (e.g., stripping trailing digits from "MALE1" or "FEMALE2").
- **Back Layout:** Implemented fixed 12x14mm boxes for Thumbmark and QR Code on the left. Created a right-side column for officials (OSCA Head and Mayor) with reserved 7mm signature lines. Spaced layout separating officials and emergency contact information.
- **Performance:** Added eager loading (`with(['gender', 'barangay'])`) in `PrintController` to prevent N+1 queries during generation.

### [Feature: Payroll Report & Datatable]
**Date:** 2026-06-07
**Description:** Built the Payroll module to list all Senior Citizens associated with a selected Funding source, along with a printable formal report.
**Implementation Details:**
- **Livewire Component (`Payroll.php`):** Implemented dynamic dropdown to select a `Funding` source, which queries the `SeniorCitizenFunding` table to list associated beneficiaries.
- **Blade View (`payroll.blade.php`):** Created a clean, SPA-like datatable displaying ID, Name, Barangay, and Amount without page reloads.
- **Print Layout:** Built a dedicated `print-payroll.blade.php` view utilizing `@media print` CSS rules to format a formal tabular report with dynamic totals and signature lines. Exposed via `PrintController@printPayroll`.

### [Feature: Funding CRUD Enhancement]
**Date:** 2026-06-07
**Description:** Expanded the Funding CRUD module to include financial allocation data and an auto-calculating total field.
**Implementation Details:**
- **Database:** Added `provider`, `amount`, `effective_date`, `beneficiary`, and `total` columns to the `fundings` table. Renamed `name` to `fund_source`.
- **Livewire Logic (`FundingMaintenance.php`):** Added `updatedAmount` and `updatedBeneficiary` hooks to automatically calculate the `total` dynamically as the user types.
- **UI:** Updated the modal and datatable to support the new fields and formatted the decimal values.

### [Feature: Public Contact Us Page & Mail Integration]
**Date:** 2026-06-02
**Description:** Created a public Contact page allowing guests to send email inquiries, utilizing Laravel's Mail facade.
**Implementation Details:**
- **Route:** Added `/contact` GET route pointing to `App\Livewire\ContactUs`.
- **Livewire Component:** Built `ContactUs.php` which validates Name, Email, Subject, and Message, and displays a success notification upon sending.
- **Mailable:** Generated `ContactFormMail.php` to handle email delivery. Configured the `Reply-To` header dynamically based on the guest's provided email.
- **View:** Designed a professional HTML email template in `resources/views/emails/contact-form.blade.php`.
- **Server:** Instructed use of external SMTP or Mailtrap for local testing to avoid spam blocking.

### [Feature: Live Support Chat System]
**Date:** 2026-06-02
**Description:** Implemented a real-time polling chat system allowing public guests to communicate with logged-in admins.
**Implementation Details:**
- **Database:** Created `chat_sessions` (tracks unique guests via session UUID) and `chat_messages` (tracks individual messages sent by either 'guest' or 'admin').
- **Guest Interface:** Created `GuestChat` Livewire component mapped to `/guest-chat`. Generates a UUID token in the browser session. Uses `wire:poll` for real-time updates.
- **Admin Interface:** Created `AdminChat` Livewire component mapped to `/messages`. Features a sidebar for active sessions with unread badges, and a main chat area. Admin can close sessions via the "End Chat" button.
- **Auto-Reopen Logic:** If an admin closes a chat, but the guest replies later, the system automatically changes the session status back to 'active' and bumps it to the top of the admin's queue via `$session->touch()`.

### [Feature: Supporting Documents / Attachments Module]
**Date:** 2026-06-02
**Description:** Added a module to the Personal Information tab allowing users to attach multiple files/documents to a Senior Citizen record, complete with a thumbnail grid and a full-screen image viewer.
**Implementation Details:**
- **Database:** Created `senior_citizen_attachments` table (`senior_citizen_id`, `file_path`, `file_name`) with a `cascadeOnDelete` foreign key.
- **Model:** Created `SeniorCitizenAttachment` model and added `hasMany` relationship `attachments()` to `SeniorCitizen`.
- **Livewire Component (`PersonalInformation.php`):** Implemented multi-file upload loop storing files in the public disk (`senior_citizens/{id}/attachments`), and added `deleteAttachment()` method to remove records and files cleanly.
- **UI & Alpine.js:** Added an upload box and a flex grid for thumbnails. Built a lightweight full-screen modal viewer using Alpine.js (`x-teleport="body"`) that triggers on image click, dimming the background. This modal was also extended to handle the Signature and ID Picture previews.

### [Feature: Edit Approval Workflow (Pending Updates)]
**Date:** 2026-06-02
**Description:** Implemented an intercept/approval workflow for critical fields (First Name, Middle Name, Last Name, Date of Birth). When an existing record is edited, these fields are held in a pending state until a Supervisor/Admin approves them.
**Implementation Details:**
- **Database:** Added a `pending_updates` JSON column to the `senior_citizens` table.
- **Livewire Logic (`PersonalInformation.php`):** Modified the `save()` method. If `$isEditMode` is true and any of the 4 critical fields differ from the original DB values, the new values are injected into the `pending_updates` JSON array instead of directly updating the columns. The Livewire state is then reverted so inputs display the "officially approved" values.
- **UI / Approval Methods:** Added an `@if(!empty($pending_updates))` warning banner below the Name block displaying the exact proposed changes. Added `approvePendingUpdates()` (applies JSON to columns and clears JSON) and `rejectPendingUpdates()` (clears JSON). The buttons are restricted to users with `is_admin == true` or `role === 'Admin'`.

### [Feature: Printable ID Card Generation]
**Date:** 2026-06-02
**Description:** Added a direct "Print ID" feature that generates a perfectly scaled HTML layout optimized for printing on CR80 PVC ID Card printers.
**Implementation Details:**
- **Routes/Controllers:** Added `Route::get('/print/id/{id}')` handled by `PrintController@printId`.
- **View:** Created `resources/views/pages/print-id.blade.php`. Uses `@media print` and `@page { size: 85.6mm 53.98mm landscape; margin: 0; }` to strictly enforce PVC ID dimensions.
- **Layout:** Includes the Senior Citizen's details, the newly integrated ID Picture and Signature, and prominently displays the verification QR Code on the bottom right.
- **Trigger:** Added a `🖨️ Print ID` button on the Personal Information form action bar (visible in Edit mode) that opens the print layout in a new tab and fires `window.print()` automatically.

### [Feature: Media Uploads and Webcam Capture]
**Date:** 2026-06-02
**Description:** Added support for uploading signature files and ID pictures, as well as capturing ID pictures directly from the user's webcam.
**Implementation Details:**
- **Database:** Added `signature` and `id_picture` columns to `senior_citizens` table.
- **Storage:** Uses Laravel's `public` disk. Files are scoped dynamically into `storage/app/public/senior_citizens/{id_number}/`.
- **Livewire:** Upgraded `PersonalInformation` component with `WithFileUploads` trait to handle standard file uploads, and added a `$webcam_image_base64` property to handle Base64 webcam snapshots.
- **Webcam UI:** Implemented an Alpine.js (`x-data="webcam()"`) component that requests `navigator.mediaDevices.getUserMedia`, displays a live `<video>` feed, captures the frame to a hidden `<canvas>`, and converts it to a Base64 string which is passed seamlessly to Livewire.

### [Feature: QR Code ID Verification]
**Date:** 2026-06-01
**Description:** Added a secure, public verification page intended for printed Senior Citizen IDs. Includes a QR code generator API that produces SVG codes for SCs.
**Implementation Details:**
- **Security:** Adds a UUIDv4 `qr_token` to `senior_citizens`. Generated once on creation; never regenerates, meaning printed IDs are valid permanently.
- **Routes:** 
  - `GET /verify/{token}` (Public): Renders `sc-verification.blade.php`.
- **Packages:** Installed `chillerlan/php-qrcode` for pure-PHP, GD-independent QR code generation.
- **Inline Rendering:** Bypassed API routes entirely for QR code generation to avoid JWT Auth Header restrictions on `<img>` tags. The QR Code is generated as a Base64 SVG Data URI directly inside the `personal-information.blade.php` file using inline PHP.
- **UI:** Added an `sc-verification` Livewire component/view that shows "Verified Active" (Green) or "Inactive/Flagged" (Red), and displays SC basic info without exposing sensitive fields.
- **Admin Panel:** The Personal Information module now displays the generated QR code placeholder when editing a record, with a link to test the public verification page.

### [Feature: Position CRUD Maintenance]
**Date:** 2026-06-01
**Description:** Created the `positions` table as a child of `offices` (cascade relationship). Positions are scoped per office — the same position name can exist in different offices but must be unique within the same office.
**Components Created:**
- `Position` model — fillable, booted audit tracking, `belongsTo Office` relationship
- `Office` model — added `hasMany positions()` relationship
- `App\Livewire\PositionMaintenance` — full CRUD with composite unique validation, office filter bar, soft delete
- `position-maintenance.blade.php` — office filter dropdown, datatable with Office badge column, modal with Office parent dropdown
**New Migration:**
- `2026_06_01_152100_create_positions_table` — creates `positions` table with `office_id` FK (cascade delete), composite unique on `[office_id, name]`, full standard audit fields
**Routing:** Replaced placeholder `/position` route with `PositionMaintenance::class`
**Key Implementation Details:**
- Unique validation uses `Rule::unique('positions')->where('office_id', ...)` to scope uniqueness per office.
- Table filter bar allows filtering positions by office without reloading the page (`wire:model.live`).
- `tables.md` updated with full `positions` schema (previously marked as pending).

### [Feature: Registration Approval Workflow]
**Date:** 2026-06-01
**Description:** Implemented a full admin-approval flow for user self-registration. All new accounts created via `/register` are held in `PENDING` status and cannot log in until an administrator approves them via the User Management module.
**Components Modified:**
- `User` model — added `approval_status` to `$fillable` and casts
- `Register` (Livewire Component & Blade View) — added Office dropdown (required, from `offices` table), set `approval_status = 'PENDING'` and `is_active = false` on create, redirects to login with a session flash notice instead of auto-login
- `Login` (Livewire Component) — added PENDING and REJECTED guards before session creation
- `login.blade.php` — shows `registration_pending` session flash as a green notice banner
- `Dashboard` (Livewire Component & Blade View) — loads real stats (total/active senior citizens, pending registration count), shows a Pending Registrations panel with a quick link to User Management
- `UserMaintenance` (Livewire Component & Blade View) — added `approve()` and `reject()` methods, prominent pending approvals section at top, `approval_status` badge column, context-aware action buttons per row
**New Migration:**
- `2026_06_01_144800_add_approval_status_to_users_table` — adds `approval_status` enum (`PENDING`, `APPROVED`, `REJECTED`, default `APPROVED`) to `users`
**Key Implementation Details:**
- Existing and admin-created users default to `APPROVED` (no disruption to current accounts).
- Users created via User Management modal are immediately set to `APPROVED`.
- Login blocks both `PENDING` and `REJECTED` accounts with distinct, user-friendly error messages.
- Dashboard stat cards are live (real DB counts). Quick Links bar includes User Management with pending badge.

### [Initial Setup & Layout]
- **Database Configuration**: Updated `.env` to use MySQL driver, pointed to `osca` database, and successfully migrated base tables (users, sessions, jobs). Configured session driver to `database`.
- **Master Layout (`resources/views/layouts/app.blade.php`)**: 
  - Overhauled default layout to feature a custom header and a **Top Navigation Menu**.
  - Integrated SVG icons for menu items.
  - Implemented dynamic active state checking for routes.
- **Styling (`resources/css/app.css`)**:
  - Implemented custom CSS variables for a modern light blue color palette (AliceBlue background, gradients, glassmorphism surface effects).
  - Resolved z-index stacking issues to ensure dropdown menus overlay page content properly.
- **Navigation Menu & Dropdown**:
  - Moved the main navigation menu from a sidebar to a horizontal top bar below the main header.
  - Implemented a mega-menu style dropdown for the `Master/Library Maintenance` menu.
  - Formatted the dropdown into a responsive 3-column layout using CSS `column-count`.

### [Dynamic Routing & Scaffolding]
- **Dynamic Pages**: Scaffolded 25 dynamic placeholder Blade views for all main menu and dropdown items (Transactions, Payroll, Reports, Messages, and 21 Master/Library Maintenance sub-items).
- **SPA Navigation**: Integrated Livewire's `wire:navigate` attribute into all navigation links, allowing for instant page transitions without full browser reloads.
- **Route Definitions**: Appended all 25 specific routes to `routes/web.php` pointing to the newly generated `resources/views/pages/` Blade components.

### [Feature: Gender CRUD Maintenance]
- **Database & Model**: Created the `genders` table (`name`, `is_active`) and the corresponding `Gender` Eloquent model with `$fillable` fields.
- **SPA Component**: Implemented `App\Livewire\GenderMaintenance` as a full Livewire component replacing the static placeholder route.
- **Datatable & Modal**: Developed a clean HTML datatable for viewing records and a smooth overlay modal for creating and editing records, entirely driven by Livewire state (`$isModalOpen`, `$isEditMode`) without full page reloads.
- **Styling**: Appended custom Vanilla CSS rules for `.data-table`, `.modal-backdrop`, `.modal-box`, and standardized `.btn` variants to `app.css`.

### [Feature: Civil Status CRUD Maintenance]
- **Database & Model**: Created the `civil_statuses` table (`name`, `is_active`, `created_by`, `updated_by`) and the corresponding `CivilStatus` Eloquent model with `$fillable` fields and automated `booted` audit tracking.
- **SPA Component**: Implemented `App\Livewire\CivilStatusMaintenance` Livewire component, strictly adhering to the Toast notification dispatch and soft delete pattern.
- **Routing**: Replaced placeholder route for `/civil-status` with the Livewire class route.

### [Feature: Regular Income CRUD Maintenance]
- **Database & Model**: Utilized the existing `regular_incomes` table (`name`, `is_active`, `created_by`, `updated_by`) and `RegularIncome` Eloquent model.
- **SPA Component**: Implemented `App\Livewire\RegularIncomeMaintenance` Livewire component, strictly adhering to the standard modal UI, Toast notification dispatch, and soft delete pattern.
- **Routing**: Replaced placeholder static route for `/regular-income` with the Livewire class route.

### [Feature: Office CRUD Maintenance]
- **Database & Model**: Created the `offices` table (`name`, `is_active`, `created_by`, `updated_by`) and the corresponding `Office` Eloquent model with `$fillable` fields and automated `booted` audit tracking.
- **SPA Component**: Implemented `App\Livewire\OfficeMaintenance` Livewire component following the Toast notification dispatch and soft delete pattern.
- **Routing**: Replaced placeholder route for `/office` with the Livewire class route.

### [Feature: NCSC Tab Fields]
**Date:** 2026-06-01
**Description:** Appended the NCSC Address Abroad and Designated Beneficiary fields to the `senior_citizens` table and `PersonalInformation` Livewire module based on user-provided schema mapping.
**Components Modified:**
- `SeniorCitizen` model
- `PersonalInformation` (Livewire Component & Blade View)
**New Migrations:**
- `2026_06_01_045734_add_ncsc_beneficiary_and_address_fields_to_senior_citizens_table`
**Key Implementation Details:**
- Added 10 new fields representing the address abroad (house no, street, county, state, country, zip) and primary/secondary designated beneficiaries (name, relationship).
- Rendered these newly added NCSC fields inside the `NCSC Tab` of the personal information module using responsive form blocks.

### [Feature: Factor Consideration Scoring Integration (DSWD Module)]
**Date:** 2026-06-01
**Description:** Replaced the static Quadrant parameter dropdowns in the DSWD Assessment tab with a dynamic Factor Consideration datatable, allowing multiple scoring details to be selected and automatically summing the guide points.
**Components Modified:**
- `PersonalInformation` (Livewire Component & Blade View)
- `ScDswdAssessment` model
- `FactorConsideration` model
**New Components/Migrations:**
- `2026_06_01_041803_replace_quadrant_with_dswd_scorings` migration (dropped quad fields, created `sc_dswd_scorings` table)
- `ScDswdScoring` pivot model
**Key Implementation Details:**
- Removed `age`, `health_condition_id`, `economic_status_id`, `living_condition_quad_id` and their corresponding points fields from `sc_dswd_assessments`.
- Created `sc_dswd_scorings` pivot table to link `senior_citizen_id` to multiple `consideration_scoring_detail_id`s.
- DSWD Tab now dynamically lists all `FactorConsideration`s and their nested `ConsiderationScoringDetail`s in a datatable with checkboxes.
- Selection changes automatically recalculate the `Total Accumulated Points`.
- Save logic uses `$sc->dswdScorings()->delete()` and inserts new records sequentially.

### [Feature: DSWD Tab — DSWD Assessment Module]
- **New Library Tables (LUT)**: Created `frailties` (LUT-FRAILTY), `regular_incomes` (LUT-REGULAR INCOME), and `quad_parameters` (single table with `quad_type` enum: QUAD1=Age, QUAD2=Health, QUAD3=Economic, QUAD4=Living Condition, with `points`, `age_from`, `age_to` fields).
- **New SC Child Tables (12 total migrated)**:
  - `sc_authorized_representatives` — max 3 per SC; fields: name (Last/First/Middle/Suffix), DOB, civil_status_id (FK), phone
  - `sc_caregivers` — name, relationship, phone, linked to SC
  - `sc_pre_existing_conditions` — max 5; FK to `diagnoses` (ICD10-CM)
  - `sc_disability_diagnoses` — max 5; FK to `diagnoses`
  - `sc_frailties` — max 3; FK to `frailties` LUT
  - `sc_pensions` — max 2; FK to `pension_sources`, decimal amount
  - `sc_regular_incomes` — max 2; FK to `regular_incomes` LUT, decimal amount
  - `sc_other_incomes` — max 2; FK to `other_incomes`, decimal amount
  - `sc_dswd_assessments` — one-to-one with SC; includes: ambulatory (bool), place_of_living_id, living_condition_id, Quadrant fields (age, age_points, health_condition_id/points, economic_status_id/points, living_condition_quad_id/points, quadrant_total_points), assessment (text), recommendation (enum), encoded_by/at, validated_by/at
- **Models**: Created 12 Eloquent models with fillable, relationships, and booted audit tracking. Added all DSWD hasMany/hasOne relationships to `SeniorCitizen`.
- **`SeniorCitizen` model updated** with 9 new relationship methods for all DSWD child tables.
- **Documentation**: Updated `tables.md` with full schemas for `quad_parameters`, `frailties`, `regular_incomes`, and all SC DSWD child tables.

### [Feature: Personal Information Transaction Module]
- **Database & Models**: Created 3 new migration tables:
  - `senior_citizens` — core masterfile (~50 columns) covering identification, name, personal details, contact, address, living situation, emergency contact, transfer info, NCSC fields, CSWDO fields, and status. Foreign keys to `civil_statuses`, `genders`, `nationalities`, `barangays`, `living_conditions`, `place_of_livings`, `users`.
  - `senior_citizen_living_children` — child table (last_name, first_name, mobile_no) linked to senior_citizens via cascade FK.
  - `senior_citizen_fundings` — one-to-one funding detail record (pension source, monthly pension, funding source, payroll provider, regular income, other income) linked to senior_citizens.
- **Models**: Created `SeniorCitizen` (full fillable, casts, and all relationships), `SeniorCitizenLivingChild` (belongsTo), `SeniorCitizenFunding` (belongsTo + library FKs), all with booted audit tracking.
- **Livewire Component**: Built `App\Livewire\PersonalInformation` with: full 5-tab state management, all library dropdown lookups (10 tables), searchable list view with pagination, dynamic living children row add/remove, and `updateOrCreate` save logic across all 3 tables atomically.
- **Blade View**: Created `personal-information.blade.php` — OSCA banner header, 5-tab navigation (Personal Information, NCSC, CSWDO, Funding, Living Children), form fields grouped into sections matching the reference layout image, QR/Signature/Photo placeholders, and audit info block.
- **Routing**: Added `/personal-information` named route. Converted Transactions top-nav item into a dropdown containing the Personal Information sub-link.
- **Documentation**: Updated `tables.md` (Section 3 rewritten with full schemas for all 3 tables) and `ragdata.md` (this entry).

### [Feature: Search-On-Demand Optimization (Personal Information)]
**Date:** 2026-06-01
**Description:** Modified the `PersonalInformation` datatable to use a Fuzzy/On-Demand search pattern instead of live querying on component load. This prevents massive data dumping from the `senior_citizens` table upon initial page load.
**Key Implementation Details:**
- Added `activeSearchTerm` property to decouple the input binding from the query state.
- Changed `wire:model.live` to `wire:model` and added `wire:keydown.enter="executeSearch"` in the blade view.
- Added a `strlen() >= 3` safeguard inside the `render()` method to only execute the database query when a valid search term is confirmed.
- Defaults to an empty `collect()` object when the search term is empty, effectively rendering the table empty initially.

### [Feature: Consideration Scoring Detail CRUD Maintenance]
- **Database & Model**: Created the `consideration_scoring_details` table linking to `factor_considerations` via `factor_consideration_id`, containing `particular`, `guide_points`, and standard audit fields. Created the `ConsiderationScoringDetail` model with booted audit tracking.
- **SPA Component**: Built the `App\Livewire\ConsiderationScoringDetailMaintenance` component and view, including a dropdown to select the parent factor. Maintained the soft delete and Toast notification standards.
- **Routing**: Connected `/f-scoring` to the component in `routes/web.php`.

### [Feature: Factor Consideration CRUD Maintenance]
- **Database & Model**: Created the `factor_considerations` table (`name` for Factor Name, plus standard audit fields). Created the `FactorConsideration` model with booted audit tracking.
- **SPA Component**: Built the `App\Livewire\FactorConsiderationMaintenance` component and view, maintaining the soft delete and Toast notification standards.
- **Routing**: Connected `/f-consideration` to the component in `routes/web.php`.

### [Feature: Pension Source CRUD Maintenance]
- **Database & Model**: Created the `pension_sources` table (`name` for Pension Source Name, plus standard audit fields). Created the `PensionSource` model with booted audit tracking.
- **SPA Component**: Built the `App\Livewire\PensionSourceMaintenance` component and view, maintaining the soft delete and Toast notification standards.
- **Routing**: Connected `/pension-source` to the component in `routes/web.php`.

### [Feature: Diagnosis CRUD Maintenance]
- **Database & Model**: Created the `diagnoses` table with fields for `generic_name`, `icd10_code`, `icd10_short_name`, and `icd10_long_name`, plus standard audit fields. Created `Diagnosis` model with booted audit tracking.
- **SPA Component**: Built the `App\Livewire\DiagnosisMaintenance` component for validating and processing complex records with multiple optional inputs.
- **Routing**: Connected `/diagnosis` to the component in `routes/web.php`.

### [Feature: Location CRUD Maintenance]
- **Database & Model**: Created the `locations` table with a composite unique index on `province` and `city_municipality`, alongside our standard audit fields (`is_active`, `created_by`, `updated_by`). Configured the `Location` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\LocationMaintenance` component and view, handling the dual-field layout and utilizing standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/location` route to the new Livewire class.

### [Feature: Funding CRUD Maintenance]
- **Database & Model**: Created the `fundings` table (`name` for Funding Source, `is_active`, `created_by`, `updated_by`) and the `Funding` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\FundingMaintenance` component and view, using the standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/funding` route to the new Livewire class.

### [Feature: Payroll Provider CRUD Maintenance]
- **Database & Model**: Created the `payroll_providers` table (`name` for Provider Name, `is_active`, `created_by`, `updated_by`) and the `PayrollProvider` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\PayrollProviderMaintenance` component and view, using the standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/payroll-provider` route to the new Livewire class.

### [Feature: Other Income CRUD Maintenance]
- **Database & Model**: Created the `other_incomes` table (`name` for Income Source, `is_active`, `created_by`, `updated_by`) and the `OtherIncome` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\OtherIncomeMaintenance` component and view, using the standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/other-income` route to the new Livewire class.

### [Feature: Place of Living CRUD Maintenance]
- **Database & Model**: Created the `place_of_livings` table (`name` for Place of Living, `is_active`, `created_by`, `updated_by`) and the `PlaceOfLiving` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\PlaceOfLivingMaintenance` component and view, using the standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/place-of-living` route to the new Livewire class.

### [Feature: Living Condition CRUD Maintenance]
- **Database & Model**: Created the `living_conditions` table (`name` for Living Arrangement, `is_active`, `created_by`, `updated_by`) and the `LivingCondition` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\LivingConditionMaintenance` component and view, using the standard soft delete logic and Toast notifications.
- **Routing**: Linked the `/living-condition` route to the new Livewire class.

### [Feature: Barangay CRUD Maintenance]
- **Database & Model**: Created the `barangays` table (`name`, `prefix`, `id_sequence`, `pay_type`, `is_active`, `created_by`, `updated_by`) and the `Barangay` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\BarangayMaintenance` component and view, with multi-column form inputs and the standard soft delete logic.
- **Routing**: Linked the `/barangay` route to the new Livewire class.

### [Feature: Nationality CRUD Maintenance]
- **Database & Model**: Created the `nationalities` table (`name`, `is_active`, `created_by`, `updated_by`) and the `Nationality` model with automated audit tracking.
- **SPA Component**: Implemented the `App\Livewire\NationalityMaintenance` component and corresponding view, adhering to the standard modal UI and soft delete logic.
- **Routing**: Linked the `/nationality` route to the new Livewire class.

### [Feature: Access Level CRUD Maintenance]
- **Database & Model**: Created the `access_levels` table (`name`, `is_active`, `created_by`, `updated_by`) and the corresponding `AccessLevel` Eloquent model with `$fillable` fields and automated `booted` audit tracking.
- **SPA Component**: Implemented `App\Livewire\AccessLevelMaintenance` Livewire component following the Toast notification dispatch and soft delete pattern.
- **Routing**: Replaced placeholder route for `/access-level` with the Livewire class route.

### [Feature: User CRUD Maintenance]
- **Database Extension**: Added OSCA-specific columns to the existing `users` table via a new migration: `username` (unique), `office_id` (FK to offices), `access_level_id` (FK to access_levels), `is_active`, `created_by`, `updated_by`.
- **Model Update**: Extended the `User` Eloquent model with new `$fillable` fields, `office()` and `accessLevel()` BelongsTo relationships, and standard `booted` audit tracking.
- **SPA Component**: Implemented `App\Livewire\UserMaintenance` with a wider 2-column modal grid for name, username, email, password (with confirmation), office dropdown, access level dropdown, and active status toggle. Password is optional on edit (leave blank to keep current).
- **Routing**: Replaced placeholder route for `/users` with the Livewire class route.

### [Feature: Purok CRUD Maintenance]
**Date:** 2026-08-20
**Description:** Created the `puroks` table as a child of `barangays` (cascade relationship). Puroks are scoped per barangay — the same purok name can exist in different barangays but must be unique within the same barangay. Supports prefix, ID sequence, and pay type for ID generation.
**Components Created:**
- `Purok` model — fillable, booted audit tracking, `belongsTo Barangay` relationship, `hasMany PurokLeader` relationship
- `Barangay` model — added `hasMany puroks()` relationship
- `App\Livewire\PurokMaintenance` — full CRUD with composite unique validation, barangay filter bar, soft delete
- `purok-maintenance.blade.php` — barangay filter dropdown, datatable with Barangay badge column, modal with Barangay parent dropdown
**New Migration:**
- `2026_06_18_000001_create_puroks_table` — creates `puroks` table with `barangay_id` FK (cascade delete), composite unique on `[barangay_id, name]`, full standard audit fields
**Routing:** Added `/purok` route with `PurokMaintenance::class`
**Key Implementation Details:**
- Unique validation uses `Rule::unique('puroks')->where('barangay_id', ...)` to scope uniqueness per barangay.
- Table filter bar allows filtering puroks by barangay without reloading the page (`wire:model.live`).
- `tables.md` updated with full `puroks` schema.

### [Feature: Purok Leader CRUD Maintenance]
**Date:** 2026-08-20
**Description:** Created the `purok_leaders` table as a child of `puroks` (cascade relationship). Each purok can have one leader with personal details, mobile number, date of birth, and civil status.
**Components Created:**
- `PurokLeader` model — fillable, booted audit tracking, `belongsTo Purok` relationship, `belongsTo CivilStatus` relationship
- `Purok` model — added `hasMany leaders()` relationship
- `App\Livewire\PurokLeaderMaintenance` — full CRUD with purok filter bar, civil status dropdown, soft delete
- `purok-leader-maintenance.blade.php` — purok filter dropdown, datatable with Purok/Barangay columns, modal with Purok parent dropdown and civil status selection
**New Migration:**
- `2026_06_18_000002_create_purok_leaders_table` — creates `purok_leaders` table with `purok_id` FK (cascade delete), `civil_status_id` FK, full standard audit fields
**Routing:** Added `/purok-leader` route with `PurokLeaderMaintenance::class`
**Key Implementation Details:**
- Uses `Purok::with('barangay')` eager loading to display "Purok Name (Barangay Name)" in the purok selector.
- Filter bar allows filtering leaders by purok without reloading the page (`wire:model.live`).
- `tables.md` updated with full `purok_leaders` schema.

---

## Important Implementation Notes
- **Audit Tracking Fields**: All future database tables for this application MUST include standard audit fields: `created_by`, `updated_by`, `created_at`, and `updated_at`. The user fields (`created_by`, `updated_by`) should be `unsignedBigInteger` foreign keys nullable mapping to `users.id`. These are auto-populated using Eloquent model `booted` events hooked to `creating` and `updating` via `auth()->id()`. JWT authentication is **fully integrated** — the native Laravel session (web guard) handles Livewire routing, while a JWT token is simultaneously issued as an HTTP-only cookie (`jwt_token`) for future API use. The `auth()->id() ?? 1` fallback in `booted()` now only applies to system-level operations (e.g., database seeding) where no authenticated session exists.
- **Toasts for Alerts**: Whenever saving or editing (or soft-deleting) records, the application standard is to dispatch a Livewire event (`$this->dispatch('notify', message: '...');`) rather than using static session flash alerts. A global Alpine.js component in `app.blade.php` automatically displays these as smooth-sliding Toast notifications.
- **Soft Deletes Only (Maintenance)**: For all library and master maintenance modules, we do NOT perform hard deletes (`->delete()`) on records. Instead, we perform "soft deletes" by toggling a status field (e.g., setting `is_active = false`) or moving it to a history state to preserve referential integrity.
- **Datatable Formatting Standard**: By default, primary key ID columns should be hidden from datatables for a cleaner user interface (unless explicitly requested).
- **CSS Compilation**: We are using Vite. After any changes to `app.css`, run `npm run build` to recompile the assets, as the layout relies on the compiled CSS file.
- **Dynamic Routing Approach**: Currently, the navigation utilizes standard Blade templates returned via closures in `web.php` as placeholders. As features are built out, these routes will be mapped to dedicated Livewire component classes (e.g., `App\Livewire\Transactions`).
- **Dropdown Menu Overflow**: The 3-column dropdown uses `column-count: 3;` and `break-inside: avoid;` to flow items top-to-bottom efficiently.

### [Feature: JWT Authentication]
- **Package**: Installed `tymon/jwt-auth ^2.3`. Published `config/jwt.php` and generated secret key.
- **User Model**: Implemented `JWTSubject` interface with `getJWTIdentifier()` and `getJWTCustomClaims()`.
- **Guard**: Added `jwt` guard in `config/auth.php`.
- **Middleware/Guard Strategy**: To maintain 100% compatibility with Livewire's internal request lifecycle and avoid `ERR_TOO_MANY_REDIRECTS` loops between `guest` and `auth` middleware, we use a hybrid approach. Laravel's native session handles the `web` guard routing, while the JWT is generated and queued into an HTTP-only cookie for future API use.
- **Login Flow**: Validates credentials, generates the JWT, queues it as `jwt_token` via `Cookie::queue()`, and logs the user in via native `auth()->login($user)`.
- **Logout**: Invalidates the JWT server-side, destroys the native session, and deletes the `jwt_token` cookie.
- **Routes**: Protected web routes use the standard `auth` middleware (relying on the native session), ensuring seamless integration with Livewire SPA navigation without custom JWT middleware conflicts.
