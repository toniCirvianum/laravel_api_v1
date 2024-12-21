<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('profile',[AuthController::class,'profile'])->name('profile');
    Route::post('logout',[AuthController::class,'logout'])->name('logout');

    Route::apiResource('tasks', TaskController::class);
    
});



Route::get('/hola-mon',function(){
    return 'Hola Mon!';
});

Route::get('/hola/{name}',function($name){
    return 'Hola '.$name.'!';
});

// Route::apiResource('tasks', TaskController::class);
