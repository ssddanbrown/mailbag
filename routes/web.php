<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MailListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('/lists', [MailListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [MailListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [MailListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{list}', [MailListController::class, 'edit'])->name('lists.edit');
    Route::put('/lists/{list}', [MailListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{list}', [MailListController::class, 'destroy'])->name('lists.destroy');

});

require __DIR__.'/auth.php';
