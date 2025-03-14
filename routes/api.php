<?php

use App\Http\Controllers\API\ListingController;
use App\Http\Controllers\API\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Detail Login User',
        'data' => $request->user()
    ]);
});

Route::resource('Listing', ListingController::class)->only(['index','show']);

Route::Post('transaction/is-available', [TransactionController::class, 'isAvailable'])->middleware(['auth:sanctum']);
Route::resource('transaction', TransactionController::class)->only(['store', 'index', 'show'])->middleware(['auth:sanctum']);

require __DIR__. '/auth.php';