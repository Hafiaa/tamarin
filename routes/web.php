<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CustomPackageController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// New Menu Page
Route::view('/new-menu', 'menu')->name('menu.new');

// Event Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{package:slug}', [EventController::class, 'show'])->name('events.show');

// Menu Routes
Route::get('/menu/cafe', [MenuController::class, 'cafeMenu'])->name('menu.cafe');
Route::get('/menu/event', [MenuController::class, 'eventMenu'])->name('menu.event');

// Company Profile Routes
Route::get('/about-us', [CompanyProfileController::class, 'about'])->name('company.about');
Route::get('/contact', [CompanyProfileController::class, 'contact'])->name('company.contact');
Route::post('/contact', [CompanyProfileController::class, 'sendContactForm'])->name('company.contact.send');
Route::get('/gallery', [CompanyProfileController::class, 'gallery'])->name('company.gallery');

// Blocked Dates Routes
Route::get('/blocked-dates', [ReservationController::class, 'getBlockedDates'])->name('blocked-dates.list');

// Reservation Routes
Route::middleware(['auth'])->group(function () {
    // These routes require authentication
    Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    
    // Reservation creation with auth check
    Route::get('/reservations/create/{packageId?}', [ReservationController::class, 'create'])
        ->name('reservations.create')
        ->middleware('auth');
});

// Public route that redirects to login if not authenticated
Route::get('/reservations/start/{packageId?}', [ReservationController::class, 'startReservation'])
    ->name('reservations.start');

// Custom Package Wizard Routes
Route::prefix('custom-package')->name('custom-package.')->group(function () {
    // Step 1: Event Details
    Route::get('/step1', [CustomPackageController::class, 'step1'])->name('step1');
    Route::post('/process-step1', [CustomPackageController::class, 'processStep1'])->name('process-step1');
    
    // Step 2: Service Selection
    Route::get('/step2', [CustomPackageController::class, 'step2'])->name('step2');
    Route::post('/process-step2', [CustomPackageController::class, 'processStep2'])->name('process-step2');
    
    // Step 3: Review & Submit
    Route::get('/step3', [CustomPackageController::class, 'step3'])->name('step3');
    Route::post('/store', [CustomPackageController::class, 'store'])->name('store');
    
    // Thank You Page
    Route::get('/thank-you/{reservation}', [CustomPackageController::class, 'thankYou'])->name('thank-you');
});

// Customer Dashboard Routes (Protected)
Route::middleware(['auth'])->prefix('dashboard')->name('customer.dashboard.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('index');
    
    // Reservations Management
    Route::get('/reservations', [CustomerDashboardController::class, 'reservations'])->name('reservations');
    Route::get('/reservations/{id}', [CustomerDashboardController::class, 'showReservation'])->name('reservations.show');
    Route::put('/reservations/{id}/cancel', [CustomerDashboardController::class, 'cancelReservation'])->name('reservations.cancel');
    
    // Payments Management
    Route::get('/payments', [CustomerDashboardController::class, 'payments'])->name('payments');
    Route::get('/payments/create/{id}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/{id}', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    
    // Testimonials Management
    Route::get('/testimonials', [CustomerDashboardController::class, 'testimonials'])->name('testimonials');
    Route::get('/testimonials/create/{reservationId}', [CustomerDashboardController::class, 'createTestimonial'])->name('testimonials.create');
    Route::post('/testimonials/{reservationId}', [CustomerDashboardController::class, 'storeTestimonial'])->name('testimonials.store');
    
    // Profile Management
    Route::get('/profile', [CustomerDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Password Update
    Route::put('/password', [CustomerDashboardController::class, 'updatePassword'])->name('password.update');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Filament Admin Panel Routes (must be last to avoid conflicts)
require __DIR__.'/filament.php';
