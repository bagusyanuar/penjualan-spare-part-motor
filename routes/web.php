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

Route::group(['prefix' => 'admin'], function () {
//    Route::match(['post', 'get'],'/check-midtrans', [App\Http\Controllers\Midtrans\CheckController::class, 'index']);
    Route::match(['post', 'get'], '/', [\App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login');
    Route::get( '/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');

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

    Route::group(['prefix' => 'setting-kredit'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingKreditController::class, 'index'])->name('admin.setting-kredit');
        Route::match(['post', 'get'], '/add', [\App\Http\Controllers\Admin\SettingKreditController::class, 'add'])->name('admin.setting-kredit.add');
        Route::match(['post', 'get'], '/{id}/edit', [\App\Http\Controllers\Admin\SettingKreditController::class, 'edit'])->name('admin.setting-kredit.edit');
        Route::post('/{id}/delete', [\App\Http\Controllers\Admin\SettingKreditController::class, 'delete'])->name('admin.setting-kredit.delete');
    });
});
