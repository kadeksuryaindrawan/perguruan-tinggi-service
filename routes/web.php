<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryBantuanController;
use App\Http\Controllers\ProgramBantuanController;
use Illuminate\Http\Request;
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

Route::post('logout-form/{id}', [DashboardController::class, 'logout'])->name('logout_form');

Route::middleware(['web', 'auth.token'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/program-bantuan', [ProgramBantuanController::class, 'index']);

    Route::post('/rekomendasi-bantuan', [ProgramBantuanController::class, 'rekomendasi_bantuan'])->name('rekomendasi_bantuan');
    Route::get('/pilih-rekomendasi', [ProgramBantuanController::class, 'pilih_rekomendasi'])->name('pilih_rekomendasi');
    Route::post('/pilihrekomendasi/{id}', [ProgramBantuanController::class, 'pilih_process'])->name('pilih_process');

    Route::get('/history', [HistoryBantuanController::class, 'index'])->name('history');
    Route::get('/history/detail', [HistoryBantuanController::class, 'detail_data'])->name('detail-history');
    Route::get('/history/edit', [HistoryBantuanController::class, 'edit_data'])->name('edit-history');
    Route::put('/history/edit/{id}', [HistoryBantuanController::class, 'edit_process'])->name('edit-history-process');
    Route::delete('/history/delete/{id}', [HistoryBantuanController::class, 'delete_process'])->name('delete-process');
});

