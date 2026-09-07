<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Dashboard;
use App\Livewire\Settings;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CivilStatusMaintenance;
use App\Livewire\GenderMaintenance;
use App\Livewire\OfficeMaintenance;
use App\Livewire\AccessLevelMaintenance;
use App\Livewire\UserMaintenance;
use App\Livewire\PositionMaintenance;
use App\Livewire\PurokMaintenance;
use App\Livewire\PurokLeaderMaintenance;

// ─── Guest Routes (unauthenticated only) ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// ─── Public Routes (No Auth Required) ─────────────────────────────────────────
Route::get('/verify/{token}', \App\Livewire\ScVerification::class)->name('verify');
Route::get('/guest-chat', \App\Livewire\GuestChat::class)->name('guest.chat');
Route::get('/contact', \App\Livewire\ContactUs::class)->name('contact');

// ─── Logout ───────────────────────────────────────────────────────────────────
Route::post('/logout', function () {
    // Invalidate the JWT token if present
    $token = request()->cookie('jwt_token');
    if ($token) {
        try {
            \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->invalidate();
        } catch (\Exception $e) {
            // Token already expired — safe to ignore
        }
    }

    // Destroy the web session
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    // Delete the JWT cookie
    return redirect('/login')->withCookie(cookie()->forget('jwt_token'));
})->name('logout');

// ─── Authenticated Routes (JWT Protected) ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class);
    Route::get('/settings', Settings::class);

    // Transactions module

    Route::get('/transactions',          fn() => view('pages.transactions'));
    Route::get('/personal-information',  \App\Livewire\PersonalInformation::class)->name('personal-information');
    
    // Print ID endpoint
    Route::get('/print/id/{id}', [\App\Http\Controllers\PrintController::class, 'printId'])->name('print.id');

    Route::get('/payroll',          \App\Livewire\Payroll::class);
        Route::get('/print/payroll/{funding_id}', [App\Http\Controllers\PrintController::class, 'printPayroll'])->name('print.payroll');
        Route::get('/print/payroll-stub/{funding_id}/{senior_citizen_id}', [App\Http\Controllers\PrintController::class, 'printPayrollClaimStub'])->name('print.payroll.stub');
    Route::get('/reports',          fn() => view('pages.reports'));
    Route::get('/messages',         \App\Livewire\AdminChat::class)->name('messages');
    Route::get('/payroll-provider', \App\Livewire\PayrollProviderMaintenance::class);
    Route::get('/funding',          \App\Livewire\FundingMaintenance::class);
    Route::get('/location',         \App\Livewire\LocationMaintenance::class);
    Route::get('/quad1-parameter',  fn() => view('pages.quad1-parameter'));
    Route::get('/quad2-parameter',  fn() => view('pages.quad2-parameter'));
    Route::get('/quad3-parameter',  fn() => view('pages.quad3-parameter'));
    Route::get('/quad4-parameter',  fn() => view('pages.quad4-parameter'));
    Route::get('/f-consideration',  \App\Livewire\FactorConsiderationMaintenance::class);
    Route::get('/f-scoring',        \App\Livewire\ConsiderationScoringDetailMaintenance::class);
    Route::get('/diagnosis',        \App\Livewire\DiagnosisMaintenance::class);
    Route::get('/pension-source',   \App\Livewire\PensionSourceMaintenance::class);
    Route::get('/regular-income',   \App\Livewire\RegularIncomeMaintenance::class);
    Route::get('/other-income',     \App\Livewire\OtherIncomeMaintenance::class);
    Route::get('/place-of-living',  \App\Livewire\PlaceOfLivingMaintenance::class);
    Route::get('/living-condition', \App\Livewire\LivingConditionMaintenance::class);
    Route::get('/barangay',         \App\Livewire\BarangayMaintenance::class);
    Route::get('/purok',          \App\Livewire\PurokMaintenance::class);
    Route::get('/purok-leader',   \App\Livewire\PurokLeaderMaintenance::class);
    Route::get('/nationality',      \App\Livewire\NationalityMaintenance::class);
    Route::get('/position',         PositionMaintenance::class);

    // CRUD Maintenance Modules
    Route::get('/civil-status',  CivilStatusMaintenance::class);
    Route::get('/gender',        GenderMaintenance::class);
    Route::get('/office',        OfficeMaintenance::class);
    Route::get('/access-level',  AccessLevelMaintenance::class);
    Route::get('/users',         UserMaintenance::class);
    Route::get('/province',      \App\Livewire\ProvinceMaintenance::class);
    Route::get('/city',          \App\Livewire\CityMaintenance::class);
});
