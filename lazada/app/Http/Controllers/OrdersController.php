<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdersController extends Controller
{
      public function index(): JsonResponse
    {

        $dataOrder = Orders::with('customer')->get();
        return response()->json($dataOrder, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $order = Orders::with(['customer', 'orderItems.product'])->findOrFail($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Orders tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|string|max:255',
            'order_date' => 'required|string|date',
            'total_amount' => 'required|integer',
            'status' => 'required|string',

        ]);

        $order = Orders::create([
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'total_amount' => $request->total_amount,
            'status' => $request->status,

        ]);


        return response()->json([
            'message' => 'Data order berhasil ditambahkan.',
            'data' => $order
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $order = Orders::findOrFail($id);

            $request->validate([
                'customer_id' => 'sometimes|string|max:255',
                'order_date' => 'sometimes|string|date',
                'total_amount' => 'sometimes|integer',
                'status' => 'sometimes|string',

            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['customer_id', 'order_date', 'total_amount', 'status']);

            $order->update($data);


            return response()->json([
                'message' => $order->wasChanged()
                    ? 'Data order berhasil diupdate.'
                    : 'Tidak ada perubahan pada data order.',
                'data' => $order
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = Orders::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }


public function recalculateTotal($id): JsonResponse
{
    try {
        $order = Orders::findOrFail($id);

        // Ambil semua item order berdasarkan order_id
        $items = OrderItems::where('order_id', $id)->get();

        // Hitung ulang total dengan menjumlahkan subtotal dari tiap item
        $total = $items->sum('subtotal');

        // Simpan total ke kolom total_amount
        $order->total_amount = $total;
        $order->save();

        return response()->json([
            'message' => 'Total order berhasil diperbarui.',
            'total_amount' => $total
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Order tidak ditemukan.'], 404);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan saat menghitung total.',
            'error' => $e->getMessage()
        ], 500);
    }
}
        public function count()
    {
        $count = \App\Models\Orders::count();

        return response()->json([
            'total' => $count
        ]);
    }

}
