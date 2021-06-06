<?php
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

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('login', 'AuthController@index')->name('login-index');
Route::get('logout', 'AuthController@logout')->name('logout');
Route::post('login', 'AuthController@login')->name('login');


Route::middleware(['auth'])->group(function() {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::group(['prefix' => 'pelanggan'], function() {
        Route::get('/', 'PelangganController@index')->name('pelanggan');
        Route::get('create', 'PelangganController@create')->name('pelanggan.create');
        Route::get('edit/{id?}', 'PelangganController@edit')->name('pelanggan.edit');
        Route::get('delete/{id?}', 'PelangganController@delete')->name('pelanggan.delete');
        Route::post('update/{id?}', 'PelangganController@update')->name('pelanggan.update');
        Route::post('store', 'PelangganController@store')->name('pelanggan.store');
        Route::get('get', 'PelangganController@get')->name('pelanggan.get');
        Route::get('view/{id?}', 'PelangganController@view')->name('pelanggan.view');
        Route::get('detail', 'PelangganController@detail')->name('pelanggan.detail');
        Route::get('riwayat', 'PelangganController@riwayat')->name('pelanggan.riwayat');
        Route::get('get_pemasangan', 'PelangganController@get_pemasangan')->name('pelanggan.get_pemasangan');
    });
    
    Route::group(['prefix' => 'deposit'], function() {
        Route::get('/', 'DepositController@index')->name('deposit');
        Route::get('create', 'DepositController@create')->name('deposit.create');
        Route::get('edit/{id?}', 'DepositController@edit')->name('deposit.edit');
        Route::get('delete/{id?}', 'DepositController@delete')->name('deposit.delete');
        Route::post('update/{id?}', 'DepositController@update')->name('deposit.update');
        Route::post('store', 'DepositController@store')->name('deposit.store');
        Route::get('get', 'DepositController@get')->name('deposit.get');
        Route::get('view/{id?}', 'DepositController@view')->name('deposit.view');
    });
    
    Route::group(['prefix' => 'pembayaran'], function() {
        Route::get('/', 'PembayaranController@index')->name('pembayaran');
        Route::get('create', 'PembayaranController@create')->name('pembayaran.create');
        Route::get('edit/{id?}', 'PembayaranController@edit')->name('pembayaran.edit');
        Route::get('delete/{id?}', 'PembayaranController@delete')->name('pembayaran.delete');
        Route::post('update/{id?}', 'PembayaranController@update')->name('pembayaran.update');
        Route::post('store', 'PembayaranController@store')->name('pembayaran.store');
        Route::get('get', 'PembayaranController@get')->name('pembayaran.get');
        Route::get('get_pembayaran', 'PembayaranController@get_pembayaran')->name('pembayaran.get_pembayaran');
    });
    
    Route::group(['prefix' => 'pemasangan'], function() {
        Route::get('/', 'PemasanganController@index')->name('pemasangan');
        Route::get('create', 'PemasanganController@create')->name('pemasangan.create');
        Route::get('edit/{id?}', 'PemasanganController@edit')->name('pemasangan.edit');
        Route::get('delete/{id?}', 'PemasanganController@delete')->name('pemasangan.delete');
        Route::post('update/{id?}', 'PemasanganController@update')->name('pemasangan.update');
        Route::post('store', 'PemasanganController@store')->name('pemasangan.store');
        Route::get('get', 'PemasanganController@get')->name('pemasangan.get');
        Route::get('get_pemasangan', 'PemasanganController@get_pemasangan')->name('pemasangan.get_pemasangan');
        Route::get('get_id_pemasangan', 'PemasanganController@get_id_pemasangan')->name('pemasangan.get_id_pemasangan');
    });
    
    Route::group(['prefix' => 'UserService'], function() {
        Route::get('/', 'UserServiceController@index')->name('UserService');
        Route::get('create', 'UserServiceController@create')->name('UserService.create');
        Route::get('get', 'UserServiceController@get')->name('UserService.get');
        Route::get('edit/{id?}', 'UserServiceController@edit')->name('UserService.edit');
        Route::get('delete/{id?}', 'UserServiceController@delete')->name('UserService.delete');
        Route::post('update/{id?}', 'UserServiceController@update')->name('UserService.update');
        Route::post('store', 'UserServiceController@store')->name('UserService.store');
        Route::get('change-password', 'UserServiceController@showChangePassword');
        Route::post('change-password', 'UserServiceController@changePassword')->name('change.password');
    });

});
