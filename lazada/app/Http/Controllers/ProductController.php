<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
       public function index(): JsonResponse
    {
$products = Product::with(['categories', 'diskon'])->get();
        return response()->json($products, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $barang = Product::findOrFail($id);
            return response()->json($barang, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'categories_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity_product' => 'required|integer',
            'price' => 'required|numeric|min:0',
        ]);

        $barang = Product::create([
            'categories_id' => $request->categories_id,
            'name' => $request->name,
            'description' => $request->description,
            'quantity_product' => $request->quantity_product,
            'price' => $request->price,
        ]);


        return response()->json([
            'message' => 'Product berhasil ditambahkan.',
            'data' => $barang
        ], 201);
    }

      // Mengupdate data user
      public function update(Request $request, $id): JsonResponse
      {
          try {
              $barang = Product::findOrFail($id);
  
              $request->validate([
                'categories_id' => 'sometimes|string|max:255',
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'quantity_product' => 'sometimes|integer',
                'price' => 'sometimes|numeric|min:0',
            ]);
  
              // Hanya update field yang dikirim
              $data = $request->only(['categories_id', 'name', 'description', 'quantity_product', 'price']);

              $barang->update($data);
              
  
              return response()->json([
                  'message' => $barang->wasChanged()
                      ? 'Data Product berhasil diupdate.'
                      : 'Tidak ada perubahan pada data Product.',
                  'data' => $barang
              ], 200);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Product tidak ditemukan'], 404);
          }
      }
  
      public function destroy($id): JsonResponse
      {
          try {
              $barang = Product::findOrFail($id);
              $barang->delete();
  
              return response()->json(['message' => 'Data Product berhasil dihapus.']);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Product tidak ditemukan.'], 404);
          }
      }
    public function count()
    {
        $count = \App\Models\Product::count();

        return response()->json([
            'total' => $count
        ]);
    }
}
