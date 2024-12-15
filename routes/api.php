<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::apiResource('tasks', TaskController::class);
});



Route::get('/hola-mon',function(){
    return 'Hola Mon!';
});

Route::get('/hola/{name}',function($name){
    return 'Hola '.$name.'!';
});

Route::apiResource('tasks', TaskController::class);
