<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display payment page.
     */
    public function index(Order $order)
    {
        // Cek kepemilikan order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Cek status order
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini tidak dapat dibayar karena sudah diproses.');
        }

        // Cek status pembayaran
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini sudah lunas.');
        }

        return view('payment.index', compact('order'));
    }

    /**
     * Process payment (simulasi).
     */
    public function process(Request $request, Order $order)
    {
        // Cek kepemilikan order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Cek status
        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan tidak dapat diproses.');
        }

        // Simulasi proses pembayaran berdasarkan metode
        switch ($order->payment_method) {
            case 'qris':
                // Redirect ke halaman sukses (simulasi)
                return redirect()->route('payment.success', $order)
                    ->with('success', 'Pembayaran QRIS berhasil diproses (simulasi).');
                
            case 'transfer':
                // Untuk transfer, biasanya menunggu konfirmasi manual
                return redirect()->route('orders.show', $order)
                    ->with('info', 'Silakan transfer ke rekening yang tertera dan konfirmasi pembayaran.');
                
            case 'card':
                // Redirect ke halaman pembayaran kartu (simulasi)
                return redirect()->route('payment.success', $order)
                    ->with('success', 'Pembayaran kartu kredit berhasil diproses (simulasi).');
                
            default:
                return redirect()->route('orders.show', $order)
                    ->with('error', 'Metode pembayaran tidak valid.');
        }
    }

    /**
     * Payment success page.
     */
    public function success(Order $order)
    {
        // Cek kepemilikan order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Update status pembayaran menjadi paid (simulasi)
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now()
        ]);

        return view('payment.success', compact('order'));
    }

    /**
     * Payment failed page.
     */
    public function failed(Order $order)
    {
        // Cek kepemilikan order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('payment.failed', compact('order'));
    }
}