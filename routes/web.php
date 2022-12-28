<?php

use App\Helpers\UserAkses;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('/home');
// });

date_default_timezone_set('Asia/Jakarta');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/login', 'HomeController@index')->name('home');


Route::get('/data/{kode}', 'HomeController@data');

Route::get('/noakses', 'HomeController@noakses')->name('noakses');
Route::get('/kirimemail', 'HomeController@email')->name('kirimemail');
Route::get('/userakun/aktivasi/{code}', 'UserAkunCont@aktivasi');
Route::get('/userakun/lupapassword/', 'UserAkunCont@lupapassword')->name('lupaPassword');
Route::post('/userakun/aktivasi/update', 'UserAkunCont@updateAktivasi')->name('updateAktivasi');
Route::post('/userakun/aktivasi/ResetPassword', 'UserAkunCont@resetPassword')->name('resetPassword');
Route::get('/userakun/resetpassword/{code}', 'UserAkunCont@viewresetPassword')->name('viewresetPassword');
Route::post('/userakun/resetpassword/update', 'UserAkunCont@updateResetPassword')->name('updateResetPassword');

//register
Route::get('/register/create', 'RegisterCont@create');
Route::post('/register/create', 'RegisterCont@store')->name('createRegister');
Route::get('/register', 'RegisterCont@index')->name('daftarRegister');
Route::post('/register/update', 'RegisterCont@update')->name('updateRegister');
Route::get('/register/{id}', 'RegisterCont@edit');

Route::get('/users', 'UserController@index')->name('users');
Route::get('/users/create', 'UserController@create')->name('createUser');
Route::get('/users/akses/{id}', 'UserController@akses')->name('akses');
Route::post('/users/akses/update', 'UserController@updateAkses')->name('updateAkses');
Route::post('/users/update', 'UserController@update')->name('updateUsers');
Route::get('/users/destroy/{id}', 'UserController@destroy')->name('destroyUsers');
Route::get('/users/{id}', 'UserController@edit');

//anggota
Route::get('/anggota', 'AnggotaCont@index')->name('anggota');
Route::get('/anggota/profile', 'AnggotaCont@profile')->name('profileanggota');
Route::get('/anggota/create', 'AnggotaCont@create')->name('createAnggota');
Route::post('/anggota/store', 'AnggotaCont@store')->name('storeAnggota');
Route::post('/anggota/update', 'AnggotaCont@update')->name('updateAnggota');
Route::get('/anggota/filter/{id}', 'AnggotaCont@filter')->name('filterAnggota');
Route::get('/anggota/destroy/{id}', 'AnggotaCont@destroy');
Route::get('/anggota/{id}', 'AnggotaCont@edit');


//pengurus
Route::get('/pengurus', 'PengurusCont@index')->name('pengurus');
Route::get('/pengurus/create', 'PengurusCont@create')->name('createPengurus');
Route::post('/pengurus/store', 'PengurusCont@store')->name('storePengurus');
Route::post('/pengurus/update', 'PengurusCont@update')->name('updatePengurus');
Route::get('/pengurus/destroy/{id}', 'PengurusCont@destroy');
Route::get('/pengurus/{id}', 'PengurusCont@edit');

//pajak
Route::get('/pajak', 'PajakCont@index')->name('pajak');
Route::get('/pajak/create', 'PajakCont@create')->name('createPajak');
Route::post('/pajak/store', 'PajakCont@store')->name('storePajak');
Route::post('/pajak/update', 'PajakCont@update')->name('updatePajak');
Route::get('/pajak/destroy/{id}', 'PajakCont@destroy');
Route::get('/pajak/{id}', 'PajakCont@edit');

//Pengelola
Route::get('/pengelola', 'PengelolaCont@index')->name('pengelola');
Route::get('/pengelola/create', 'PengelolaCont@create')->name('createPengelola');
Route::post('/pengelola/store', 'PengelolaCont@store')->name('storePengelola');
Route::post('/pengelola/update', 'PengelolaCont@update')->name('updatePengelola');
Route::get('/pengelola/destroy/{id}', 'PengelolaCont@destroy');
Route::get('/pengelola/{id}', 'PengelolaCont@edit');

//jenis_simpanan
Route::get('/jenis_simpanan', 'JenisSimpananCont@index')->name('jenis_simpanan');
Route::get('/jenis_simpanan/create', 'JenisSimpananCont@create')->name('createJenisSimpanan');
Route::post('/jenis_simpanan/store', 'JenisSimpananCont@store')->name('storeJenisSimpanan');
Route::post('/jenis_simpanan/update', 'JenisSimpananCont@update')->name('updateJenisSimpanan');
Route::get('/jenis_simpanan/destroy/{id}', 'JenisSimpananCont@destroy');
Route::get('/jenis_simpanan/{id}', 'JenisSimpananCont@edit');

