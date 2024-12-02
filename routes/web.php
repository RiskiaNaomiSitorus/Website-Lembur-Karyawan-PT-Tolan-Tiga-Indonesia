<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\PerhitunganLemburController;
use App\Http\Controllers\RekapitulasiJamLemburController;
use App\Exports\KaryawanExport;
use Maatwebsite\Excel\Facades\Excel;

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

// Authentication Routes
// Route for the root URL ('/') that points to the login method of AuthController
Route::get('/', [AuthController::class, 'login'])->name('login');

// Route to handle the login action, POST request
Route::post('actionlogin', [AuthController::class, 'actionlogin'])->name('actionlogin');

// Route to show the login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Route to handle user logout
Route::post('/logout', [AuthController::class, 'logout'])->name('actionlogout');

// Registration Routes
// Route to show the registration form
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route to handle registration form submission
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard Routes
// Route to the home page, which is controlled by the DashboardController
Route::get('home', [DashboardController::class, 'index'])->name('home')->middleware('auth');

// Data Karyawan Routes
// Route to view employee data
Route::get('data-karyawan', [DataKaryawanController::class, 'index'])->name('data-karyawan')->middleware('auth');

// Route to store new employee data
Route::post('store-karyawan', [DataKaryawanController::class, 'store'])->name('store-karyawan')->middleware('auth');

// Route to delete an employee record by ID
Route::delete('delete-karyawan/{id}', [DataKaryawanController::class, 'destroy'])->name('delete-karyawan')->middleware('auth');

// Route to update an existing employee record
Route::put('update-karyawan', [DataKaryawanController::class, 'update'])->name('update-karyawan')->middleware('auth');

// API Routes
// Route to get employee names for use in APIs
Route::get('/api/nama-karyawan', [DataKaryawanController::class, 'getNamaKaryawan'])->middleware('auth');

// Overtime Calculation Routes
// Route to view overtime calculation
// Route::get('perhitungan-lembur', [PerhitunganLemburController::class, 'index'])->name('perhitungan-lembur')->middleware('auth');

// Overtime Summary Routes
// Route to view overtime summary
Route::get('rekapitulasi-jam-lembur', [RekapitulasiJamLemburController::class, 'index'])->name('rekapitulasi-jam-lembur')->middleware('auth');

// Additional Route
// Route for the Lembur Index page
Route::get('/lembur', [PerhitunganLemburController::class, 'index'])->name('lembur.index')->middleware('auth');

// Add this to your web.php
Route::post('/store-lembur', [PerhitunganLemburController::class, 'store'])->name('store-lembur')->middleware('auth');

Route::delete('/lembur/{id}', [PerhitunganLemburController::class, 'destroy'])->name('lembur.destroy')->middleware('auth');

// In routes/web.php or routes/api.php
Route::get('autocomplete/id-karyawan', [DataKaryawanController::class, 'autocomplete'])->name('autocomplete.id_karyawan')->middleware('auth');

// In routes/web.php or routes/api.php
Route::get('autocomplete/nama-lengkap', [DataKaryawanController::class, 'autocompleteNama'])->name('autocomplete.nama_lengkap')->middleware('auth');

Route::get('/get-gaji', [DataKaryawanController::class, 'getGaji']);

// Route for updating lembur records
Route::put('/perhitungan-lembur/update', [PerhitunganLemburController::class, 'update'])->name('perhitungan-lembur.update')->middleware('auth');

// routes/web.php
Route::get('/export-excel', [PerhitunganLemburController::class, 'exportExcel'])->name('export.excel')->middleware('auth');


// Route for exporting data
Route::get('export-karyawan', [DataKaryawanController::class, 'export'])->name('karyawan.export')->middleware('auth');

// Add route for filtering lembur records
Route::get('perhitungan-lembur', [PerhitunganLemburController::class, 'index'])->name('perhitungan-lembur')->middleware('auth')->middleware('auth');

Route::get('/printable-view', [PerhitunganLemburController::class, 'printableView'])->name('printableView')->middleware('auth');

// Route for printing filtered data
Route::get('print-filtered-data', [RekapitulasiJamLemburController::class, 'printFilteredData'])->name('print.filtered.data')->middleware('auth');

// Route for exporting the filtered data to Excel
Route::get('/export-excel2', [RekapitulasiJamLemburController::class, 'exportExcel'])
    ->name('export.filtered.excel')->middleware('auth');
Route::post('rekapitulasi-jam-lembur', [RekapitulasiJamLemburController::class, 'index'])->name('rekapitulasi-jam-lembur')->middleware('auth')->middleware('auth');
