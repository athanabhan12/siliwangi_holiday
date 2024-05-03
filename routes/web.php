<?php

use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PgiiController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\DaftarHadirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TourLeaderController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

// Route::get('/', function () {
//     return view('dashboard');
// });

//  jika user belum login
Route::group(['middleware' => 'guest'], function() {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'dologin']);

});

Route::get('/',                                   [LoginController::class,'index'])->name('login');

// untuk admin dan TourLeader
Route::group(['middleware' => ['auth', 'checkrole:1,2']], function() {
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/redirect', [RedirectController::class, 'cek']);
});

// untuk superadmin
Route::group(['middleware' => ['auth', 'checkrole:1']], function() {
Route::get('/dashboard',                          [DashboardController::class, 'index'])->name('index');
Route::get('/admin',                              [DashboardController::class, 'index'])->name('admin');
Route::get('/dashboard',                          [DashboardController::class, 'index'])->name('dashboard');

Route::get('/pelanggan',                          [PelangganController::class,'index'])->name('pelanggan');
Route::get('/peserta_pgii',                       [PelangganController::class,'index_pgii'])->name('pelanggan_pgii');
Route::get('/pelanggan/create',                   [PelangganController::class,'create']);
Route::post('/pelanggan/store',                   [PelangganController::class,'store']);
Route::get('/pelanggan/edit/{id}',                [PelangganController::class,'edit']);
Route::post('/pelanggan/update/{id}',             [PelangganController::class,'update']);
Route::get('/pelanggan/delete/{id}',              [PelangganController::class,'delete']);
Route::get('/pelanggan/detail/{id}',              [PelangganController::class,'detail']);
Route::post('/pelanggan/import',                  [PelangganController::class,'import'])->name('import');
Route::post('/pelanggan/import_pgii',             [PelangganController::class,'import_pgii'])->name('import_pgii');
Route::get('/pelanggan/pdf',                      [PelangganController::class,'pdf'])->name('pdf');

Route::get('/peserta_pgii',                       [PgiiController::class,'index_pgii'])->name('peserta_pgii');
Route::get('/peserta_pgii/create',                [PgiiController::class,'create']);
Route::post('/peserta_pgii/store',                [PgiiController::class,'store']);
Route::get('/peserta_pgii/edit/{id}',             [PgiiController::class,'edit']);
Route::post('/peserta_pgii/update/{id}',          [PgiiController::class,'update']);
Route::get('/peserta_pgii/delete/{id}',           [PgiiController::class,'delete']);
Route::get('/peserta_pgii/detail_pgii/{id}',      [PgiiController::class,'detail_pgii']);
Route::post('/peserta_pgii/import_pgii',          [PgiiController::class,'import_pgii'])->name('import_pgii');
Route::get('/peserta_pgii/pdf_pgii',              [PgiiController::class,'pdf_pgii'])->name('pdf_pgii');



Route::post('valdasi/qrcode',                     [RegistrasiController::class,'qrcode'])->name('qrcode');
Route::get('/registrasi/scan/{id}',               [RegistrasiController::class,'scan']);
Route::get('registrasi/detailscan',               [RegistrasiController::class,'detailscan'])->name('detailscan');
Route::get('/scan',                               [RegistrasiController::class,'handlescan'])->name('scan');


Route::get('/laporan_peserta',                    [LaporanController::class,'index_peserta'])->name('laporan_peserta');
Route::get('/laporan_data_tour',                  [LaporanController::class,'index_data_tour'])->name('laporan_data_tour');

Route::get('/panitia',                            [PanitiaController::class,'index'])->name('panitia');
Route::get('/panitia/create',                     [PanitiaController::class,'create']);
Route::post('/panitia/store',                     [PanitiaController::class,'store']);
Route::get('/panitia/edit/{id}',                  [PanitiaController::class,'edit']);
Route::post('/panitia/update/{id}',               [PanitiaController::class,'update']);
Route::get('/panitia/delete/{id}',                [PanitiaController::class,'delete']);
Route::get('/panitia/ubah_password/{id}',         [PanitiaController::class,'ubah_password']);
Route::post('/panitia/update_password/{id}',      [PanitiaController::class,'update_password']);



Route::get('/laporan_peserta/pdf_peserta',        [LaporanController::class,'pdf_peserta']);
});

// untuk TOUR LEADER
Route::group(['middleware' => ['auth', 'checkrole:2']], function() {
Route::get('/tourleader',                           [TourLeaderController::class, 'index'])->name('tourleader');
Route::get('/menu_pilihan',                         [TourLeaderController::class, 'index'])->name('menu_pilihan');
Route::get('/tourleader/create',                    [TourLeaderController::class, 'create'])->name('tourleader');
Route::post('/tourleader/store',                    [TourLeaderController::class, 'store'])->name('tourleader');

Route::get('/tour',                               [TourController::class,'index'])->name('tour');
Route::get('/tour/create',                        [TourController::class,'create']);
Route::post('/tour/store',                        [TourController::class,'store']);
Route::get('/tour/edit/{id}',                     [TourController::class,'edit']);
Route::post('/tour/update/{id}',                  [TourController::class,'update']);
Route::get('/tour/delete/{id}',                   [TourController::class,'delete']);
Route::get('/tour/detail/{id}',                   [TourController::class,'detail']);
Route::get('/daftar_hadir',                       [DaftarHadirController::class,'index'])->name('daftar_hadir');

});


Route::get('/daftar_hadir',                       [DaftarHadirController::class,'index'])->name('daftar_hadir');
Route::get('/daftar_hadir_pgii',                       [DaftarHadirController::class,'index_pgii'])->name('daftar_hadir_pgii');

Route::get('/tour',                               [TourController::class,'index'])->name('tour');
Route::get('/tour/create',                        [TourController::class,'create']);
Route::post('/tour/store',                        [TourController::class,'store']);
Route::get('/tour/edit/{id}',                     [TourController::class,'edit']);
Route::post('/tour/update/{id}',                  [TourController::class,'update']);
Route::get('/tour/delete/{id}',                   [TourController::class,'delete']);
Route::get('/tour/detail/{id}',                   [TourController::class,'detail']);


Route::get('/registrasi',                         [RegistrasiController::class,'index'])->name('registrasi');
Route::post('/registrasi/store',                  [RegistrasiController::class,'store']);
Route::get('/registrasi_pgii',                    [RegistrasiController::class,'index_pgii'])->name('registrasi_pgii');
Route::post('/registrasi/store_pgii',             [RegistrasiController::class,'store_pgii']);