//sumber_pinjaman
Route::get('/sumber_pinjaman', 'SumberPinjamanCont@index')->name('sumber_pinjaman');
Route::get('/sumber_pinjaman/create', 'SumberPinjamanCont@create')->name('createSumberPinjaman');
Route::post('/sumber_pinjaman/store', 'SumberPinjamanCont@store')->name('storeSumberPinjaman');
Route::post('/sumber_pinjaman/update', 'SumberPinjamanCont@update')->name('updateSumberPinjaman');
Route::get('/sumber_pinjaman/destroy/{id}', 'SumberPinjamanCont@destroy');
Route::get('/sumber_pinjaman/{id}', 'SumberPinjamanCont@edit');

//simpanan
Route::get('/simpanan', 'SimpananCont@index')->name('simpanan');
Route::post('/simpanan/filter', 'SimpananCont@filter')->name('filterSimpanan');
Route::get('/simpanan/saldo', 'SimpananCont@saldo')->name('saldosimpanan');
Route::post('/simpanan/filtersaldo', 'SimpananCont@filtersaldo')->name('filterSaldosimpanan');

//setoran
Route::get('/setoran', 'SetoranCont@index')->name('setoran');
Route::get('/setoran/create', 'SetoranCont@create');
Route::get('/setoran/import', 'SetoranCont@import');
Route::get('/setoran/rekon', 'SetoranCont@rekon');
Route::get('/setoran/destroy/{id}', 'SetoranCont@destroy');
Route::post('/setoran/update', 'SetoranCont@update')->name('updateSetoran');
Route::post('/setoran/store', 'SetoranCont@store')->name('storeSetoran');
Route::post('/setoran/filter', 'SetoranCont@filter')->name('filterSetoran');
Route::get('/setoran/{id}', 'SetoranCont@edit')->name('showSetoran');

//pinjaman
Route::get('/pinjaman', 'PinjamanCont@index')->name('pinjaman');
Route::get('/pinjaman/daftarpermohonan', 'PinjamanCont@daftarPermohonan')->name('daftarPermohonan');
Route::get('/pinjaman/daftarpeminjam', 'PinjamanCont@daftarPeminjam')->name('daftarPeminjam');
Route::get('/pinjaman/daftarpelunasan', 'PinjamanCont@daftarPelunasan')->name('daftarPelunasan');
Route::get('/pinjaman/daftarangsuran', 'PinjamanCont@daftarAngsuran')->name('daftarAngsuran');
Route::get('/pinjaman/daftartunggakan', 'PinjamanCont@daftarTunggakan')->name('daftarTunggakan');
Route::get('/ajukanpinjaman', 'PinjamanCont@pengajuan')->name('pinjamanInput');
Route::post('/pinjaman/store', 'PinjamanCont@store')->name('storePinjaman');
Route::post('/pinjaman/update', 'PinjamanCont@update')->name('updatePinjaman');
Route::get('/pinjaman/bayarangsuran', 'PinjamanCont@bayarAngsuran')->name('bayarAngsuran');
Route::get('/pinjaman/showangsuran/{id}', 'PinjamanCont@showAngsuran')->name('showAngsuran');
Route::get('/pinjaman/destroyangsuran/{id}', 'PinjamanCont@destroyAngsuran');
Route::get('/pinjaman/destroy/{id}', 'PinjamanCont@destroyPinjaman')->name('pinjamanDestroy');

Route::post('/pinjaman/filterangsuran', 'PinjamanCont@filterAngsuran')->name('filterAngsuran');

Route::get('/pinjaman/getsimpanan/{id}', 'PinjamanCont@getSimpanan');
Route::get('/pinjaman/getpinjaman/{id}', 'PinjamanCont@getPinjaman');
Route::get('/pinjaman/show/{id}', 'PinjamanCont@show');
Route::get('/pinjaman/detail/{id}/', 'PinjamanCont@detail');
Route::get('/pinjaman/edit/{id}/', 'PinjamanCont@editPinjaman')->name('editPinjaman');
Route::post('/pinjaman/filter', 'PinjamanCont@filter')->name('filterPinjaman');
Route::post('/pinjaman/info', 'PinjamanCont@info')->name('detailPeminjam');
Route::post('/pinjaman/updateangsuran', 'PinjamanCont@updateAngsuran')->name('updateAngsuranPinjaman');

//pelunasan
Route::post('/pinjaman/updatepelunasan', 'PinjamanCont@updatePelunasan')->name('updatePelunasanPinjaman');
Route::get('/pinjaman/bayarpelunasan', 'PinjamanCont@bayarPelunasan')->name('bayarPelunasan');
Route::get('/pinjaman/showpelunasan/{id}/{nilai}', 'PinjamanCont@showPelunasan')->name('showPelunasan');
Route::get('/pinjaman/destroypelunasan/{id}', 'PinjamanCont@destroyPelunasan');
Route::post('/pinjaman/filterpelunasan', 'PinjamanCont@filterPelunasan')->name('filterPelunasan');
Route::get('/pinjaman/{id}', 'PinjamanCont@edit')->name('dataPermohonan');

