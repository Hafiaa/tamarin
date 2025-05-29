<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

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

// Reservation Routes
Route::get('/reservations/create/{packageId?}', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

// Customer Dashboard Routes (Protected)
Route::middleware(['auth'])->prefix('dashboard')->name('customer.dashboard.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('index');
    
    // Reservations Management
    Route::get('/reservations', [CustomerDashboardController::class, 'reservations'])->name('reservations');
    Route::get('/reservations/{id}', [CustomerDashboardController::class, 'showReservation'])->name('reservations.show');
    
    // Payments Management
    Route::get('/payments', [CustomerDashboardController::class, 'payments'])->name('payments');
    Route::get('/payments/create/{reservationId}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/{reservationId}', [PaymentController::class, 'store'])->name('payments.store');
    
    // Testimonials Management
    Route::get('/testimonials', [CustomerDashboardController::class, 'testimonials'])->name('testimonials');
    Route::get('/testimonials/create/{reservationId}', [CustomerDashboardController::class, 'createTestimonial'])->name('testimonials.create');
    Route::post('/testimonials/{reservationId}', [CustomerDashboardController::class, 'storeTestimonial'])->name('testimonials.store');
    
    // Profile Management
    Route::get('/profile', [CustomerDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Filament Admin Panel Routes (must be last to avoid conflicts)
require __DIR__.'/filament.php';
