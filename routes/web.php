<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\InformasiGiziController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Makanan
Route::get('/makanan', [MakananController::class, 'index'])->name('makanan.index');
Route::get('/makanan/{id}', [MakananController::class, 'show'])->name('makanan.show');

// Analisis
Route::get('/analisis', [AnalisisController::class, 'index'])->name('analisis.index');
Route::post('/analisis/analyze', [AnalisisController::class, 'analyze'])->name('analisis.analyze');
Route::get('/analisis/result/{id}', [AnalisisController::class, 'result'])->name('analisis.result');
Route::get('/analisis/trace/{id}', [AnalisisController::class, 'trace'])->name('analisis.trace');

// Informasi Gizi
Route::get('/informasi-gizi', [InformasiGiziController::class, 'index'])->name('informasi-gizi.index');
Route::get('/informasi-gizi/{id}', [InformasiGiziController::class, 'show'])->name('informasi-gizi.show');
