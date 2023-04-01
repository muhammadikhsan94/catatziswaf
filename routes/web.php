<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/', function() {
	return view('auth.login');
});

Route::get('/daftar', [App\Http\Controllers\RegisterController::class, 'daftar'])->name('daftar');
Route::post('/daftar/simpan', [App\Http\Controllers\RegisterController::class, 'simpanRegister'])->name('simpanRegister');

Auth::routes();

// Auth::routes(['verify' => true]);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

	Route::get('/user', function() {
		return view('home');
	});

	Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
	Route::get('/profil', [App\Http\Controllers\HomeController::class, 'profil'])->name('profil');
	Route::post('/profil/update', [App\Http\Controllers\HomeController::class, 'updateProfil'])->name('updateProfil');
	Route::get('cetak', [App\Http\Controllers\HomeController::class, 'buatSuratTugas'])->name('buatSuratTugas');
	Route::get('faq', [App\Http\Controllers\HomeController::class, 'faq'])->name('faq');

    Route::group(['prefix' => 'duta', 'middleware' => ['dutazakat']], function() {
		Route::get('/', [App\Http\Controllers\DutaZakatController::class, 'index'])->name('duta.beranda');
		Route::post('/getrekening', [App\Http\Controllers\DutaZakatController::class, 'getRekening'])->name('duta.rekening');

		//TRANSAKSI
		Route::get('/transaksi', [App\Http\Controllers\DutaZakatController::class, 'getTransaksi'])->name('duta.transaksi');
		Route::get('/transaksi/getdata', [App\Http\Controllers\DutaZakatController::class, 'getDataTransaksi'])->name('duta.getTransaksi');
		Route::get('/transaksi/tambah', [App\Http\Controllers\DutaZakatController::class, 'tambahTransaksi'])->name('duta.tambahTransaksi');
		Route::post('/transaksi/simpan', [App\Http\Controllers\DutaZakatController::class, 'simpanTransaksi'])->name('duta.simpanTransaksi');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\DutaZakatController::class, 'detailTransaksi'])->name('duta.detailTransaksi');
		Route::delete('/transaksi/delete/{id}', [App\Http\Controllers\DutaZakatController::class, 'deleteTransaksi'])->name('duta.deleteTransaksi');
		Route::get('/transaksi/edit/{id}', [App\Http\Controllers\DutaZakatController::class, 'editTransaksi'])->name('duta.editTransaksi');
		Route::post('/transaksi/edit/update', [App\Http\Controllers\DutaZakatController::class, 'updateTransaksi'])->name('duta.updateTransaksi');
		Route::get('/rekening/lembaga/{id}', [App\Http\Controllers\DutaZakatController::class, 'getRekening'])->name('duta.getRekening');
		Route::get('/transaksi/bukti/{id}', [App\Http\Controllers\DutaZakatController::class, 'cetakBukti'])->name('duta.cetakBukti');

		//DONATUR
		Route::get('/donatur', [App\Http\Controllers\DutaZakatController::class, 'getDonatur'])->name('duta.donatur');
		Route::post('/donatur/simpan', [App\Http\Controllers\DutaZakatController::class, 'simpanDonatur'])->name('duta.simpanDonatur');
		Route::get('/donatur/getdata', [App\Http\Controllers\DutaZakatController::class, 'getDataDonatur'])->name('duta.getDonatur');
		Route::get('/donatur/detail/{id}', [App\Http\Controllers\DutaZakatController::class, 'detailDonatur'])->name('duta.detailDonatur');
		Route::get('/donatur/edit/{id}', [App\Http\Controllers\DutaZakatController::class, 'editDonatur'])->name('duta.editDonatur');
		Route::post('/donatur/edit/update', [App\Http\Controllers\DutaZakatController::class, 'updateDonatur'])->name('duta.updateDonatur');
		Route::get('/donatur/{id}', [App\Http\Controllers\DutaZakatController::class, 'createPlan'])->name('duta.createPlan');
		Route::post('/donatur/update', [App\Http\Controllers\DutaZakatController::class, 'savePlan'])->name('duta.savePlan');

		//PROFIL
		Route::get('/profil', [App\Http\Controllers\DutaZakatController::class, 'editProfil'])->name('duta.editProfil');
		Route::post('/profil/update', [App\Http\Controllers\DutaZakatController::class, 'updateProfil'])->name('duta.updateProfil');

		//PERENCANAAN
		Route::get('/perencanaan/delete/{id}', [App\Http\Controllers\DutaZakatController::class, 'deletePlan'])->name('duta.deletePlan');

		//LAPORAN
		Route::get('/laporan', [App\Http\Controllers\DutaZakatController::class, 'getLaporan'])->name('duta.laporan');
		Route::get('/laporan/transaksi', [App\Http\Controllers\DutaZakatController::class, 'getDataLaporanRincian'])->name('duta.getDataLaporanRincian');
    });

    Route::group(['prefix' => 'manajer', 'middleware' => ['manajer']], function() {
    	Route::get('/', [App\Http\Controllers\ManajerController::class, 'index'])->name('manajer.beranda');

    	//USER
    	Route::get('/user', [App\Http\Controllers\ManajerController::class, 'getUser'])->name('manajer.user');
    	Route::get('/user/getdata', [App\Http\Controllers\ManajerController::class, 'getDataUser'])->name('manajer.getUser');
		Route::get('/user/detail/{id}', [App\Http\Controllers\ManajerController::class, 'detailUser'])->name('manajer.detailUser');

		//TRANSAKSI
    	Route::post('/transaksi/simpan', [App\Http\Controllers\ManajerController::class, 'ubahStatus'])->name('manajer.ubahStatus');
		Route::get('/transaksi', [App\Http\Controllers\ManajerController::class, 'getTransaksi'])->name('manajer.transaksi');
		Route::get('/transaksi/getdata', [App\Http\Controllers\ManajerController::class, 'getDataTransaksi'])->name('manajer.getTransaksi');
		Route::get('/transaksi/{id}', [App\Http\Controllers\ManajerController::class, 'getStatus'])->name('manajer.getStatus');
		Route::post('/transaksi/update', [App\Http\Controllers\ManajerController::class, 'updateStatus'])->name('manajer.updateStatus');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\ManajerController::class, 'detailTransaksi'])->name('manajer.detailTransaksi');

		//LAPORAN
		Route::get('/laporan', [App\Http\Controllers\ManajerController::class, 'getLaporan'])->name('manajer.laporan');
		Route::get('/laporan/transaksi/realisasi', [App\Http\Controllers\ManajerController::class, 'getDataLaporanRealisasi'])->name('manajer.getDataLaporanRealisasi');

		//DONATUR
		Route::get('/donatur', [App\Http\Controllers\ManajerController::class, 'getDonatur'])->name('manajer.donatur');
		Route::post('/donatur/simpan', [App\Http\Controllers\ManajerController::class, 'simpanDonatur'])->name('manajer.simpanDonatur');
		Route::get('/donatur/getdata', [App\Http\Controllers\ManajerController::class, 'getDataDonatur'])->name('manajer.getDonatur');
		Route::get('/donatur/detail/{id}', [App\Http\Controllers\ManajerController::class, 'detailDonatur'])->name('manajer.detailDonatur');
		Route::get('/donatur/edit/{id}', [App\Http\Controllers\ManajerController::class, 'editDonatur'])->name('manajer.editDonatur');
		Route::post('/donatur/edit/update', [App\Http\Controllers\ManajerController::class, 'updateDonatur'])->name('manajer.updateDonatur');

    });

    Route::group(['prefix' => 'manajerarea', 'middleware' => ['manajerarea']], function() {
    	Route::get('/', [App\Http\Controllers\ManajerAreaController::class, 'index'])->name('manajerarea.beranda');

    	//TRANSAKSI
    	Route::get('/transaksi', [App\Http\Controllers\ManajerAreaController::class, 'getTransaksi'])->name('manajerarea.transaksi');
    	Route::get('/transaksi/getdata', [App\Http\Controllers\ManajerAreaController::class, 'getDataTransaksi'])->name('manajerarea.getTransaksi');
    	Route::get('/transaksi/{id}', [App\Http\Controllers\ManajerAreaController::class, 'getStatus'])->name('spv.getStatus');
		Route::post('/transaksi/update', [App\Http\Controllers\ManajerAreaController::class, 'updateStatus'])->name('manajerarea.updateStatus');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\ManajerAreaController::class, 'detailTransaksi'])->name('manajerarea.detailTransaksi');

		//LAPORAN
		Route::get('/laporan/realisasi', [App\Http\Controllers\ManajerAreaController::class, 'getLaporanRealisasi'])->name('manajerarea.laporanRealisasi');
		Route::get('/laporan/realisasi/getdata', [App\Http\Controllers\ManajerAreaController::class, 'getDataLaporanRealisasi'])->name('manajerarea.getDataLaporanRealisasi');
    });

    Route::group(['prefix' => 'panzisda', 'middleware' => ['panzisda']], function() {
    	Route::get('/', [App\Http\Controllers\PanzisdaController::class, 'index'])->name('panzisda.beranda');

    	//USER
		Route::get('/user', [App\Http\Controllers\PanzisdaController::class, 'getUser'])->name('panzisda.user');
		Route::get('/user/tambah', [App\Http\Controllers\PanzisdaController::class, 'tambahUser'])->name('panzisda.tambahUser');
		Route::get('/user/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataUser'])->name('panzisda.getUser');
		Route::post('/user/simpan', [App\Http\Controllers\PanzisdaController::class, 'simpanUser'])->name('panzisda.simpanUser');
		Route::get('/user/edit/{id}', [App\Http\Controllers\PanzisdaController::class, 'editUser'])->name('panzisda.editUser');
		Route::post('/user/edit/update', [App\Http\Controllers\PanzisdaController::class, 'updateUser'])->name('panzisda.updateUser');
		Route::delete('/user/delete/{id}', [App\Http\Controllers\PanzisdaController::class, 'deleteUser'])->name('panzisda.deleteUser');
		Route::post('/user/reset', [App\Http\Controllers\PanzisdaController::class, 'resetPassword'])->name('panzisda.resetPassword');

		//TRANSAKSI
    	Route::post('/transaksi/simpan', [App\Http\Controllers\PanzisdaController::class, 'ubahStatus'])->name('panzisda.ubahStatus');
		Route::get('/transaksi', [App\Http\Controllers\PanzisdaController::class, 'getTransaksi'])->name('panzisda.transaksi');
		Route::get('/transaksi/getdata/{id}', [App\Http\Controllers\PanzisdaController::class, 'getDataTransaksi'])->name('panzisda.getTransaksi');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\PanzisdaController::class, 'detailTransaksi'])->name('panzisda.detailTransaksi');
		Route::get('/transaksi/{id}', [App\Http\Controllers\PanzisdaController::class, 'getStatus'])->name('panzisda.getStatus');
		Route::post('/transaksi/update', [App\Http\Controllers\PanzisdaController::class, 'updateStatus'])->name('panzisda.updateStatus');

		//DONATUR
		Route::get('/donatur', [App\Http\Controllers\PanzisdaController::class, 'getDonatur'])->name('panzisda.donatur');
		Route::get('/donatur/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataDonatur'])->name('panzisda.getDonatur');
		Route::get('/donatur/detail/{id}', [App\Http\Controllers\PanzisdaController::class, 'detailDonatur'])->name('panzisda.detailDonatur');

		Route::get('/profil', [App\Http\Controllers\PanzisdaController::class, 'editProfil'])->name('panzisda.editProfil');
		Route::post('/profil/update', [App\Http\Controllers\PanzisdaController::class, 'updateProfil'])->name('panzisda.updateProfil');
		
		//LAPORAN
		Route::get('/laporan/dutazakat', [App\Http\Controllers\PanzisdaController::class, 'getLaporanDZ'])->name('panzisda.laporanDZ');
		Route::get('/laporan/realisasi', [App\Http\Controllers\PanzisdaController::class, 'getLaporanRealisasi'])->name('panzisda.laporanRealisasi');
		Route::get('/laporan/validasi', [App\Http\Controllers\PanzisdaController::class, 'getLaporanValidasi'])->name('panzisda.laporanValidasi');
		Route::get('/laporan/rekonsiliasi', [App\Http\Controllers\PanzisdaController::class, 'getLaporanRekonsiliasi'])->name('panzisda.laporanRekonsiliasi');
		Route::get('/laporan/dutazakat/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanDZ'])->name('panzisda.getDataLaporanDZ');
		Route::get('/laporan/validasi/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanValidasi'])->name('panzisda.getDataLaporanValidasi');
		Route::get('/laporan/realisasi/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanRealisasi'])->name('panzisda.getDataLaporanRealisasi');
		Route::get('/laporan/rekonsiliasi/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanRekonsiliasi'])->name('panzisda.getDataLaporanRekonsiliasi');
		Route::get('/laporan/realisasi-paketziswaf', [App\Http\Controllers\PanzisdaController::class, 'getLaporanRealisasiPaketZiswaf'])->name('panzisda.laporanRealisasiPaketZiswaf');
		Route::get('/laporan/realisasi-paketziswaf/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanRealisasiPaketZiswaf'])->name('panzisda.getDataLaporanRealisasiPaketZiswaf');
		Route::get('/laporan/distribusi', [App\Http\Controllers\PanzisdaController::class, 'getLaporanDistribusi'])->name('panzisda.laporanRealisasiDistribusi');
		Route::get('/laporan/distribusi/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanDistribusi'])->name('panzisda.getDataLaporanDistribusi');
		Route::get('/laporan/donatur', [App\Http\Controllers\PanzisdaController::class, 'getLaporanDonatur'])->name('panzisda.laporanDonatur');
		Route::get('/laporan/donatur/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanDonatur'])->name('panzisda.getDataLaporanDonatur');
		Route::get('/laporan/realisasi-paketziswaf-dutazakat', [App\Http\Controllers\PanzisdaController::class, 'getLaporanRealisasiDutaZakat'])->name('panzisda.laporanRealisasiDutaZakat');
		Route::get('/laporan/realisasi-paketziswaf-dutazakat/getdata', [App\Http\Controllers\PanzisdaController::class, 'getDataLaporanRealisasiDutaZakat'])->name('panzisda.getDataLaporanRealisasiDutaZakat');
    });

    Route::group(['prefix' => 'panziswil', 'middleware' => ['panziswil']], function() {
    	Route::get('/', [App\Http\Controllers\PanziswilController::class, 'index'])->name('panziswil.beranda');

    	//USER
    	Route::get('/user', [App\Http\Controllers\PanziswilController::class, 'getUser'])->name('panziswil.user');
		Route::get('/user/tambah', [App\Http\Controllers\PanziswilController::class, 'tambahUser'])->name('panziswil.tambahUser');
		Route::get('/user/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataUser'])->name('panziswil.getUser');
		Route::post('/user/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanUser'])->name('panziswil.simpanUser');
		Route::get('/user/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editUser'])->name('panziswil.editUser');
		Route::post('/user/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateUser'])->name('panziswil.updateUser');
		Route::delete('/user/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteUser'])->name('panziswil.deleteUser');
		Route::post('/user/import_excel', [App\Http\Controllers\PanziswilController::class, 'import_excel'])->name('panziswil.import');
		Route::get('/user/export_excel', [App\Http\Controllers\PanziswilController::class, 'export_excel'])->name('panziswil.export');
		Route::post('/user/reset', [App\Http\Controllers\PanziswilController::class, 'resetPassword'])->name('panziswil.resetPassword');

		//TRANSAKSI
		Route::get('/transaksi', [App\Http\Controllers\PanziswilController::class, 'getTransaksi'])->name('panziswil.transaksi');
		Route::get('/transaksi/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataTransaksi'])->name('panziswil.getTransaksi');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\PanziswilController::class, 'detailTransaksi'])->name('panziswil.detailTransaksi');
		Route::get('/transaksi/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editTransaksi'])->name('panziswil.editTransaksi');
		Route::post('/transaksi/edit/update', [App\Http\Controllers\PanziswilController::class, 'updateTransaksi'])->name('panziswil.updateTransaksi');
		Route::get('/rekening/lembaga/{id}', [App\Http\Controllers\PanziswilController::class, 'getRekening'])->name('panziswil.getRekening');
		Route::get('/transaksi/{id}', [App\Http\Controllers\PanziswilController::class, 'getStatus'])->name('panziswil.getStatus');
		Route::post('/transaksi/update', [App\Http\Controllers\PanziswilController::class, 'updateStatus'])->name('panziswil.updateStatus');
		Route::delete('/transaksi/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteTransaksi'])->name('panziswil.deleteTransaksi');
		
		//PROFIL
		Route::get('/profil', [App\Http\Controllers\PanziswilController::class, 'editProfil'])->name('panziswil.editProfil');
		Route::post('/profil/update', [App\Http\Controllers\PanziswilController::class, 'updateProfil'])->name('panziswil.updateProfil');

		//WILAYAH
		Route::get('/wilayah', [App\Http\Controllers\PanziswilController::class, 'getWilayah'])->name('panziswil.wilayah');
		Route::get('/wilayah/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataWilayah'])->name('panziswil.getWilayah');
		Route::post('/wilayah/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanWilayah'])->name('panziswil.simpanWilayah');
		Route::delete('/wilayah/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteWilayah'])->name('panziswil.deleteWilayah');
		Route::get('/wilayah/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editWilayah'])->name('panziswil.editWilayah');
		Route::post('/wilayah/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateWilayah'])->name('panziswil.updateWilayah');

		//LEMBAGA
		Route::get('/lembaga', [App\Http\Controllers\PanziswilController::class, 'getLembaga'])->name('panziswil.lembaga');
		Route::get('/lembaga/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataLembaga'])->name('panziswil.getLembaga');
		Route::post('/lembaga/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanLembaga'])->name('panziswil.simpanLembaga');
		Route::delete('/lembaga/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteLembaga'])->name('panziswil.deleteLembaga');
		Route::get('/lembaga/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editLembaga'])->name('panziswil.editLembaga');
		Route::post('/lembaga/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateLembaga'])->name('panziswil.updateLembaga');

		//PAKET
		Route::get('/paket', [App\Http\Controllers\PanziswilController::class, 'getPaket'])->name('panziswil.paket');
		Route::get('/paket/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataPaket'])->name('panziswil.getPaket');
		Route::post('/paket/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanPaket'])->name('panziswil.simpanPaket');
		Route::delete('/paket/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deletePaket'])->name('panziswil.deletePaket');
		Route::get('/paket/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editPaket'])->name('panziswil.editPaket');
		Route::post('/paket/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updatePaket'])->name('panziswil.updatePaket');

		//JENIS TRANSAKSI
		Route::get('/jenis-transaksi', [App\Http\Controllers\PanziswilController::class, 'getJenisTransaksi'])->name('panziswil.jenisTransaksi');
		Route::get('/jenis-transaksi/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataJenisTransaksi'])->name('panziswil.getDataJenisTransaksi');
		Route::get('/jenis-transaksi/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editJenisTransaksi'])->name('panziswil.editJenisTransaksi');
		Route::post('/jenis-transaksi/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateJenisTransaksi'])->name('panziswil.updateJenisTransaksi');
		Route::post('/jenis-transaksi/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanJenisTransaksi'])->name('panziswil.simpanJenisTransaksi');
		Route::delete('/jenis-transaksi/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteJenisTransaksi'])->name('panziswil.deleteJenisTransaksi');

		//DISTRIBUSI
		Route::get('/distribusi', [App\Http\Controllers\PanziswilController::class, 'getDistribusi'])->name('panziswil.distribusi');
		Route::get('/distribusi/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataDistribusi'])->name('panziswil.getDataDistribusi');
		Route::get('/distribusi/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editDistribusi'])->name('panziswil.editDistribusi');
		Route::post('/distribusi/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateDistribusi'])->name('panziswil.updateDistribusi');
		Route::post('/distribusi/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanDistribusi'])->name('panziswil.simpanDistribusi');
		Route::delete('/distribusi/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteDistribusi'])->name('panziswil.deleteDistribusi');

		//REKENING LEMBAGA
		Route::get('/rekening-lembaga', [App\Http\Controllers\PanziswilController::class, 'getRekeningLembaga'])->name('panziswil.rekeningLembaga');
		Route::get('/rekening-lembaga/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataRekeningLembaga'])->name('panziswil.getDataRekeningLembaga');
		Route::get('/rekening-lembaga/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editRekeningLembaga'])->name('panziswil.editRekeningLembaga');
		Route::post('/rekening-lembaga/edit/simpan', [App\Http\Controllers\PanziswilController::class, 'updateRekeningLembaga'])->name('panziswil.updateRekeningLembaga');
		Route::post('/rekening-lembaga/simpan', [App\Http\Controllers\PanziswilController::class, 'simpanRekeningLembaga'])->name('panziswil.simpanRekeningLembaga');
		Route::delete('/rekening-lembaga/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteRekeningLembaga'])->name('panziswil.deleteRekeningLembaga');
		
		//GROUP
		Route::get('/group', [App\Http\Controllers\PanziswilController::class, 'getGroup'])->name('panziswil.group');
		Route::get('/group/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataGroup'])->name('panziswil.getGroup');
		Route::get('/group/edit/{id}', [App\Http\Controllers\PanziswilController::class, 'editGroup'])->name('panziswil.editGroup');
		Route::post('/group/edit/update', [App\Http\Controllers\PanziswilController::class, 'updateGroup'])->name('panziswil.updateGroup');
		Route::delete('/group/delete/{id}', [App\Http\Controllers\PanziswilController::class, 'deleteGroup'])->name('panziswil.deleteGroup');
		
		//LAPORAN
		Route::get('/laporan/dutazakat', [App\Http\Controllers\PanziswilController::class, 'getLaporanDZ'])->name('panziswil.laporanDZ');
		Route::get('/laporan/wilayah', [App\Http\Controllers\PanziswilController::class, 'getLaporanWilayah'])->name('panziswil.laporanWilayah');
		Route::get('/laporan/validasi/wilayah', [App\Http\Controllers\PanziswilController::class, 'getLaporanValidasiWilayah'])->name('panziswil.laporanValidasiWilayah');
		Route::get('/laporan/validasi/lembaga', [App\Http\Controllers\PanziswilController::class, 'getLaporanValidasiLembaga'])->name('panziswil.laporanValidasiLembaga');
		Route::get('/laporan/jenisziswaf', [App\Http\Controllers\PanziswilController::class, 'getLaporJenisZiswaf'])->name('panziswil.laporanJenisZiswaf');
		Route::get('/laporan/dutazakat/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanDZ'])->name('panziswil.getDataLaporanDZ');
		Route::get('/laporan/validasi/wilayah/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanValidasiWilayah'])->name('panziswil.getDataLaporanValidasiWilayah');
		Route::get('/laporan/validasi/lembaga/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanValidasiLembaga'])->name('panziswil.getDataLaporanValidasiLembaga');
		Route::get('/laporan/jenisziswaf/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanJenisZiswaf'])->name('panziswil.getDataLaporanJenisZiswaf');
		Route::get('/laporan/wilayah/getdata', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanWilayah'])->name('panziswil.getDataLaporanWilayah');
		Route::get('/laporan/realisasi-paketziswaf', [App\Http\Controllers\PanziswilController::class, 'getLaporanRealisasiPaketZiswaf'])->name('panziswil.laporanRealisasiPaketZiswaf');
		Route::get('/laporan/realisasi-paketziswaf/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanRealisasiPaketZiswaf'])->name('panziswil.getDataLaporanRealisasiPaketZiswaf');
		Route::get('/laporan/distribusi', [App\Http\Controllers\PanziswilController::class, 'getLaporanRealisasiDistribusi'])->name('panziswil.laporanRealisasiDistribusi');
		Route::get('/laporan/distribusi/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanRealisasiDistribusi'])->name('panziswil.getDataLaporanRealisasiDistribusi');
		Route::get('/laporan/donatur', [App\Http\Controllers\PanziswilController::class, 'getLaporanDonatur'])->name('panziswil.laporanDonatur');
		Route::get('/laporan/donatur/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanDonatur'])->name('panziswil.getDataLaporanDonatur');
		Route::get('/laporan/realisasi-paketziswaf-dutazakat', [App\Http\Controllers\PanziswilController::class, 'getLaporanRealisasiDutaZakat'])->name('panziswil.laporanRealisasiDutaZakat');
		Route::get('/laporan/realisasi-paketziswaf-dutazakat/getdata/{id}', [App\Http\Controllers\PanziswilController::class, 'getDataLaporanRealisasiDutaZakat'])->name('panziswil.getDataLaporanRealisasiDutaZakat');
    });

	Route::group(['prefix' => 'lazis', 'middleware' => ['lazis']], function() {
    	Route::get('/', [App\Http\Controllers\LazisController::class, 'index'])->name('lazis.beranda');

		//TRANSAKSI
    	Route::post('/transaksi/simpan', [App\Http\Controllers\LazisController::class, 'ubahStatus'])->name('lazis.ubahStatus');
		Route::get('/transaksi', [App\Http\Controllers\LazisController::class, 'getTransaksi'])->name('lazis.transaksi');
		Route::get('/transaksi/getdata/{id}', [App\Http\Controllers\LazisController::class, 'getDataTransaksi'])->name('lazis.getTransaksi');
		Route::get('/transaksi/{id}', [App\Http\Controllers\LazisController::class, 'getStatus'])->name('lazis.getStatus');
		Route::post('/transaksi/update', [App\Http\Controllers\LazisController::class, 'updateStatus'])->name('lazis.updateStatus');
		Route::get('/transaksi/detail/{id}', [App\Http\Controllers\LazisController::class, 'detailTransaksi'])->name('lazis.detailTransaksi');

		//LAPORAN
		Route::get('/laporan/validasi', [App\Http\Controllers\LazisController::class, 'getLaporanValidasi'])->name('lazis.laporanValidasi');
		Route::get('/laporan/validasi/getdata', [App\Http\Controllers\LazisController::class, 'getDataLaporanValidasi'])->name('lazis.getDataLaporanValidasi');
		Route::get('/laporan/realisasi-paketziswaf', [App\Http\Controllers\LazisController::class, 'getLaporanRealisasiPaketZiswaf'])->name('lazis.laporanRealisasiPaketZiswaf');
		Route::get('/laporan/realisasi-paketziswaf/getdata/{id}', [App\Http\Controllers\LazisController::class, 'getDataLaporanRealisasiPaketZiswaf'])->name('lazis.getDataLaporanRealisasiPaketZiswaf');
		Route::get('/laporan/distribusi', [App\Http\Controllers\LazisController::class, 'getLaporanDistribusi'])->name('lazis.laporanRealisasiDistribusi');
		Route::get('/laporan/distribusi/getdata', [App\Http\Controllers\LazisController::class, 'getDataLaporanDistribusi'])->name('lazis.getDataLaporanDistribusi');

		//DONATUR
		Route::get('/donatur', [App\Http\Controllers\LazisController::class, 'getDonatur'])->name('lazis.donatur');
		Route::get('/donatur/getdata', [App\Http\Controllers\LazisController::class, 'getDataDonatur'])->name('lazis.getDonatur');
		Route::get('/donatur/detail/{id}', [App\Http\Controllers\LazisController::class, 'detailDonatur'])->name('lazis.detailDonatur');
	});

    Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
    Route::get('/transaksi/export', [App\Http\Controllers\HomeController::class, 'transaksiExport'])->name('transaksiExport');
    Route::get('/transaksi/export/izi', [App\Http\Controllers\HomeController::class, 'iziExportById'])->name('iziByIdExport');
    Route::get('/transaksi/export/lazdai', [App\Http\Controllers\HomeController::class, 'lazdaiByIdExport'])->name('lazdaiByIdExport');
    Route::get('/transaksi/export/struktur', [App\Http\Controllers\HomeController::class, 'strukturByIdExport'])->name('strukturByIdExport');
    Route::get('/transaksi/export/tunai', [App\Http\Controllers\HomeController::class, 'tunaiByIdExport'])->name('tunaiByIdExport');
    Route::get('/transaksi/export/nontunai', [App\Http\Controllers\HomeController::class, 'nontunaiByIdExport'])->name('nontunaiByIdExport');
    Route::get('/transaksi/export/barang', [App\Http\Controllers\HomeController::class, 'barangByIdExport'])->name('barangByIdExport');
    Route::get('/user/export/{id}', [App\Http\Controllers\HomeController::class, 'userByIdExport'])->name('userByIdExport');
    Route::get('/user/export', [App\Http\Controllers\HomeController::class, 'userExport'])->name('userExport');
    Route::get('/kalkulator', [App\Http\Controllers\HomeController::class, 'kalkulator'])->name('kalkulator');
    Route::get('/informasi', [App\Http\Controllers\HomeController::class, 'informasi'])->name('informasi');
    Route::get('/surat_tugas/izi/{id}', [App\Http\Controllers\HomeController::class, 'suratTugasIZI'])->name('suratTugasIZI');
    Route::get('/surat_tugas/lazdai/{id}', [App\Http\Controllers\HomeController::class, 'suratTugasLAZDAI'])->name('suratTugasLAZDAI');

});