//upload
Route::post('/home/upload', 'HomeController@upload')->name('uploadProses');

//rekon payroll
Route::get('/rekon', 'RekonController@index')->name('rekon');
Route::post('/home/rekon/upload', 'HomeController@uploadrekon')->name('uploadrekon');
Route::get('/rekon/simpanan/{periode}', 'RekonController@simpanan')->name('prosesPayrollSimpanan');
Route::get('/rekon/angsuran/{periode}', 'RekonController@angsuran')->name('prosesPayrollAngsuran');
Route::post('/rekon/cekdata', 'RekonController@cekDataRekon')->name('cekDataRekon');

Route::post('/home/simulasi', 'HomeController@simulasi')->name('simulasiKredit');
Route::get('/payroll/potongan', 'RekonController@potonganPayroll')->name('potonganPayroll');
Route::post('/payroll/filter', 'RekonController@filterPotongan')->name('filterPotongan');


//perusahaan
Route::get('/perusahaan', 'PerusahaanCont@index')->name('perusahaan');
Route::get('/perusahaan/create', 'PerusahaanCont@create')->name('createPerusahaan');
Route::post('/perusahaan/store', 'PerusahaanCont@store')->name('storePerusahaan');
Route::post('/perusahaan/update', 'PerusahaanCont@update')->name('updatePerusahaan');
Route::get('/perusahaan/destroy/{id}', 'PerusahaanCont@destroy');
Route::get('/perusahaan/{id}', 'PerusahaanCont@edit');

//Vendor
Route::get('/vendor', 'VendorCont@index')->name('vendor');
Route::get('/vendor/create', 'VendorCont@create')->name('createVendor');
Route::post('/vendor/store', 'VendorCont@store')->name('storeVendor');
Route::post('/vendor/update', 'VendorCont@update')->name('updateVendor');
Route::get('/vendor/destroy/{id}', 'VendorCont@destroy');
Route::get('/vendor/{id}', 'VendorCont@edit');

//Produk
Route::get('/produk', 'ProdukCont@index')->name('produk');
Route::get('/produk/create', 'ProdukCont@create')->name('createProduk');
Route::post('/produk/update', 'ProdukCont@update')->name('updateProduk');
Route::get('/produk/destroy/{id}', 'ProdukCont@destroy');
Route::get('/produk/{id}', 'ProdukCont@edit');


//project
Route::get('/project', 'ProjectCont@index')->name('project');
Route::get('/project/createAM/{id}', 'ProjectCont@createAM')->name('createAM');
Route::post('/project/updateAM', 'ProjectCont@updateAM')->name('updateAM');
Route::get('/project/create', 'ProjectCont@create')->name('createProject');
Route::post('/project/store', 'ProjectCont@store')->name('storeProject');
Route::post('/project/update', 'ProjectCont@update')->name('updateProject');
Route::get('/project/show/{id}', 'ProjectCont@show')->name('showProject');
Route::get('/project/destroy/{id}', 'ProjectCont@destroy');
Route::get('/project/createItem', 'ProjectCont@createItem');
Route::post('/project/updateItem', 'ProjectCont@updateItem')->name('updateItem');
Route::get('/project/destroyitem/{id}', 'ProjectCont@destroyItem');
Route::post('/project/info', 'ProjectCont@infoProject')->name('infoProject');
Route::post('/project/filter', 'ProjectCont@filterProject')->name('filterProject');

//am

Route::get('/project/createAM/{id}', 'ProjectCont@createAM')->name('createAM');
Route::post('/project/updateAM', 'ProjectCont@updateAM')->name('updateAM');
Route::get('/project/showAM/{id}', 'ProjectCont@showAM')->name('showAM');
Route::get('/project/destroyAM/{id}', 'ProjectCont@destroyAM');

//invoice
Route::get('/invoice', 'ProjectCont@invoice')->name('invoice');
Route::get('/project/createInvoice', 'ProjectCont@createInvoice')->name('createInvoice');
Route::post('/project/updateInvoice', 'ProjectCont@updateInvoice')->name('updateInvoice');
Route::get('/project/destroyInvoice/{id}', 'ProjectCont@destroyInvoice');
Route::get('/project/batalInvoice/{id}', 'ProjectCont@batalInvoice')->name('batalInvoice');
Route::post('/project/updatePembatalan', 'ProjectCont@updatePembatalan')->name('updatePembatalan');
Route::get('/project/previewInvoice/{id}', 'ProjectCont@previewInvoice')->name('previewInvoice');
Route::get('/project/printInvoice/{id}', 'ProjectCont@printInvoice')->name('printInvoice');
Route::get('/project/printSPB/{id}', 'ProjectCont@printSPB')->name('printSPB');
Route::get('/project/printTT/{id}', 'ProjectCont@printTT')->name('printTT');
Route::get('/project/printSJ/{id}', 'ProjectCont@printSJ')->name('printSJ');
Route::get('/project/printBA/{id}', 'ProjectCont@printBA')->name('printBA');
Route::get('/project/printKwitansi/{id}', 'ProjectCont@printKwitansi')->name('printKwitansi');
Route::get('/project/printBAPPKontrak/{id}', 'ProjectCont@printBAPPKontrak')->name('printBAPPKontrak');
Route::get('/project/printBAUTKontrak/{id}', 'ProjectCont@printBAUTKontrak')->name('printBAUTKontrak');
Route::get('/project/printBAPPGSD/{id}', 'ProjectCont@printBAPPGSD')->name('printBAPPGSD');



