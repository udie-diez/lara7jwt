<?php

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

Auth::routes();
 
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/data/{kode}', 'HomeController@data'); 

//register
Route::get('/register/create','RegisterCont@create');
Route::post('/register/create', 'RegisterCont@store')->name('createRegister');
Route::get('/register','RegisterCont@index')->name('daftarRegister');
Route::post('/register/update','RegisterCont@update')->name('updateRegister');
Route::get('/register/{id}','RegisterCont@edit');

Route::get('/users','UserController@index')->name('users');
Route::get('/users/{id}','UserController@edit');
Route::post('/users/update','UserController@update')->name('updateUsers');
Route::get('/users/create','UserController@create')->name('createUser');

//anggota
Route::get('/anggota','AnggotaCont@index')->name('anggota');
Route::get('/anggota/profile','AnggotaCont@profile')->name('profileanggota');
Route::get('/anggota/create','AnggotaCont@create')->name('createAnggota');
Route::post('/anggota/store','AnggotaCont@store')->name('storeAnggota');
Route::post('/anggota/update','AnggotaCont@update')->name('updateAnggota');
Route::get('/anggota/destroy/{id}','AnggotaCont@destroy');
Route::get('/anggota/{id}','AnggotaCont@edit');


//pengurus
Route::get('/pengurus','PengurusCont@index')->name('pengurus');
Route::get('/pengurus/create','PengurusCont@create')->name('createPengurus');
Route::post('/pengurus/store','PengurusCont@store')->name('storePengurus');
Route::post('/pengurus/update','PengurusCont@update')->name('updatePengurus');
Route::get('/pengurus/destroy/{id}','PengurusCont@destroy');
Route::get('/pengurus/{id}','PengurusCont@edit');

//pajak
Route::get('/pajak','PajakCont@index')->name('pajak');
Route::get('/pajak/create','PajakCont@create')->name('createPajak');
Route::post('/pajak/store','PajakCont@store')->name('storePajak');
Route::post('/pajak/update','PajakCont@update')->name('updatePajak');
Route::get('/pajak/destroy/{id}','PajakCont@destroy');
Route::get('/pajak/{id}','PajakCont@edit');

//Pengelola
Route::get('/pengelola','PengelolaCont@index')->name('pengelola');
Route::get('/pengelola/create','PengelolaCont@create')->name('createPengelola');
Route::post('/pengelola/store','PengelolaCont@store')->name('storePengelola');
Route::post('/pengelola/update','PengelolaCont@update')->name('updatePengelola');
Route::get('/pengelola/destroy/{id}','PengelolaCont@destroy');
Route::get('/pengelola/{id}','PengelolaCont@edit');

//jenis_simpanan
Route::get('/jenis_simpanan','JenisSimpananCont@index')->name('jenis_simpanan');
Route::get('/jenis_simpanan/create','JenisSimpananCont@create')->name('createJenisSimpanan');
Route::post('/jenis_simpanan/store','JenisSimpananCont@store')->name('storeJenisSimpanan');
Route::post('/jenis_simpanan/update','JenisSimpananCont@update')->name('updateJenisSimpanan');
Route::get('/jenis_simpanan/destroy/{id}','JenisSimpananCont@destroy');
Route::get('/jenis_simpanan/{id}','JenisSimpananCont@edit');

//sumber_pinjaman
Route::get('/sumber_pinjaman','SumberPinjamanCont@index')->name('sumber_pinjaman');
Route::get('/sumber_pinjaman/create','SumberPinjamanCont@create')->name('createSumberPinjaman');
Route::post('/sumber_pinjaman/store','SumberPinjamanCont@store')->name('storeSumberPinjaman');
Route::post('/sumber_pinjaman/update','SumberPinjamanCont@update')->name('updateSumberPinjaman');
Route::get('/sumber_pinjaman/destroy/{id}','SumberPinjamanCont@destroy');
Route::get('/sumber_pinjaman/{id}','SumberPinjamanCont@edit');

