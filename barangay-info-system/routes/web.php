<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangayHealthController;
use App\Http\Controllers\ResidentPortalController;
use App\Models\Blotter;
use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Event;
use App\Models\Household;
use App\Models\Payment;
use App\Models\Purok;
use App\Models\Resident;


// Guest routes
$landingPage = function () {
    return view('home', [
        'stats' => [
            'residents' => Resident::count(),
            'households' => Household::count(),
            'puroks' => Purok::count(),
            'documents' => DocumentType::count(),
            'released_documents' => DocumentRequest::where('status', 'Released')->count(),
            'upcoming_events' => Event::whereIn('status', ['Upcoming', 'Ongoing'])->count(),
            'active_blotters' => Blotter::whereNotIn('status', ['Resolved', 'Closed'])->count(),
            'collections' => Payment::sum('amount'),
        ],
        'documentTypes' => DocumentType::orderBy('name')->limit(6)->get(),
        'upcomingEvents' => Event::whereIn('status', ['Upcoming', 'Ongoing'])
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->limit(3)
            ->get(),
    ]);
};

Route::get('/', $landingPage)->name('landing');
Route::get('/landing', $landingPage)->name('landing.page');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


// Admin login (only admin/staff roles)
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post');

// Resident portal login (only residents)
Route::get('/resident/login', [LoginController::class, 'showResidentLoginForm'])->name('resident.login');
Route::post('/resident/login', [LoginController::class, 'residentLogin'])->name('resident.login.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset (forgot password)
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('resident-portal')->name('resident-portal.')->middleware('role:Resident')->group(function () {
        Route::get('/', [ResidentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ResidentPortalController::class, 'profile'])->name('profile');
        Route::get('/requests', [ResidentPortalController::class, 'requests'])->name('requests');
        Route::get('/requests/create', [ResidentPortalController::class, 'createRequest'])->name('requests.create');
        Route::post('/requests', [ResidentPortalController::class, 'storeRequest'])->name('requests.store');
        Route::get('/events', [ResidentPortalController::class, 'events'])->name('events');
        Route::post('/events/{event}/register', [ResidentPortalController::class, 'registerEvent'])->name('events.register');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:Captain,Secretary,Treasurer,Staff');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:Captain,Secretary,Treasurer,Staff')->group(function () {

    // Resident Management
    Route::get('/residents/export', [ResidentController::class, 'export'])->name('residents.export');
    Route::resource('residents', ResidentController::class);


    // Household Management
    Route::resource('households', HouseholdController::class);
    
    // Document Management
    Route::resource('documents', DocumentController::class);
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::post('/documents/{document}/release', [DocumentController::class, 'release'])->name('documents.release');
    
    // Payment Management
    Route::get('/payments/receipt/{payment}', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{document}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
    
    // Event Management
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/duplicate', [EventController::class, 'duplicate'])->name('events.duplicate');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/attendance/{participant}', [EventController::class, 'markAttendance'])->name('events.attendance');
    
    // Health Center Visits
    Route::resource('health-visits', BarangayHealthController::class);

    // Blotter Management
    Route::resource('blotters', BlotterController::class);

    Route::post('/blotters/{blotter}/resolve', [BlotterController::class, 'resolve'])->name('blotters.resolve');
    Route::post('/blotters/{blotter}/update-status', [BlotterController::class, 'updateStatus'])->name('blotters.update-status');
    
    // User Management (Captain only)
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export')->middleware('role:Captain');
    Route::resource('users', UserController::class)->middleware('role:Captain');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Reports (Captain and Treasurer)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/population', [ReportController::class, 'population'])->name('population');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/documents', [ReportController::class, 'documents'])->name('documents');
        Route::get('/blotters', [ReportController::class, 'blotters'])->name('blotters');
        Route::get('/events', [ReportController::class, 'events'])->name('events');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });
    });
});
