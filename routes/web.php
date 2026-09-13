<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $packages = \App\Models\Package::where('is_available', true)->take(3)->get();
    $galleries = \App\Models\Gallery::latest()->take(6)->get();
    return view('welcome', compact('packages', 'galleries'));
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/packages/{package:slug}/builder', [PackageController::class, 'builder'])->name('packages.builder');

Route::get('/packages', [PackageController::class, 'publicIndex'])->name('packages.index');
Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout & Orders
    Route::get('/checkout/summary/{package}', function(\App\Models\Package $package) {
        return redirect()->route('packages.show', $package);
    });
    Route::post('/checkout/summary/{package}', [OrderController::class, 'checkoutSummary'])->name('checkout.summary');
    Route::post('/checkout/process/{package}', [OrderController::class, 'checkout'])->name('checkout.process');
    Route::get('/my-orders', [OrderController::class, 'userOrders'])->name('user.orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
    Route::get('/orders/{order}/contract', [OrderController::class, 'downloadContract'])->name('orders.contract');
    Route::post('/payment/{payment}/snap', [OrderController::class, 'getPaymentSnap'])->name('payment.snap');
    Route::post('/midtrans/sync', [OrderController::class, 'syncStatus'])->name('midtrans.sync');
});

// API for Booked Dates
Route::get('/api/booked-dates', [OrderController::class, 'getBookedDates']);
Route::post('/api/promo/validate', [\App\Http\Controllers\PromoController::class, 'validatePromo']);

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('calendar');
    Route::resource('categories', CategoryController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('promos', \App\Http\Controllers\PromoController::class);
    // Package Components
    Route::post('packages/{package}/components', [\App\Http\Controllers\PackageComponentController::class, 'store'])->name('packages.components.store');
    Route::patch('components/{component}', [\App\Http\Controllers\PackageComponentController::class, 'update'])->name('components.update');
    Route::delete('components/{component}', [\App\Http\Controllers\PackageComponentController::class, 'destroy'])->name('components.destroy');
    
    Route::get('galleries', [GalleryController::class, 'adminIndex'])->name('galleries.index');
    Route::get('galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::get('galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
    Route::put('galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
    Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
    
    Route::get('orders', [OrderController::class, 'adminOrders'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/contract', [OrderController::class, 'downloadContract'])->name('orders.contract');

    Route::resource('users', \App\Http\Controllers\AdminUserController::class)->except(['show']);

    Route::get('inquiries', [\App\Http\Controllers\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [\App\Http\Controllers\InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}/read', [\App\Http\Controllers\InquiryController::class, 'markAsRead'])->name('inquiries.markAsRead');
    Route::delete('inquiries/{inquiry}', [\App\Http\Controllers\InquiryController::class, 'destroy'])->name('inquiries.destroy');
});

// Public Inquiry Post
Route::post('/inquiries', [\App\Http\Controllers\InquiryController::class, 'store'])->name('inquiries.store');

// Midtrans Callback (Public, No CSRF)
Route::post('/midtrans/callback', [OrderController::class, 'callback'])->name('midtrans.callback');

require __DIR__.'/auth.php';