//simpanan
Route::get('/simpanan','SimpananCont@index')->name('simpanan');
Route::post('/simpanan/filter','SimpananCont@filter')->name('filterSimpanan');

//setoran
Route::get('/setoran','SetoranCont@index')->name('setoran');
Route::get('/setoran/create','SetoranCont@create');
Route::get('/setoran/import','SetoranCont@import');
Route::get('/setoran/destroy/{id}','SetoranCont@destroy');
Route::post('/setoran/update','SetoranCont@update')->name('updateSetoran');
Route::post('/setoran/store','SetoranCont@store')->name('storeSetoran');
Route::post('/setoran/filter','SetoranCont@filter')->name('filterSetoran');
Route::get('/setoran/{id}','SetoranCont@edit');

//pinjaman
Route::get('/pinjaman','PinjamanCont@index')->name('pinjaman');
Route::get('/pinjaman/daftarpermohonan','PinjamanCont@daftarPermohonan')->name('daftarPermohonan');
Route::get('/pinjaman/daftarpeminjam','PinjamanCont@daftarPeminjam')->name('daftarPeminjam');
Route::get('/pinjaman/daftarpelunasan','PinjamanCont@daftarPelunasan')->name('daftarPelunasan');
Route::get('/pinjaman/daftarangsuran','PinjamanCont@daftarAngsuran')->name('daftarAngsuran');
Route::get('/pinjaman/daftartunggakan','PinjamanCont@daftarTunggakan')->name('daftarTunggakan');
Route::get('/ajukanpinjaman','PinjamanCont@pengajuan')->name('pinjamanInput');
Route::post('/pinjaman/store','PinjamanCont@store')->name('storePinjaman');
Route::post('/pinjaman/update','PinjamanCont@update')->name('updatePinjaman');
Route::get('/pinjaman/bayarangsuran','PinjamanCont@bayarAngsuran')->name('bayarAngsuran');
Route::get('/pinjaman/showangsuran/{id}','PinjamanCont@showAngsuran')->name('showAngsuran');
Route::get('/pinjaman/destroyangsuran/{id}','PinjamanCont@destroyAngsuran');
Route::get('/pinjaman/destroy/{id}','PinjamanCont@destroyPinjaman')->name('pinjamanDestroy');

Route::post('/pinjaman/filterangsuran','PinjamanCont@filterAngsuran')->name('filterAngsuran');

Route::get('/pinjaman/getsimpanan/{id}','PinjamanCont@getSimpanan');
Route::get('/pinjaman/getpinjaman/{id}','PinjamanCont@getPinjaman');
Route::get('/pinjaman/show/{id}','PinjamanCont@show');
Route::get('/pinjaman/detail/{id}/','PinjamanCont@detail');
Route::get('/pinjaman/edit/{id}/','PinjamanCont@editPinjaman')->name('editPinjaman');
Route::post('/pinjaman/filter','PinjamanCont@filter')->name('filterPinjaman');
Route::post('/pinjaman/info','PinjamanCont@info')->name('detailPeminjam');
Route::post('/pinjaman/updateangsuran','PinjamanCont@updateAngsuran')->name('updateAngsuranPinjaman');

//pelunasan
Route::post('/pinjaman/updatepelunasan','PinjamanCont@updatePelunasan')->name('updatePelunasanPinjaman');
Route::get('/pinjaman/bayarpelunasan','PinjamanCont@bayarPelunasan')->name('bayarPelunasan');
Route::get('/pinjaman/showpelunasan/{id}/{nilai}','PinjamanCont@showPelunasan')->name('showPelunasan');
Route::get('/pinjaman/destroypelunasan/{id}','PinjamanCont@destroyPelunasan');
Route::post('/pinjaman/filterpelunasan','PinjamanCont@filterPelunasan')->name('filterPelunasan');
Route::get('/pinjaman/{id}','PinjamanCont@edit')->name('dataPermohonan');

//upload
Route::post('/home/upload','HomeController@upload')->name('uploadProses');

