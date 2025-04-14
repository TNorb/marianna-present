<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

// Elérhető oldalak bejelentkezve, admin szerepkörrel
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->group(function () {
    Route::get('admin', [AdminController::class, 'admin'])->name('admin');
    Route::get('admin/items', [AdminController::class, 'admin'])->name('admin.items.index');
    Route::post('admin/items', [AdminController::class, 'store'])->name('admin.items.store');
    Route::get('admin/items/{item}/edit', [AdminController::class, 'edit'])->name('admin.items.edit');
    Route::put('admin/items/{item}', [AdminController::class, 'update'])->name('admin.items.update');
    Route::patch('admin/items/{item}/archive', [AdminController::class, 'archive'])->name('admin.items.archive');
    Route::get('admin/search', [AdminController::class, 'adminSearch'])->name('admin.search');
});


// Elérhető oldalak bejelentkezve, superadmin szerepkörrel
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'superadmin'])->group(function () {
    Route::get('admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('admin/users/search', [AdminController::class, 'searchUsers'])->name('admin.users.search');
    Route::patch('admin/users/{user}/archive', [AdminController::class, 'toggleArchiveUser'])->name('admin.users.archive');
    Route::patch('admin/users/{user}/role', [AdminController::class, 'toggleRole'])->name('admin.users.role');
});


// Elérhető oldalak bejelentkezve
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('cart', [CartController::class, 'cart'])->name('cart');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('checkout', [OrderController::class, 'placeOrder'])->name('placeOrder');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
});


// Elérhető oldalak login nélkül
Route::get('/', [ItemController::class, 'index'])->name('/');
Route::get('onsale', [ItemController::class, 'onsale'])->name('onsale');
Route::get('search', [ItemController::class, 'search'])->name('search');
Route::get('categories', [CategoryController::class, 'categories'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'search'])->name('categories.search');
Route::get('item/details/{id}', [ItemController::class, 'details'])->name('item.details');

Route::get('dashboard', [ItemController::class, 'dashboard'])->name('dashboard'); //?
Route::get('introduction', [ItemController::class, 'introduction'])->name('introduction');
Route::get('contact', [ItemController::class, 'contact'])->name('contact');


// Terms - összes feltétel és szabály az oldalhoz
Route::get('terms-and-conditions', [TermsController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('terms-of-purchase', [TermsController::class, 'termsOfPurchase'])->name('terms-of-purchase');
Route::get('shipping-informations', [TermsController::class, 'shippingInformations'])->name('shipping-informations');
Route::get('consumer-informations', [TermsController::class, 'consumerInformations'])->name('consumer-informations');
Route::get('declaration-of-withdrawal', [TermsController::class, 'declarationOfWithdrawal'])->name('declaration-of-withdrawal');
Route::get('privacy-policy', [TermsController::class, 'privacyPolicy'])->name('privacy-policy');


