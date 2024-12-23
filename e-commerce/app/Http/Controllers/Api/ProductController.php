<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get(); // Mengambil semua produk beserta kategori
        $data = [
            "message" => "Data produk berhasil diambil",
            "data" => $products,
        ];
        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => ["required", "string", "max:255"],
                "category_id" => ["required", "exists:categories,id"], // Validasi kategori
                "description" => ["nullable", "string"], // Validasi deskripsi
                "price" => ["required", "numeric", "min:0"], // Validasi harga
            ], [
                "name.required" => "Nama produk harus diisi",
                "category_id.required" => "Kategori produk harus dipilih",
                "category_id.exists" => "Kategori yang dipilih tidak valid",
                "description.string" => "Deskripsi harus berupa teks",
                "price.required" => "Harga produk harus diisi",
                "price.numeric" => "Harga harus berupa angka",
                "price.min" => "Harga tidak boleh negatif",
            ]);

            Product::create([
                "name" => $request->name,
                "category_id" => $request->category_id, // Menyimpan ID kategori
                "description" => $request->description, // Menyimpan deskripsi
                "price" => $request->price, // Menyimpan harga
            ]);

            return response()->json([
                "message" => "Data produk berhasil ditambahkan",
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data produk gagal ditambahkan",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                "name" => ["required", "string", "max:255"],
                "category_id" => ["required", "exists:categories,id"],
                "description" => ["nullable", "string"], // Validasi deskripsi
                "price" => ["required", "numeric", "min:0"], // Validasi harga
            ], [
                "name.required" => "Nama produk harus diisi",
                "category_id.required" => "Kategori produk harus dipilih",
                "category_id.exists" => "Kategori yang dipilih tidak valid",
                "description.string" => "Deskripsi harus berupa teks",
                "price.required" => "Harga produk harus diisi",
                "price.numeric" => "Harga harus berupa angka",
                "price.min" => "Harga tidak boleh negatif",
            ]);

            $product = Product::findOrFail($id); // Mencari produk berdasarkan ID
            $product->update([
                "name" => $request->name,
                "category_id" => $request->category_id,
                "description" => $request->description,
                "price" => $request->price,
            ]);

            return response()->json([
                "message" => "Data produk berhasil diubah",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data produk gagal diubah",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id); // Mencari produk berdasarkan ID
            $product->delete();

            return response()->json([
                "message" => "Data produk berhasil dihapus",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data produk gagal dihapus",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
