<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CustomerController extends Controller
{
    public function index(): JsonResponse
    {

        $dataUCustomer = Customer::all();
        return response()->json($dataUCustomer, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }
    }

    // Menambahkan user baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer,email',
            'password' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',

        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);


        return response()->json([
            'message' => 'Akun customer berhasil ditambahkan.',
            'data' => $customer
        ], 201);
    }

      // Mengupdate data user
      public function update(Request $request, $id): JsonResponse
      {
          try {
              $customer = Customer::findOrFail($id);
  
              $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:customer,email',
                'password' => 'sometimes|string',
                'phone' => 'sometimes|integer',
                'address' => 'sometimes|string',
            ]);
  
              // Hanya update field yang dikirim
              $data = $request->only(['name', 'email', 'password','phone','address']);

              $customer->update($data);
              
  
              return response()->json([
                  'message' => $customer->wasChanged()
                      ? 'Akun customer berhasil diupdate.'
                      : 'Tidak ada perubahan pada data customer.',
                  'data' => $customer
              ], 200);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Akun tidak ditemukan'], 404);
          }
      }
  
      public function destroy($id): JsonResponse
      {
          try {
              $customer = Customer::findOrFail($id);
              $customer->delete();
  
              return response()->json(['message' => 'Customer berhasil dihapus.']);
          } catch (ModelNotFoundException $e) {
              return response()->json(['message' => 'Customer tidak ditemukan.'], 404);
          }
      }
}
