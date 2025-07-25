<?php

namespace App\Http\Controllers;

use App\Models\OrdersItems;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdersItemsController extends Controller
{
    public function index(): JsonResponse
    {

        $dataBarang = OrdersItems::all();
        return response()->json($dataBarang, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $barang = OrdersItems::findOrFail($id);
            return response()->json($barang, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Item tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
            'product_id' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
        ]);

        $barang = OrdersItems::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);


        return response()->json([
            'message' => 'Order Items berhasil ditambahkan.',
            'data' => $barang
        ], 201);
    }

      // Mengupdate data user
      public function update(Request $request, $id): JsonResponse
      {
          try {
              $barang = OrdersItems::findOrFail($id);
  
              $request->validate([
                'order_id' => 'sometimes|string',
                'product_id' => 'sometimes|string',
                'quantity' => 'sometimes|integer',
                'price' => 'sometimes|integer',
            ]);
  
              // Hanya update field yang dikirim
              $data = $request->only(['order_id','product_id','quantity', 'price']);

              $barang->update($data);
              
  
              return response()->json([
                  'message' => $barang->wasChanged()
                      ? 'Data Order Items berhasil diupdate.'
                      : 'Tidak ada perubahan pada data Order Items.',
                  'data' => $barang
              ], 200);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Order Items tidak ditemukan'], 404);
          }
      }
  
      public function destroy($id): JsonResponse
      {
          try {
              $barang = OrdersItems::findOrFail($id);
              $barang->delete();
  
              return response()->json(['message' => 'Data Order Items berhasil dihapus.']);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Order Items tidak ditemukan.'], 404);
          }
      }
}