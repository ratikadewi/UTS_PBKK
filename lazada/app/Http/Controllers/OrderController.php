<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {

        $dataOrder = Orders::all();
        return response()->json($dataOrder, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $order = Orders::findOrFail($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Orders tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|string',
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
            'message' => 'Data orders berhasil ditambahkan.',
            'data' => $order
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $order = Orders::findOrFail($id);

            $request->validate([
                'customer_id' => 'sometimes|string',
                'order_date' => 'required|string|date',
                'total_amount' => 'required|integer',
                'status' => 'required|string',
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['customer','order_date', 'total_amount', 'status']);

            $order->update($data);


            return response()->json([
                'message' => $order->wasChanged()
                    ? 'Data orders berhasil diupdate.'
                    : 'Tidak ada perubahan pada data orders.',
                'data' => $order
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Orders tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = Orders::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Orders berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Orders tidak ditemukan.'], 404);
        }
    }
}