//rekon payroll
Route::get('/rekon','RekonController@index')->name('rekon');
Route::post('/home/rekon/upload','HomeController@uploadrekon')->name('uploadrekon');
Route::get('/rekon/simpanan/{periode}','RekonController@simpanan')->name('prosesPayrollSimpanan');
Route::get('/rekon/angsuran/{periode}','RekonController@angsuran')->name('prosesPayrollAngsuran');
Route::post('/rekon/cekdata','RekonController@cekDataRekon')->name('cekDataRekon');

Route::post('/home/simulasi','HomeController@simulasi')->name('simulasiKredit');
Route::get('/payroll/potongan','RekonController@potonganPayroll')->name('potonganPayroll');
Route::post('/payroll/filter','RekonController@filterPotongan')->name('filterPotongan');


//perusahaan
Route::get('/perusahaan','PerusahaanCont@index')->name('perusahaan');
Route::get('/perusahaan/create','PerusahaanCont@create')->name('createPerusahaan');
Route::post('/perusahaan/store','PerusahaanCont@store')->name('storePerusahaan');
Route::post('/perusahaan/update','PerusahaanCont@update')->name('updatePerusahaan');
Route::get('/perusahaan/destroy/{id}','PerusahaanCont@destroy');
Route::get('/perusahaan/{id}','PerusahaanCont@edit');

//Vendor
Route::get('/vendor','VendorCont@index')->name('vendor');
Route::get('/vendor/create','VendorCont@create')->name('createVendor');
Route::post('/vendor/store','VendorCont@store')->name('storeVendor');
Route::post('/vendor/update','VendorCont@update')->name('updateVendor');
Route::get('/vendor/destroy/{id}','VendorCont@destroy');
Route::get('/vendor/{id}','VendorCont@edit');

//Produk
Route::get('/produk','ProdukCont@index')->name('produk');
Route::get('/produk/create','ProdukCont@create')->name('createProduk');
Route::post('/produk/update','ProdukCont@update')->name('updateProduk');
Route::get('/produk/destroy/{id}','ProdukCont@destroy');
Route::get('/produk/{id}','ProdukCont@edit');


//project
Route::get('/project','ProjectCont@index')->name('project');
Route::get('/project/createAM/{id}','ProjectCont@createAM')->name('createAM');
Route::post('/project/updateAM','ProjectCont@updateAM')->name('updateAM');
Route::get('/project/create','ProjectCont@create')->name('createProject');
Route::post('/project/store','ProjectCont@store')->name('storeProject');
Route::post('/project/update','ProjectCont@update')->name('updateProject');
Route::get('/project/show/{id}','ProjectCont@show')->name('showProject');
Route::get('/project/destroy/{id}','ProjectCont@destroy');
Route::get('/project/createItem','ProjectCont@createItem');
Route::post('/project/updateItem','ProjectCont@updateItem')->name('updateItem');
Route::get('/project/destroyitem/{id}','ProjectCont@destroyItem');
Route::post('/project/info','ProjectCont@infoProject')->name('infoProject');
Route::post('/project/filter','ProjectCont@filterProject')->name('filterProject');

//am

Route::get('/project/createAM/{id}','ProjectCont@createAM')->name('createAM');
Route::post('/project/updateAM','ProjectCont@updateAM')->name('updateAM');
Route::get('/project/showAM/{id}','ProjectCont@showAM')->name('showAM');
Route::get('/project/destroyAM/{id}','ProjectCont@destroyAM');

