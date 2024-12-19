<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/orders', 'AsiaYo\modules\OrderManaging\presentation\controllers\NormalizingOrderInfoController');
