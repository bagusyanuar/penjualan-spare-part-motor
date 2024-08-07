<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\Customer\HomeController::class, 'index'])->name('customer.home');
Route::match(['post', 'get'],'/login', [\App\Http\Controllers\Customer\LoginController::class, 'login'])->name('customer.login');
Route::get('/logout', [\App\Http\Controllers\Customer\LoginController::class, 'logout'])->name('customer.logout');
Route::match(['post', 'get'],'/register', [\App\Http\Controllers\Customer\RegisterController::class, 'register'])->name('customer.register');

Route::group(['prefix' => 'akun-saya'], function () {
    Route::match(['post', 'get'], '/', [\App\Http\Controllers\Customer\AkunController::class, 'index'])->name('customer.account');
});

Route::group(['prefix' => 'product'], function () {
    Route::get('/', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customer.product');
    Route::get('/{id}', [\App\Http\Controllers\Customer\ProductController::class, 'detail'])->name('customer.product.detail');
});

Route::group(['prefix' => 'keranjang'], function () {
    Route::match(['post', 'get'], '/', [\App\Http\Controllers\Customer\KeranjangController::class, 'index'])->name('customer.cart');
    Route::post('/checkout', [\App\Http\Controllers\Customer\KeranjangController::class, 'checkout'])->name('customer.checkout');
    Route::post('/{id}/delete', [\App\Http\Controllers\Customer\KeranjangController::class, 'delete'])->name('customer.delete');

});

Route::group(['prefix' => 'pesanan'], function () {
    Route::get('/', [\App\Http\Controllers\Customer\PesananController::class, 'index'])->name('customer.order');
    Route::match(['post', 'get'],'/{id}', [\App\Http\Controllers\Customer\PesananController::class, 'detail'])->name('customer.order.detail');
    Route::match(['post', 'get'], '/{id}/pembayaran', [\App\Http\Controllers\Customer\PesananController::class, 'pembayaran'])->name('customer.order.payment');
    Route::post( '/{id}/angsuran/{instalmentID}', [\App\Http\Controllers\Customer\AngsuranController::class, 'getSnapToken'])->name('customer.order.payment.snap');
    Route::post( '/{id}/angsuran/{instalmentID}/pay', [\App\Http\Controllers\Customer\AngsuranController::class, 'pay'])->name('customer.order.payment.pay');
});

Route::group(['prefix' => 'admin'], function () {
    Route::match(['post', 'get'],'/check-midtrans', [App\Http\Controllers\Midtrans\CheckController::class, 'index']);
    Route::match(['post', 'get'], '/', [\App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login');
    Route::get('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => 'pengguna'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\PenggunaController::class, 'index'])->name('admin.pengguna');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\PenggunaController::class, 'add'])->name('admin.pengguna.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\PenggunaController::class, 'edit'])->name('admin.pengguna.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\PenggunaController::class, 'delete'])->name('admin.pengguna.delete');
    });

    Route::group(['prefix' => 'kategori'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('admin.category');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\KategoriController::class, 'add'])->name('admin.category.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\KategoriController::class, 'edit'])->name('admin.category.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\KategoriController::class, 'delete'])->name('admin.category.delete');
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.product');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\ProductController::class, 'add'])->name('admin.product.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('admin.product.delete');
    });

    Route::group(['prefix' => 'biaya-pengiriman'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\BiayaPengirimanController::class, 'index'])->name('admin.shipment');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\BiayaPengirimanController::class, 'add'])->name('admin.shipment.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\BiayaPengirimanController::class, 'edit'])->name('admin.shipment.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\BiayaPengirimanController::class, 'delete'])->name('admin.shipment.delete');
    });

    Route::group(['prefix' => 'setting-kredit'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingKreditController::class, 'index'])->name('admin.setting-kredit');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\SettingKreditController::class, 'add'])->name('admin.setting-kredit.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\SettingKreditController::class, 'edit'])->name('admin.setting-kredit.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\SettingKreditController::class, 'delete'])->name('admin.setting-kredit.delete');
    });

    Route::group(['prefix' => 'penjualan'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\PenjualanController::class, 'index'])->name('admin.penjualan');
        Route::match(['post', 'get'],'/{id}/pesanan-baru', [\App\Http\Controllers\Admin\PenjualanController::class, 'detail_process'])->name('admin.penjualan.detail.new');
        Route::match(['post', 'get'],'/{id}/selesai-packing', [\App\Http\Controllers\Admin\PenjualanController::class, 'detail_packing'])->name('admin.penjualan.detail.packing');
        Route::match(['post', 'get'],'/{id}/selesai', [\App\Http\Controllers\Admin\PenjualanController::class, 'detail_finish'])->name('admin.penjualan.detail.finish');
    });

    Route::group(['prefix' => 'angsuran'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AngsuranController::class, 'index'])->name('admin.angsuran');
        Route::get('/{id}', [\App\Http\Controllers\Admin\AngsuranController::class, 'detail'])->name('admin.angsuran.detail');
    });

    Route::group(['prefix' => 'laporan-penjualan'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan.penjualan');
        Route::get('/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'penjualan_pdf'])->name('admin.laporan.penjualan.cetak');
    });

    Route::group(['prefix' => 'laporan-angsuran'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\LaporanController::class, 'angsuran'])->name('admin.laporan.angsuran');
        Route::get('/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'angsuran_pdf'])->name('admin.laporan.angsuran.cetak');
    });
});