//invoice
Route::get('/invoice','ProjectCont@invoice')->name('invoice');
Route::get('/project/createInvoice','ProjectCont@createInvoice')->name('createInvoice');
Route::post('/project/updateInvoice','ProjectCont@updateInvoice')->name('updateInvoice');
Route::get('/project/destroyInvoice/{id}','ProjectCont@destroyInvoice');
Route::get('/project/batalInvoice/{id}','ProjectCont@batalInvoice')->name('batalInvoice');
Route::post('/project/updatePembatalan','ProjectCont@updatePembatalan')->name('updatePembatalan');
Route::get('/project/previewInvoice/{id}','ProjectCont@previewInvoice')->name('previewInvoice');
Route::get('/project/printInvoice/{id}','ProjectCont@printInvoice')->name('printInvoice');
Route::get('/project/printSPB/{id}','ProjectCont@printSPB')->name('printSPB');
Route::get('/project/printTT/{id}','ProjectCont@printTT')->name('printTT');
Route::get('/project/printSJ/{id}','ProjectCont@printSJ')->name('printSJ');
Route::get('/project/printBA/{id}','ProjectCont@printBA')->name('printBA');
Route::get('/project/printKwitansi/{id}','ProjectCont@printKwitansi')->name('printKwitansi');
Route::get('/project/printBAPPKontrak/{id}','ProjectCont@printBAPPKontrak')->name('printBAPPKontrak');
Route::get('/project/printBAUTKontrak/{id}','ProjectCont@printBAUTKontrak')->name('printBAUTKontrak');
Route::get('/project/printBAPPGSD/{id}','ProjectCont@printBAPPGSD')->name('printBAPPGSD');



Route::post('/project/filterInvoice','ProjectCont@filterInvoice')->name('filterInvoice');

Route::get('/panjar','ProjectCont@panjar')->name('panjar');
Route::get('/project/createPanjar','ProjectCont@createPanjar')->name('createPanjar');
Route::post('/project/updatePanjar','ProjectCont@updatePanjar')->name('updatePanjar');
Route::get('/project/destroyPanjar/{id}','ProjectCont@destroyPanjar');
Route::post('/project/infopanjar','ProjectCont@infoProjectPanjar')->name('infoProjectPanjar');
Route::get('/project/showpanjar/{id}','ProjectCont@showPanjar')->name('showPanjar');
Route::get('/project/editPanjar/{id}','ProjectCont@editPanjar')->name('editPanjar');
Route::get('/project/printPanjar/{id}','ProjectCont@printPanjar')->name('printPanjar');

Route::get('/project/invoice/{id}','ProjectCont@editInvoice')->name('editInvoice');
Route::get('/project/invoice/show/{id}','ProjectCont@showInvoice')->name('showInvoice');
Route::get('/project/item/{id}','ProjectCont@editItem');

//dashboard project
Route::get('/project/dashboard','ProjectCont@dashboard')->name('projectDb');
Route::post('/project/dashboard/filter','ProjectCont@filterDashboard')->name('filterDashboard');
Route::get('/project/dashboard/listproject/{jenis}','ProjectCont@dashboardProjectList')->name('projectListDbl');
Route::get('/project/dashboardpegawai/{id}','ProjectCont@dashboardpegawai')->name('projectDbpegawai');



//pembayaran
Route::get('/project/createPembayaran/{id}','PembayaranCont@create')->name('createPembayaran');
Route::get('/project/showPembayaran/{id}','PembayaranCont@show')->name('showPembayaran');
Route::get('/project/editPembayaran/{id}','PembayaranCont@edit')->name('editPembayaran');
Route::get('/project/destroyPembayaran/{id}','PembayaranCont@destroy')->name('destroyPembayaran');
Route::post('/project/updatePembayaran','PembayaranCont@update')->name('updatePembayaran');

//panjar
Route::get('/project/panjar/pengunaan/{id}','ProjectCont@createPengunaanPanjar')->name('createPengunaanPanjar');
Route::post('/project/panjar/updatepenggunaan','ProjectCont@updatePenggunaanPanjar')->name('updatePenggunaanPanjar');
Route::get('/project/panjar/destroyPenggunaanPanjar/{id}','ProjectCont@destroyPenggunaanPanjar')->name('destroyPenggunaanPanjar');
Route::get('/project/panjar/showPenggunaanPanjar/{id}','ProjectCont@showPenggunaanPanjar')->name('showPenggunaanPanjar');


Route::get('/project/{id}','ProjectCont@edit')->name('editProject');

