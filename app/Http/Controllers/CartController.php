<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the cart.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product', 'product.images', 'product.category')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $totalItems = $cartItems->sum('quantity');

        return view('cart.index', compact('cartItems', 'subtotal', 'totalItems'));
    }

    /**
     * Get cart count for AJAX (untuk cart badge)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => true,
                    'count' => 0,
                    'message' => 'User tidak login'
                ]);
            }

            $count = Cart::where('user_id', Auth::id())->sum('quantity');
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Cart count berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'message' => 'Gagal mengambil data cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart details for AJAX (lengkap dengan subtotal)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => true,
                    'count' => 0,
                    'subtotal' => 0,
                    'items' => []
                ]);
            }

            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product', 'product.images')
                ->get();

            $count = $cartItems->sum('quantity');
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $items = $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'price_formatted' => 'Rp ' . number_format($item->product->price, 0, ',', '.'),
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                    'subtotal_formatted' => 'Rp ' . number_format($item->product->price * $item->quantity, 0, ',', '.'),
                    'image' => $item->product->images->isNotEmpty() 
                        ? asset('storage/' . $item->product->images->first()->image_path)
                        : asset('images/no-image.jpg'),
                    'stock' => $item->product->stock
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $count,
                'subtotal' => $subtotal,
                'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'subtotal' => 0,
                'items' => [],
                'message' => 'Gagal mengambil data cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'requires_login' => true,
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);

            // Cek stok
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'error' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock
                ], 400);
            }

            // Cek apakah produk sudah ada di cart
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $request->quantity;
                
                // Cek stok untuk quantity baru
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Stok tidak mencukupi. Anda sudah memiliki ' . $existingCart->quantity . ' item di keranjang. Maksimal dapat menambah ' . ($product->stock - $existingCart->quantity) . ' item lagi.'
                    ], 400);
                }

                $existingCart->update([
                    'quantity' => $newQuantity
                ]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }

            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Cart $cart)
    {
        try {
            if ($cart->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized'
                ], 403);
            }

            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            if ($cart->product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'error' => 'Stok tidak mencukupi. Stok tersedia: ' . $cart->product->stock
                ], 400);
            }

            $cart->update([
                'quantity' => $request->quantity
            ]);

            // Hitung subtotal item
            $itemSubtotal = $cart->product->price * $cart->quantity;
            
            // Hitung total seluruh cart
            $cartTotal = Cart::where('user_id', Auth::id())
                ->get()
                ->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

            // Hitung total quantity
            $totalQuantity = Cart::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate',
                'item_subtotal' => $itemSubtotal,
                'item_subtotal_formatted' => 'Rp ' . number_format($itemSubtotal, 0, ',', '.'),
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
                'cart_count' => $totalQuantity
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart.
     * 
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cart $cart)
    {
        try {
            if ($cart->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized'
                ], 403);
            }

            $cart->delete();

            // Hitung total quantity setelah hapus
            $totalQuantity = Cart::where('user_id', Auth::id())->sum('quantity');
            
            // Hitung total harga setelah hapus
            $cartTotal = Cart::where('user_id', Auth::id())
                ->get()
                ->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang',
                'cart_count' => $totalQuantity,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized'
                ], 401);
            }

            Cart::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan',
                'cart_count' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}