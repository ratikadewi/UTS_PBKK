<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockController extends Controller
{
    public function index(): JsonResponse
    {
        $dataStock = Stock::with('product')->get();
        return response()->json($dataStock, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $stock = Stock::findOrFail($id);
            return response()->json($stock, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|string|max:255',
            'limit' => 'required|integer|max:255',
        ]);

        $stock = Stock::create([
            'product_id' => $request->product_id,
            'limit' => $request->limit,
        ]);


        return response()->json([
            'message' => 'Data stock berhasil ditambahkan.',
            'data' => $stock
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $stock = Stock::findOrFail($id);

            $request->validate([
                'product_id' => 'sometimes|string|max:255',
                'limit' => 'sometimes|integer|max:255',
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['product_id', 'limit']);

            $stock->update($data);


            return response()->json([
                'message' => $stock->wasChanged()
                    ? 'Data stock berhasil diupdate.'
                    : 'Tidak ada perubahan pada data stock.',
                'data' => $stock
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->delete();

            return response()->json(['message' => 'Stock berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Stock tidak ditemukan.'], 404);
        }
    }

    public function count()
    {
        $count = \App\Models\Stock::count();

        return response()->json([
            'total' => $count
        ]);
    }

public function restockAll(): JsonResponse
{
    try {
        $stocks = Stock::with('product')->get();

        if ($stocks->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data stok untuk direstok.'], 404);
        }

        $updatedProducts = [];

        foreach ($stocks as $stock) {
            $product = $stock->product;

            if ($product) {
                $addedQuantity = $stock->limit;

                // Tambahkan jumlah produk sesuai limit
                $product->quantity_product += $addedQuantity;
                $product->save();

                // Kurangi limit di stock (set jadi 0 karena sudah restock penuh)
                $stock->limit = 0;
                $stock->save();

                $updatedProducts[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'added_quantity' => $addedQuantity,
                    'new_quantity' => $product->quantity_product,
                ];
            }
        }

        return response()->json([
            'message' => 'Semua stok berhasil direstok ke produk terkait.',
            'data' => $updatedProducts,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal merestok produk.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