//pemesan
Route::get('/pemesan','PemesanCont@index')->name('pemesan');
Route::get('/pemesan/create','PemesanCont@create')->name('createPemesan');
Route::post('/pemesan/update','PemesanCont@update')->name('updatePemesan');
Route::get('/pemesan/destroy/{id}','PemesanCont@destroy')->name('destroyPemesan');
Route::get('/pemesan/{id}','PemesanCont@show')->name('showPemesan');


//target
Route::get('/target','TargetCont@index')->name('target');
Route::get('/target/create','TargetCont@create')->name('createTarget');
Route::post('/target/update','TargetCont@update')->name('updateTarget');
Route::get('/target/{id}','TargetCont@edit')->name('editTarget');;

//pembelian
Route::get('/pembelian','PembelianCont@index')->name('pembelian');
Route::get('/pembelian/create','PembelianCont@create')->name('createPembelian');
Route::post('/pembelian/update','PembelianCont@update')->name('updatePembelian');
Route::post('/pembelian/produk/update','PembelianCont@updateProduk')->name('updatePembelianProduk');

Route::get('/pembelian/destroy/{id}','PembelianCont@destroy');
Route::get('/pembelian/destroyproduk/{id}','PembelianCont@destroyProduk');
Route::get('/pembelian/edit/{id}','PembelianCont@edit')->name('editPembelian');

Route::get('/pembelian/pembayaran/{id}','PembelianCont@createPembayaran')->name('createPembelianPembayaran');
Route::post('/pembelian/pembayaran/update','PembelianCont@updatePembayaran')->name('updatePembelianPembayaran');
Route::get('/pembelian/showpembayaran/{id}','PembelianCont@showPembayaran')->name('showPembelianPembayaran');
Route::get('/pembelian/editpembayaran/{id}','PembelianCont@editPembayaran')->name('editPembelianPembayaran');
Route::get('/pembelian/destroypembayaran/{id}','PembelianCont@destroyPembayaran');
Route::get('/pembelian/cetak/{id}','PembelianCont@cetak')->name('cetakPembelian');


Route::get('/pembelian/{id}','PembelianCont@show')->name('showPembelian');

 //pq
Route::get('/purchase_quotes','PembelianPenawaranCont@index')->name('pq'); 
Route::get('/purchase_quotes/create','PembelianPenawaranCont@create')->name('create_pq');
Route::post('/purchase_quotes/update','PembelianPenawaranCont@update')->name('update_pq');
Route::get('/purchase_quotes/destroy/{id}','PembelianPenawaranCont@destroy');
Route::get('/purchase_quotes/edit/{id}','PembelianPenawaranCont@edit')->name('edit_pq');
Route::get('/purchase_quotes/destroyproduk/{id}','PembelianPenawaranCont@destroyProduk');
Route::get('/purchase_quotes/createpemesanan/{id}','PembelianPenawaranCont@createPemesanan');
Route::get('/purchase_quotes/cetak/{id}','PembelianPenawaranCont@cetak')->name('cetak_pq');

Route::get('/purchase_quotes/{id}','PembelianPenawaranCont@show')->name('show_pq');


 //po
 Route::get('/purchase_order','PembelianPemesananCont@index')->name('po'); 
 Route::get('/purchase_order/create','PembelianPemesananCont@create')->name('create_po');
 Route::post('/purchase_order/update','PembelianPemesananCont@update')->name('update_po');
 Route::get('/purchase_order/destroy/{id}','PembelianPemesananCont@destroy');
 Route::get('/purchase_order/edit/{id}','PembelianPemesananCont@edit')->name('edit_po');
Route::get('/purchase_order/destroyproduk/{id}','PembelianPemesananCont@destroyProduk');
Route::get('/purchase_order/createpembelian/{id}','PembelianPemesananCont@createPembelian');
Route::get('/purchase_order/cetak/{id}','PembelianPemesananCont@cetak')->name('cetak_po');
 
 Route::get('/purchase_order/{id}','PembelianPemesananCont@show')->name('show_po');