Route::post('/project/filterInvoice', 'ProjectCont@filterInvoice')->name('filterInvoice');

Route::get('/panjar', 'ProjectCont@panjar')->name('panjar');
Route::get('/project/createPanjar', 'ProjectCont@createPanjar')->name('createPanjar');
Route::post('/project/updatePanjar', 'ProjectCont@updatePanjar')->name('updatePanjar');
Route::get('/project/destroyPanjar/{id}', 'ProjectCont@destroyPanjar');
Route::post('/project/infopanjar', 'ProjectCont@infoProjectPanjar')->name('infoProjectPanjar');
Route::get('/project/persetujuanPanjar/{id}', 'ProjectCont@persetujuanPanjar')->name('persetujuanPanjar');
Route::get('/project/batalpersetujuanPanjar/{id}', 'ProjectCont@batalpersetujuanPanjar')->name('batalpersetujuanPanjar');
Route::get('/project/showpanjar/{id}', 'ProjectCont@showPanjar')->name('showPanjar');
Route::get('/project/editPanjar/{id}', 'ProjectCont@editPanjar')->name('editPanjar');
Route::get('/project/printPanjar/{id}', 'ProjectCont@printPanjar')->name('printPanjar');

Route::get('/project/invoice/{id}', 'ProjectCont@editInvoice')->name('editInvoice');
Route::get('/project/invoice/show/{id}', 'ProjectCont@showInvoice')->name('showInvoice');
Route::get('/project/item/{id}', 'ProjectCont@editItem');

//dashboard project
Route::get('/project/dashboard', 'ProjectCont@dashboard')->name('projectDb');
Route::post('/project/dashboard/filter', 'ProjectCont@filterDashboard')->name('filterDashboard');
Route::get('/project/dashboard/listproject/{jenis}', 'ProjectCont@dashboardProjectList')->name('projectListDbl');
Route::get('/project/dashboardpegawai/{id}', 'ProjectCont@dashboardpegawai')->name('projectDbpegawai');



//pembayaran
Route::get('/project/createPembayaran/{id}', 'PembayaranCont@create')->name('createPembayaran');
Route::get('/project/createPembayaranPanjar/{id}', 'PembayaranCont@panjar')->name('createPembayaranPanjar');
Route::get('/project/showPembayaran/{id}', 'PembayaranCont@show')->name('showPembayaran');
Route::get('/project/editPembayaran/{id}', 'PembayaranCont@edit')->name('editPembayaran');
Route::get('/project/destroyPembayaran/{id}', 'PembayaranCont@destroy')->name('destroyPembayaran');
Route::post('/project/updatePembayaran', 'PembayaranCont@update')->name('updatePembayaran');
Route::post('/project/updatePembayaranPanjar', 'PembayaranCont@updatePembayaranPanjar')->name('updatePembayaranPanjar');

//panjar
Route::get('/project/panjar/pengunaan/{id}', 'ProjectCont@createPengunaanPanjar')->name('createPengunaanPanjar');
Route::post('/project/panjar/updatepenggunaan', 'ProjectCont@updatePenggunaanPanjar')->name('updatePenggunaanPanjar');
Route::get('/project/panjar/destroyPenggunaanPanjar/{id}', 'ProjectCont@destroyPenggunaanPanjar')->name('destroyPenggunaanPanjar');
Route::get('/project/panjar/showPenggunaanPanjar/{id}', 'ProjectCont@showPenggunaanPanjar')->name('showPenggunaanPanjar');


Route::get('/project/{id}', 'ProjectCont@edit')->name('editProject');

//pemesan
Route::get('/pemesan', 'PemesanCont@index')->name('pemesan');
Route::get('/pemesan/create', 'PemesanCont@create')->name('createPemesan');
Route::post('/pemesan/update', 'PemesanCont@update')->name('updatePemesan');
Route::get('/pemesan/destroy/{id}', 'PemesanCont@destroy')->name('destroyPemesan');
Route::get('/pemesan/{id}', 'PemesanCont@show')->name('showPemesan');


