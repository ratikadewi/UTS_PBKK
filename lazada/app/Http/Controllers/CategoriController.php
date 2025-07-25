<?php

namespace App\Http\Controllers;

use App\Models\Categori;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriController extends Controller
{
     public function index(): JsonResponse
    {

        $dataBarang = Categori::all();
        return response()->json($dataBarang, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $barang = Categori::findOrFail($id);
            return response()->json($barang, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Categori tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $barang = Categori::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);


        return response()->json([
            'message' => 'Categori berhasil ditambahkan.',
            'data' => $barang
        ], 201);
    }

      // Mengupdate data user
      public function update(Request $request, $id): JsonResponse
      {
          try {
              $barang = Categori::findOrFail($id);
  
              $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
            ]);
  
              // Hanya update field yang dikirim
              $data = $request->only(['name', 'description']);

              $barang->update($data);
              
  
              return response()->json([
                  'message' => $barang->wasChanged()
                      ? 'Data Categori berhasil diupdate.'
                      : 'Tidak ada perubahan pada data Categori.',
                  'data' => $barang
              ], 200);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Categori tidak ditemukan'], 404);
          }
      }
  
      public function destroy($id): JsonResponse
      {
          try {
              $barang = Categori::findOrFail($id);
              $barang->delete();
  
              return response()->json(['message' => 'Data Categori berhasil dihapus.']);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Data Categori tidak ditemukan.'], 404);
          }
      }
}
