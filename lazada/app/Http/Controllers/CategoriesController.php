<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CategoriesController extends Controller
{
      public function index(): JsonResponse
    {

        $dataCategories = Categories::all();
        return response()->json($dataCategories, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $categories = Categories::findOrFail($id);
            return response()->json($categories, 200);
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

        $categories = Categories::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);


        return response()->json([
            'message' => 'Data categori berhasil ditambahkan.',
            'data' => $categories
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $categories = Categories::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string',
                'description' => 'sometimes|string',
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['name', 'description']);

            $categories->update($data);


            return response()->json([
                'message' => $categories->wasChanged()
                    ? 'Data categori berhasil diupdate.'
                    : 'Tidak ada categori pada data stock.',
                'data' => $categories
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'categori tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = Categories::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'categori berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'categori tidak ditemukan.'], 404);
        }
    }
            public function count()
    {
        $count = \App\Models\Categories::count();

        return response()->json([
            'total' => $count
        ]);
    }
}