//target
Route::get('/target', 'TargetCont@index')->name('target');
Route::get('/target/create', 'TargetCont@create')->name('createTarget');
Route::post('/target/update', 'TargetCont@update')->name('updateTarget');
Route::get('/target/destroy/{id}', 'TargetCont@destroy');
Route::get('/target/{id}', 'TargetCont@edit')->name('editTarget');;

//rekap pajak project
Route::get('/rekappajak/pph22', 'RekapController@pph22')->name('rekappph22');
Route::get('/rekappajak/pph23', 'RekapController@pph23')->name('rekappph23');
Route::get('/rekappajak/ppn', 'RekapController@ppn')->name('rekapppn');
Route::post('/rekappajak/filterppn', 'RekapController@filterppn')->name('filterPpn');
Route::post('/rekappajak/update', 'RekapController@updaterekap')->name('updateRekapPajak');
Route::get('/rekappajak/{id}', 'RekapController@edit')->name('editRekapPajak');

//pembelian
Route::get('/pembelian', 'PembelianCont@index')->name('pembelian');
Route::get('/pembelian/create', 'PembelianCont@create')->name('createPembelian');
Route::post('/pembelian/update', 'PembelianCont@update')->name('updatePembelian');
Route::post('/pembelian/produk/update', 'PembelianCont@updateProduk')->name('updatePembelianProduk');

Route::get('/pembelian/destroy/{id}', 'PembelianCont@destroy');
Route::get('/pembelian/destroyproduk/{id}', 'PembelianCont@destroyProduk');
Route::get('/pembelian/edit/{id}', 'PembelianCont@edit')->name('editPembelian');

Route::get('/pembelian/pembayaran/{id}', 'PembelianCont@createPembayaran')->name('createPembelianPembayaran');
Route::post('/pembelian/pembayaran/update', 'PembelianCont@updatePembayaran')->name('updatePembelianPembayaran');
Route::get('/pembelian/showpembayaran/{id}', 'PembelianCont@showPembayaran')->name('showPembelianPembayaran');
Route::get('/pembelian/editpembayaran/{id}', 'PembelianCont@editPembayaran')->name('editPembelianPembayaran');
Route::get('/pembelian/destroypembayaran/{id}', 'PembelianCont@destroyPembayaran');
Route::get('/pembelian/cetak/{id}', 'PembelianCont@cetak')->name('cetakPembelian');

Route::get('/pembelian/create/{id}', 'PembelianCont@create')->name('createPembelian');
Route::get('/pembelian/{id}', 'PembelianCont@show')->name('showPembelian');

//pq
Route::get('/purchase_quotes', 'PembelianPenawaranCont@index')->name('pq');
Route::get('/purchase_quotes/create', 'PembelianPenawaranCont@create')->name('create_pq');
Route::post('/purchase_quotes/update', 'PembelianPenawaranCont@update')->name('update_pq');
Route::get('/purchase_quotes/destroy/{id}', 'PembelianPenawaranCont@destroy');
Route::get('/purchase_quotes/edit/{id}', 'PembelianPenawaranCont@edit')->name('edit_pq');
Route::get('/purchase_quotes/destroyproduk/{id}', 'PembelianPenawaranCont@destroyProduk');
Route::get('/purchase_quotes/createpemesanan/{id}', 'PembelianPenawaranCont@createPemesanan');
Route::get('/purchase_quotes/cetak/{id}', 'PembelianPenawaranCont@cetak')->name('cetak_pq');

Route::get('/purchase_quotes/{id}', 'PembelianPenawaranCont@show')->name('show_pq');

//po
Route::get('/purchase_order', 'PembelianPemesananCont@index')->name('po');
Route::get('/purchase_order/create', 'PembelianPemesananCont@create')->name('create_po');
Route::post('/purchase_order/update', 'PembelianPemesananCont@update')->name('update_po');
Route::get('/purchase_order/destroy/{id}', 'PembelianPemesananCont@destroy');
Route::get('/purchase_order/edit/{id}', 'PembelianPemesananCont@edit')->name('edit_po');
Route::get('/purchase_order/destroyproduk/{id}', 'PembelianPemesananCont@destroyProduk');
Route::get('/purchase_order/createpembelian/{id}', 'PembelianPemesananCont@createPembelian');
Route::get('/purchase_order/cetak/{id}', 'PembelianPemesananCont@cetak')->name('cetak_po');

Route::get('/purchase_order/{id}', 'PembelianPemesananCont@show')->name('show_po');

//finance
Route::get('/akun', 'AkunCont@index')->name('akun');
Route::get('/akun/create', 'AkunCont@create')->name('createAkun');
Route::post('/akun/update', 'AkunCont@update')->name('updateAkun');
Route::get('/akun/show/{id}', 'AkunCont@show')->name('showAkun');
Route::get('/akun/destroy/{id}', 'AkunCont@destroy');


