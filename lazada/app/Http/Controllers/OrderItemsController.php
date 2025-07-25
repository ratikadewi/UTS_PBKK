<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderItemsController extends Controller
{
      public function index(): JsonResponse
    {

        $dataOrderItems = OrderItems::with(['product'])->get();
        return response()->json($dataOrderItems, 200);
    }

    public function show($order_id): JsonResponse
    {
        $items = OrderItems::with('product')
            ->where('order_id', $order_id)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Order items tidak ditemukan'], 404);
        }

        return response()->json($items, 200);
    }

public function store(Request $request, $orderId): JsonResponse
{
    $items = $request->input('items', []);

    DB::beginTransaction();
    try {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Validasi stok cukup
            if ($product->quantity_product < $item['quantity']) {
                throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi.");
            }

            // Kurangi stok
            $product->quantity_product -= $item['quantity'];
            $product->save();

            OrderItems::create([
                'order_id'  => $orderId,
                'product_id'=> $item['product_id'],
                'quantity'  => $item['quantity'],
                'price'     => $product->price,
                'subtotal'  => $product->price * $item['quantity'],
            ]);
        }

        DB::commit();
        return response()->json(['message' => 'Order items berhasil disimpan'], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal menyimpan order items',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $orderitems = OrderItems::findOrFail($id);

            $request->validate([
                'order_id' => 'sometimes|string|max:255',
                'product_id' => 'sometimes|string|max:255',
                'quantity' => 'sometimes|integer|min:0',
                'price' => 'sometimes|numeric|min:0',
                'subtotal' => 'sometimes|numeric|min:0',

            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['order_id', 'product_id', 'quantity', 'price','subtotal']);

            $orderitems->update($data);


            return response()->json([
                'message' => $orderitems->wasChanged()
                    ? 'Data order items berhasil diupdate.'
                    : 'Tidak ada perubahan pada data order items.',
                'data' => $orderitems
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order items tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = OrderItems::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order Items berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Items tidak ditemukan.'], 404);
        }
    }

        public function getDetailsByOrderId($orderId): JsonResponse
    {
        try {
            $items = OrderItems::with('product')
                ->where('order_id', $orderId)
                ->get();

            return response()->json($items, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil order items'], 500);
        }
    }
}
