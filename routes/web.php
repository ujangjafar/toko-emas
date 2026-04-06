<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController; // Untuk user orders
use App\Http\Controllers\PaymentController; // <-- TAMBAHKAN INI
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Routes that require authentication
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard (dikomentari karena tidak digunakan - langsung ke homepage)
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Profile Routes (dari Breeze)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | Cart Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index'); // Tampilkan cart
        Route::post('/', [CartController::class, 'store'])->name('store'); // Tambah item
        Route::get('/count', [CartController::class, 'getCount'])->name('count'); // Hitung item (AJAX)
        Route::get('/details', [CartController::class, 'getDetails'])->name('details'); // Detail cart (AJAX)
        Route::put('/{cart}', [CartController::class, 'update'])->name('update'); // Update quantity
        Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy'); // Hapus item
        Route::post('/clear', [CartController::class, 'clear'])->name('clear'); // Kosongkan cart
    });
    
    /*
    |--------------------------------------------------------------------------
    | Checkout Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index'); // Form checkout
        Route::post('/', [CheckoutController::class, 'store'])->name('store'); // Proses checkout
    });
    
    /*
    |--------------------------------------------------------------------------
    | Payment Routes (TAMBAHAN BARU)
    |--------------------------------------------------------------------------
    */
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'index'])->name('index'); // Halaman pembayaran
        Route::post('/{order}/process', [PaymentController::class, 'process'])->name('process'); // Proses pembayaran
        Route::get('/{order}/success', [PaymentController::class, 'success'])->name('success'); // Halaman sukses
        Route::get('/{order}/failed', [PaymentController::class, 'failed'])->name('failed'); // Halaman gagal
        Route::post('/{order}/confirm', [PaymentController::class, 'confirm'])->name('confirm'); // Konfirmasi manual (untuk transfer)
        Route::get('/{order}/instructions', [PaymentController::class, 'instructions'])->name('instructions'); // Instruksi pembayaran
    });
    
    /*
    |--------------------------------------------------------------------------
    | Orders Routes untuk USER
    |--------------------------------------------------------------------------
    */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'userOrders'])->name('index'); // History order user
        Route::get('/{order}', [OrderController::class, 'show'])->name('show'); // Detail order
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel'); // Batalkan order
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice'); // Cetak invoice
        Route::post('/{order}/payment-proof', [OrderController::class, 'uploadPaymentProof'])->name('payment-proof'); // Upload bukti bayar
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Memerlukan Auth + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Admin Categories Management
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);
    
    /*
    |--------------------------------------------------------------------------
    | Admin Products Management
    |--------------------------------------------------------------------------
    */
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/images/{image}', [AdminProductController::class, 'deleteImage'])
        ->name('products.images.delete'); // Hapus gambar produk
    
    /*
    |--------------------------------------------------------------------------
    | Admin Orders Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index'); // List semua order
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show'); // Detail order
        Route::put('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('status'); // Update status
        Route::post('/{order}/payment-confirmation', [AdminOrderController::class, 'confirmPayment'])
            ->name('payment-confirm'); // Konfirmasi pembayaran
        Route::get('/export', [AdminOrderController::class, 'export'])->name('export'); // Export laporan
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin Payment Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'payments'])->name('index'); // Daftar pembayaran
        Route::get('/{payment}', [AdminOrderController::class, 'paymentDetail'])->name('show'); // Detail pembayaran
        Route::post('/{payment}/verify', [AdminOrderController::class, 'verifyPayment'])->name('verify'); // Verifikasi pembayaran
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin Reports (Optional)
    |--------------------------------------------------------------------------
    */
    Route::get('/reports/sales', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/products', [DashboardController::class, 'productsReport'])->name('reports.products');
    Route::get('/reports/payments', [DashboardController::class, 'paymentsReport'])->name('reports.payments');
});

/*
|--------------------------------------------------------------------------
| Payment Callback Routes (Public - Tidak perlu auth)
|--------------------------------------------------------------------------
*/
Route::prefix('payment')->name('payment.')->group(function () {
    // Callback dari payment gateway eksternal
    Route::post('/callback/qris', [PaymentController::class, 'qrisCallback'])->name('callback.qris');
    Route::post('/callback/transfer', [PaymentController::class, 'transferCallback'])->name('callback.transfer');
    Route::post('/callback/card', [PaymentController::class, 'cardCallback'])->name('callback.card');
    
    // Webhook untuk payment gateway
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
    
    // Notifikasi pembayaran (untuk testing)
    Route::get('/notification/{order}', [PaymentController::class, 'notification'])->name('notification');
});

/*
|--------------------------------------------------------------------------
| API Routes untuk AJAX (Optional)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->middleware(['auth'])->group(function () {
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::get('/cart/details', [CartController::class, 'getDetails'])->name('cart.details');
    Route::post('/payment/{order}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (dari Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';