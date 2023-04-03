<?php
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserRequestController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth', 'is_admin')->group(function(){
    Route::resource('/item',InventoryController::class);
    Route::resource('/request',UserRequestController::class);
    Route::resource('/deleteditem',DeletedItemController::class);
});