//biaya
Route::get('/biaya', 'BiayaCont@index')->name('biaya');
Route::get('/biaya/create', 'BiayaCont@create')->name('createBiaya');
Route::post('/biaya/update', 'BiayaCont@update')->name('updateBiaya');
Route::get('/biaya/show/{id}', 'BiayaCont@show')->name('showBiaya');
Route::get('/biaya/edit/{id}', 'BiayaCont@edit')->name('editBiaya');
Route::get('/biaya/destroyakun/{id}', 'BiayaCont@destroyAkun');
Route::get('/biaya/destroy/{id}', 'BiayaCont@destroy');

//keuangan
Route::get('/keuangan/saldoawal', 'KeuanganCont@saldoawal')->name('saldoawal');
Route::post('/keuangan/saldoawal/update', 'KeuanganCont@saldoawalupdate')->name('saldoawalupdate');
Route::get('/keuangan/laporan/jurnal/create', 'KeuanganCont@laporanJurnalCreate')->name('laporanJurnalCreate');
Route::post('/keuangan/laporan/jurnal', 'KeuanganCont@laporanJurnal')->name('laporanJurnal');

Route::get('/keuangan/laporan/bukubesar/create', 'KeuanganCont@laporanBukubesarCreate')->name('laporanBukubesarCreate');
Route::post('/keuangan/laporan/bukubesar', 'KeuanganCont@laporanBukubesar')->name('laporanBukubesar');

Route::get('/keuangan/laporan/neraca/create', 'KeuanganCont@laporanNeracaCreate')->name('laporanNeracaCreate');
Route::post('/keuangan/laporan/neraca', 'KeuanganCont@laporanNeraca')->name('laporanNeraca');

Route::get('/keuangan/laporan/labarugi/create', 'KeuanganCont@laporanLabarugiCreate')->name('laporanLabarugiCreate');
Route::post('/keuangan/laporan/labarugi', 'KeuanganCont@laporanLabarugi')->name('laporanLabarugi');

Route::get('/keuangan/laporan/perubahanmodal/create', 'KeuanganCont@laporanPerubahanmodalCreate')->name('laporanPerubahanmodalCreate');
Route::post('/keuangan/laporan/perubahanmodal', 'KeuanganCont@laporanPerubahanmodal')->name('laporanPerubahanmodal');

Route::get('/keuangan/laporan/pajak', 'KeuanganCont@laporanPajak')->name('laporanPajak');

Route::get('/keuangan/index', 'KeuanganCont@index')->name('index');
Route::get('/keuangan/saldokas', 'KeuanganCont@saldokas')->name('saldoKas');
Route::get('/keuangan/transferkas/{id}', 'KeuanganCont@transferkas')->name('transferKas');
Route::get('/keuangan/showtransferkas/{id}', 'KeuanganCont@showtransferkas')->name('showtransferKas');
Route::get('/keuangan/jurnal/transfer/{id}', 'KeuanganCont@showJurnalTransfer')->name('showJurnalTransfer');

Route::get('/keuangan/destroytransferkas/{id}', 'KeuanganCont@destroytransferkas')->name('destroytransferKas');
Route::post('/keuangan/transferkasupdate', 'KeuanganCont@transferkasupdate')->name('transferKasUpdate');

Route::get('/keuangan/jurnal/biaya/{id}', 'KeuanganCont@showJurnalBiaya')->name('showJurnalBiaya');
Route::get('/keuangan/jurnal/invoice/{id}', 'KeuanganCont@showJurnalInvoice')->name('showJurnalInvoice');
Route::get('/keuangan/jurnal/pembayaran/{id}', 'KeuanganCont@showJurnalPembayaran')->name('showJurnalPembayaran');
Route::get('/keuangan/jurnal/pembayaranbeli/{id}', 'KeuanganCont@showJurnalPembayaranPembelian')->name('showJurnalPembayaranPembelian');

Route::get('/keuangan/detailakun/{id}', 'KeuanganCont@detailAkun')->name('detailAkun');

Route::get('/keuangan/terimauang/{id}', 'KeuanganCont@terimaUang')->name('terimaUang');
Route::post('/keuangan/terimauangupdate', 'KeuanganCont@terimauangupdate')->name('terimaUangUpdate');
Route::get('/keuangan/jurnal/terimauang/{id}', 'KeuanganCont@showJurnalTerimauang')->name('showJurnalTerimauang');
Route::get('/keuangan/terimauang/show/{id}', 'KeuanganCont@showTerimaUang')->name('showTerimaUang');
Route::get('/keuangan/terimauang/edit/{id}', 'KeuanganCont@editTerimaUang')->name('editTerimaUang');
Route::get('/keuangan/kirimuang/destroy/{id}', 'KeuanganCont@destroyKirimuang');
Route::get('/keuangan/terimauang/destroy/{id}', 'KeuanganCont@destroyTerimauang');

