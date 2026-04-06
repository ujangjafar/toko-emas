<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display checkout page.
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product', 'product.images')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Process checkout.
     */
    public function store(Request $request)
    {
        // Validasi dengan metode pembayaran
        $request->validate([
            'address' => 'required|string|min:10',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'payment_method' => 'required|in:qris,transfer,card', // Validasi metode pembayaran
            'notes' => 'nullable|string|max:500',
            'email' => 'nullable|email'
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong');
        }

        // Validate stock
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', 
                    "Stok {$item->product->name} tidak mencukupi. Tersedia: {$item->product->stock}"
                )->withInput();
            }
        }

        DB::beginTransaction();

        try {
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Generate nomor invoice unik
            $invoiceNumber = 'INV/' . date('Ymd') . '/' . strtoupper(uniqid());

            // Simpan order dengan metode pembayaran
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_number' => $invoiceNumber,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending', // pending, paid, failed
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email ?? Auth::user()->email,
                'notes' => $request->notes,
                'order_date' => now()
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name, // Simpan nama produk untuk history
                    'product_sku' => $item->product->sku, // Simpan SKU
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Redirect ke halaman sukses dengan pesan sesuai metode pembayaran
            $paymentMessage = $this->getPaymentInstructions($request->payment_method);
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!')
                ->with('payment_info', $paymentMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 
                'Terjadi kesalahan: ' . $e->getMessage()
            )->withInput();
        }
    }

    /**
     * Display order detail.
     */
    public function show(Order $order)
    {
        // Cek kepemilikan order
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load('items');

        return view('orders.show', compact('order'));
    }

    /**
     * Get payment instructions based on payment method.
     */
    private function getPaymentInstructions($method)
    {
        $instructions = [
            'qris' => [
                'title' => 'Pembayaran via QRIS',
                'steps' => [
                    'Buka aplikasi e-wallet atau mobile banking Anda',
                    'Pilih menu scan QRIS / QR Code',
                    'Scan kode QR yang tersedia',
                    'Konfirmasi jumlah pembayaran',
                    'Masukkan PIN untuk menyelesaikan pembayaran'
                ]
            ],
            'transfer' => [
                'title' => 'Pembayaran via Transfer Bank',
                'steps' => [
                    'Lakukan transfer ke salah satu rekening berikut:',
                    'BCA: 1234567890 a.n. PT EMAS PREMIUM',
                    'Mandiri: 123-00-1234567-8 a.n. PT EMAS PREMIUM',
                    'BNI: 0123456789 a.n. PT EMAS PREMIUM',
                    'BRI: 1234-01-012345-67-8 a.n. PT EMAS PREMIUM',
                    'Konfirmasi pembayaran melalui WhatsApp 081234567890'
                ]
            ],
            'card' => [
                'title' => 'Pembayaran via Kartu Kredit/Debit',
                'steps' => [
                    'Anda akan diarahkan ke halaman pembayaran aman',
                    'Masukkan detail kartu Anda',
                    'Masukkan kode OTP yang dikirim ke nomor Anda',
                    'Pembayaran akan diproses secara real-time'
                ]
            ]
        ];

        return $instructions[$method] ?? null;
    }

    /**
     * Cancel order (for user).
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses');
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Dibatalkan oleh customer'
            ]);

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * List user orders.
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Retry payment (for pending orders).
     */
    public function retryPayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses');
        }

        // Redirect ke halaman pembayaran sesuai metode
        return redirect()->route('payment.process', $order);
    }
}