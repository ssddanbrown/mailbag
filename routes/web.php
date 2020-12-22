<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\LaunchSendController;
use App\Http\Controllers\MailListController;
use App\Http\Controllers\SendController;
use App\Http\Controllers\SignupConfirmationController;
use App\Http\Controllers\SignupController;
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


Route::get("/signup-confirm/{signupKey}", [SignupConfirmationController::class, 'show'])->name('signup.confirm.show');
Route::post("/signup-confirm/{signup:key}", [SignupConfirmationController::class, 'confirm'])->name('signup.confirm.confirm');
Route::get("/signup-confirm/{list:slug}/thanks", [SignupConfirmationController::class, 'thanks'])->name('signup.confirm.thanks');

Route::get('/signup/{list:slug}', [SignupController::class, 'show'])->name('signup.show');
Route::post('/signup/{list:slug}', [SignupController::class, 'signup'])->name('signup.signup');
Route::get('/signup/{list:slug}/thanks', [SignupController::class, 'thanks'])->name('signup.thanks');

Route::get('/unsubscribe/{key}', [])->name('unsubscribe.show');

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

    Route::put('/contacts/{contact}/lists', [ContactListController::class, 'add'])->name('contacts.lists.add');
    Route::delete('/contacts/{contact}/lists', [ContactListController::class, 'remove'])->name('contacts.lists.remove');

    Route::get('/lists', [MailListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [MailListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [MailListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{list}', [MailListController::class, 'edit'])->name('lists.edit');
    Route::put('/lists/{list}', [MailListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{list}', [MailListController::class, 'destroy'])->name('lists.destroy');

    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::get('/sends/create', [SendController::class, 'create'])->name('sends.create');
    Route::post('/sends', [SendController::class, 'store'])->name('sends.store');
    Route::get('/sends/{send}', [SendController::class, 'show'])->name('sends.show');
    Route::get('/sends/{send}/edit', [SendController::class, 'edit'])->name('sends.edit');
    Route::put('/sends/{send}', [SendController::class, 'update'])->name('sends.update');
    Route::delete('/sends/{send}', [SendController::class, 'destroy'])->name('sends.destroy');

    Route::post('/sends/{send}/launch', [LaunchSendController::class, 'launch'])->name('sends.launch');
});

require __DIR__.'/auth.php';
