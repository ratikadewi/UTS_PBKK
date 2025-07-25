<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Models\Customers;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomersController extends Controller
{
    public function index(): JsonResponse
    {
        $dataCustomers = Customers::all();
        return response()->json($dataCustomers, 200);
    }
    // Menampilkan user berdasarkan ID
    public function show($id): JsonResponse
    {
        try {
            $customers = Customers::findOrFail($id);
            return response()->json($customers, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customers not found'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|max:15|min:8|unique:customers,phone',
            'address' => 'required|string',
        ]);

        $customers = Customers::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Akun Customers berhasil ditambahkan.',
            'data' => $customers
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $customers = Customers::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:8',
                'phone' => 'sometimes|max:15|min:8|unique:customers,phone',
                'address' => 'sometimes|string'
            ]);

            // Hanya update field yang dikirim
            $data = $request->only(['name', 'email', 'password','phone','address']);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            logger('Data yg dikirim', $data);
            $customers->update($data);
            

            return response()->json([
                'message' => $customers->wasChanged()
                    ? 'Akun Customers berhasil diupdate.'
                    : 'Tidak ada perubahan pada Data Customers.',
                'data' => $customers
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customers not found'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $customers = Customers::findOrFail($id);
            $customers->delete();

            return response()->json(['message' => 'Data Customers berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Customers tidak ditemukan.'], 404);
        }
    }

        public function count()
    {
        $count = \App\Models\Customers::count();

        return response()->json([
            'total' => $count
        ]);
    }
}