Route::get('/keuangan/kirimuang/{id}', 'KeuanganCont@kirimuang')->name('kirimUang');
Route::post('/keuangan/kirimuangupdate', 'KeuanganCont@kirimuangupdate')->name('kirimUangUpdate');
Route::get('/keuangan/jurnal/kirimuang/{id}', 'KeuanganCont@showJurnalKirimuang')->name('showJurnalKirimuang');
Route::get('/keuangan/kirimuang/show/{id}', 'KeuanganCont@showKirimUang')->name('showKirimUang');
Route::get('/keuangan/kirimuang/edit/{id}', 'KeuanganCont@editKirimUang')->name('editKirimUang');

//jurnalumum
Route::get('/jurnalumum', 'JurnalUmumCont@index')->name('jurnalumum');
Route::get('/jurnalumum/create', 'JurnalUmumCont@create')->name('createJurnalumum');
Route::post('/jurnalumum/update', 'JurnalUmumCont@update')->name('updateJurnalumum');
Route::get('/keuangan/jurnalumum/{id}', 'keuanganCont@showJurnalJurnal')->name('showJurnalJurnalumum');
Route::get('/jurnalumum/show/{id}', 'JurnalUmumCont@show')->name('showJurnalumum');
Route::get('/jurnalumum/edit/{id}', 'JurnalUmumCont@edit')->name('editJurnalumum');
Route::get('/jurnalumum/destroy/{id}', 'JurnalUmumCont@destroy');

Route::get('/keuangan/lampiran/destroy/{id}', 'KeuanganCont@destroyLampiran')->name('destroyLampiran');

//mapping
Route::get('/pengaturan/mappingAkun/', 'PengaturanCont@mappingAkun')->name('mappingAkun');
Route::post('/pengaturan/updateMapping', 'PengaturanCont@updateMapping')->name('updateMapping');

Route::get('/backup123', 'BackupController@db');

// Route::group([
//   'prefix' => 'xauth'
// ], function () {
//   Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('register');
//   Route::post('register', 'Auth\RegisterController@register')->name('register.post');
//   Route::get('login', 'Auth\LoginController@showLoginform')->name('login');
//   Route::post('login', 'Auth\LoginController@login')->name('login.post');
//   Route::get('forgot-password', 'Auth\PasswordResetController@showForgotForm')->name('forgot');
//   Route::post('forgot-password', 'Auth\PasswordResetController@sendForgotLink')->name('forgot.sendLink');
//   Route::get('reset-password/{token}', 'Auth\PasswordResetController@showResetForm')->name('reset');
//   Route::post('reset-password', 'Auth\PasswordResetController@reset')->name('reset.post');
// });

