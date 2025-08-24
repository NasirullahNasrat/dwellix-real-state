<?php

use App\Http\Controllers\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingViewController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function ()
{
    return view('welcome');
})->name('home');

Route::middleware('auth')->prefix('dashboard')->group(function ()
{
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




// Web routes for the listing management interface
Route::prefix('listings')->name('listings.')->group(function () {
    Route::get('/manage', [ListingViewController::class, 'index'])->name('manage');
    Route::get('/create', [ListingViewController::class, 'create'])->name('create');
    Route::post('/store', [ListingViewController::class, 'store'])->name('store');
    Route::get('/{id}', [ListingViewController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ListingViewController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ListingViewController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [ListingViewController::class, 'destroy'])->name('destroy');
});
