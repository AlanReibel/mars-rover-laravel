<?php


use App\Http\Controllers\RoverController;
use Illuminate\Support\Facades\Route;

Route::post('/rover/move', [RoverController::class, 'move']);
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});