Route::group([
    'middleware' => ['auth', 'AuthCheck'],
], function () {
    Route::post('token-refresh', 'Auth\LoginController@refresh')->name('token.refresh');

    Route::group([
        'prefix' => 'dashboard',
    ], function () {
        //   Route::get('/', 'Admin\DashboardController@index')->name('dashboard');
        //   Route::get('summary', 'Admin\DashboardController@counter')->name('dashboard.counter');
        Route::get('list', 'Admin\DashboardController@list')->name('dashboard.list');
        //   Route::get('{id}', 'Admin\DashboardController@show')->name('dashboard.read');
        //   Route::post('create', 'Admin\DashboardController@store')->name('dashboard.create');
        //   Route::put('update/{id}', 'Admin\DashboardController@update')->name('dashboard.update');
        //   Route::delete('delete/{id}', 'Admin\DashboardController@destroy')->name('dashboard.destroy');
    });

    Route::group([
        'prefix' => 'banner',
    ], function () {
        Route::get('/', 'Admin\MasterData\BannerController@index')->name('banner');
        Route::get('list', 'Admin\MasterData\BannerController@list')->name('banner.list');
        Route::get('{id}', 'Admin\MasterData\BannerController@show')->name('banner.read');
        Route::post('create', 'Admin\MasterData\BannerController@store')->name('banner.create');
        Route::put('update/{id}', 'Admin\MasterData\BannerController@update')->name('banner.update');
        Route::delete('delete/{id}', 'Admin\MasterData\BannerController@destroy')->name('banner.destroy');
    });

    Route::group([
        'prefix' => 'cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\CutiController@index')->name('cuti');
        Route::get('list', 'Admin\MasterData\CutiController@list')->name('cuti.list');
        Route::get('{id}', 'Admin\MasterData\CutiController@show')->name('cuti.read');
        Route::post('create', 'Admin\MasterData\CutiController@store')->name('cuti.create');
        Route::put('update/{id}', 'Admin\MasterData\CutiController@update')->name('cuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\CutiController@destroy')->name('cuti.destroy');
    });

    Route::group([
        'prefix' => 'jenis-cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\JenisCutiController@index')->name('jenisCuti');
        Route::get('list', 'Admin\MasterData\JenisCutiController@list')->name('jenisCuti.list');
        Route::get('{id}', 'Admin\MasterData\JenisCutiController@show')->name('jenisCuti.read');
        Route::post('create', 'Admin\MasterData\JenisCutiController@store')->name('jenisCuti.create');
        Route::put('update/{id}', 'Admin\MasterData\JenisCutiController@update')->name('jenisCuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\JenisCutiController@destroy')->name('jenisCuti.destroy');
    });

    Route::group([
        'prefix' => 'alasan-presensi',
    ], function () {
        Route::get('/', 'Admin\MasterData\AlasanPresensiController@index')->name('alasanPresensi');
        Route::get('list', 'Admin\MasterData\AlasanPresensiController@list')->name('alasanPresensi.list');
        Route::get('{id}', 'Admin\MasterData\AlasanPresensiController@show')->name('alasanPresensi.read');
        Route::post('create', 'Admin\MasterData\AlasanPresensiController@store')->name('alasanPresensi.create');
        Route::put('update/{id}', 'Admin\MasterData\AlasanPresensiController@update')->name('alasanPresensi.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AlasanPresensiController@destroy')->name('alasanPresensi.destroy');
    });

    Route::group([
        'prefix' => 'alasan-cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\AlasanCutiController@index')->name('alasanCuti');
        Route::get('list', 'Admin\MasterData\AlasanCutiController@list')->name('alasanCuti.list');
        Route::get('{id}', 'Admin\MasterData\AlasanCutiController@show')->name('alasanCuti.read');
        Route::post('create', 'Admin\MasterData\AlasanCutiController@store')->name('alasanCuti.create');
        Route::put('update/{id}', 'Admin\MasterData\AlasanCutiController@update')->name('alasanCuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AlasanCutiController@destroy')->name('alasanCuti.destroy');
    });

    Route::group([
        'prefix' => 'hari-libur',
    ], function () {
        Route::get('/', 'Admin\MasterData\HariLiburController@index')->name('hariLibur');
        Route::get('list', 'Admin\MasterData\HariLiburController@list')->name('hariLibur.list');
        Route::get('{date}', 'Admin\MasterData\HariLiburController@show')->name('hariLibur.read');
        Route::post('create', 'Admin\MasterData\HariLiburController@store')->name('hariLibur.create');
        Route::put('update/{id}', 'Admin\MasterData\HariLiburController@update')->name('hariLibur.update');
        Route::delete('delete/{id}', 'Admin\MasterData\HariLiburController@destroy')->name('hariLibur.destroy');
    });

    Route::group([
        'prefix' => 'app-version',
    ], function () {
        Route::get('/', 'Admin\MasterData\AppVersionController@index')->name('appVersion');
        Route::get('list', 'Admin\MasterData\AppVersionController@list')->name('appVersion.list');
        Route::get('{date}', 'Admin\MasterData\AppVersionController@show')->name('appVersion.read');
        Route::post('create', 'Admin\MasterData\AppVersionController@store')->name('appVersion.create');
        Route::put('update/{id}', 'Admin\MasterData\AppVersionController@update')->name('appVersion.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AppVersionController@destroy')->name('appVersion.destroy');
    });

    Route::group([
        'prefix' => 'laporan',
    ], function () {
        Route::get('presensi-tahunan-user', 'Admin\Report\PresensiTahunanUserController@index')->name('report.presensiTahunanUser');
        Route::get('presensi-tahunan-user/list', 'Admin\Report\PresensiTahunanUserController@list')->name('report.presensiTahunanUser.list');
        Route::get('presensi-tahunan-user/download', 'Admin\Report\PresensiTahunanUserController@export')->name('report.presensiTahunanUser.download');

        Route::get('presensi-bulanan-user', 'Admin\Report\PresensiBulananUserController@index')->name('report.presensiBulananUser');
        Route::get('presensi-bulanan-user/list', 'Admin\Report\PresensiBulananUserController@list')->name('report.presensiBulananUser.list');
        Route::get('presensi-bulanan-user/download', 'Admin\Report\PresensiBulananUserController@export')->name('report.presensiBulananUser.download');

        Route::get('presensi-user', 'Admin\Report\PresensiUserController@index')->name('report.presensiUser');
        Route::get('presensi-user/list', 'Admin\Report\PresensiUserController@list')->name('report.presensiUser.list');
        Route::get('presensi-user/download', 'Admin\Report\PresensiUserController@export')->name('report.presensiUser.download');

        Route::get('cuti-user', 'Admin\Report\CutiUserController@index')->name('report.cutiUser');
        Route::get('cuti-user/list', 'Admin\Report\CutiUserController@list')->name('report.cutiUser.list');
        Route::get('cuti-user/download', 'Admin\Report\CutiUserController@export')->name('report.cutiUser.download');
    });
});
