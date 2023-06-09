<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\DeletedItemController;
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

Route::middleware('splade')->group(function () {
    
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['verified'])->name('dashboard');

        Route::resource('/item',InventoryController::class);
        Route::resource('/request',UserRequestController::class);
        Route::resource('/deleteditem',DeletedItemController::class);
        Route::get('/viewusers',[ProfileController::class, 'index']);
        Route::get('/viewuserrequest/{id}',[ProfileController::class, 'show'])->name('viewuser.request');

        //user view own request
        Route::get('/userrequest',[UserRequestController::class, 'userrequest'])->name('user.userrequest');

        //view available item
        Route::get('/availableitem',[InventoryController::class, 'availableitem'])->name('user.availableitem');

        //request the item -user
        Route::post('/requestitem',[UserRequestController::class, 'requestitem'])->name('user.request');



        

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
});
