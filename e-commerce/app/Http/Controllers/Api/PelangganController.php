<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Symfony\Component\HttpFoundation\Response;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = Pelanggan::all(); // Mengambil semua data pelanggan
        return response()->json([
            "message" => "Data pelanggan berhasil diambil",
            "data" => $pelanggans,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "nama" => ["required", "string", "max:255"],
                "email" => ["required", "email", "unique:pelanggan,email"],
                "telepon" => ["required", "string", "max:15"],
                "alamat" => ["nullable", "string"],
            ], [
                "nama.required" => "Nama pelanggan harus diisi",
                "email.required" => "Email pelanggan harus diisi",
                "email.email" => "Format email tidak valid",
                "email.unique" => "Email sudah digunakan",
                "telepon.required" => "Nomor telepon harus diisi",
                "telepon.max" => "Nomor telepon terlalu panjang",
            ]);

            Pelanggan::create([
                "nama" => $request->nama,
                "email" => $request->email,
                "telepon" => $request->telepon,
                "alamat" => $request->alamat,
            ]);

            return response()->json([
                "message" => "Data pelanggan berhasil ditambahkan",
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data pelanggan gagal ditambahkan",
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
                "nama" => ["required", "string", "max:255"],
                "email" => ["required", "email", "unique:pelanggan,email," . $id],
                "telepon" => ["required", "string", "max:15"],
                "alamat" => ["nullable", "string"],
            ], [
                "nama.required" => "Nama pelanggan harus diisi",
                "email.required" => "Email pelanggan harus diisi",
                "email.email" => "Format email tidak valid",
                "email.unique" => "Email sudah digunakan",
                "telepon.required" => "Nomor telepon harus diisi",
                "telepon.max" => "Nomor telepon terlalu panjang",
            ]);

            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->update([
                "nama" => $request->nama,
                "email" => $request->email,
                "telepon" => $request->telepon,
                "alamat" => $request->alamat,
            ]);

            return response()->json([
                "message" => "Data pelanggan berhasil diubah",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data pelanggan gagal diubah",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

            return response()->json([
                "message" => "Data pelanggan berhasil dihapus",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data pelanggan gagal dihapus",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
