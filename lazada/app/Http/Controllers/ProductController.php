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

        $dataBarang = Product::all();
        return response()->json($dataBarang, 200);
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|string'
        ]);

        $barang = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id
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
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|integer',
                'stock' => 'sometimes|integer',
                'category_id' => 'sometimes|string'
            ]);
  
              // Hanya update field yang dikirim
              $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);

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
}