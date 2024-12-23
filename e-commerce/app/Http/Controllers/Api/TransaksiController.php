<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Symfony\Component\HttpFoundation\Response;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('pelanggan')->get(); // Mengambil semua data transaksi beserta data pelanggan
        return response()->json([
            "message" => "Data transaksi berhasil diambil",
            "data" => $transaksis,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "pelanggan_id" => ["required", "exists:pelanggan,id"],
                "total_harga" => ["required", "numeric", "min:0"],
                "tanggal_transaksi" => ["required", "date"],
                "status" => ["required", "string"],
            ], [
                "pelanggan_id.required" => "Pelanggan harus dipilih",
                "pelanggan_id.exists" => "Pelanggan tidak valid",
                "total_harga.required" => "Total harga harus diisi",
                "total_harga.numeric" => "Total harga harus berupa angka",
                "total_harga.min" => "Total harga tidak boleh negatif",
                "tanggal_transaksi.required" => "Tanggal transaksi harus diisi",
                "tanggal_transaksi.date" => "Format tanggal tidak valid",
                "status.required" => "Status transaksi harus diisi",
            ]);

            Transaksi::create([
                "pelanggan_id" => $request->pelanggan_id,
                "total_harga" => $request->total_harga,
                "tanggal_transaksi" => $request->tanggal_transaksi,
                "status" => $request->status,
            ]);

            return response()->json([
                "message" => "Data transaksi berhasil ditambahkan",
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data transaksi gagal ditambahkan",
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
                "pelanggan_id" => ["required", "exists:pelanggan,id"],
                "total_harga" => ["required", "numeric", "min:0"],
                "tanggal_transaksi" => ["required", "date"],
                "status" => ["required", "string"],
            ], [
                "pelanggan_id.required" => "Pelanggan harus dipilih",
                "pelanggan_id.exists" => "Pelanggan tidak valid",
                "total_harga.required" => "Total harga harus diisi",
                "total_harga.numeric" => "Total harga harus berupa angka",
                "total_harga.min" => "Total harga tidak boleh negatif",
                "tanggal_transaksi.required" => "Tanggal transaksi harus diisi",
                "tanggal_transaksi.date" => "Format tanggal tidak valid",
                "status.required" => "Status transaksi harus diisi",
            ]);

            $transaksi = Transaksi::findOrFail($id);
            $transaksi->update([
                "pelanggan_id" => $request->pelanggan_id,
                "total_harga" => $request->total_harga,
                "tanggal_transaksi" => $request->tanggal_transaksi,
                "status" => $request->status,
            ]);

            return response()->json([
                "message" => "Data transaksi berhasil diubah",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data transaksi gagal diubah",
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
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();

            return response()->json([
                "message" => "Data transaksi berhasil dihapus",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data transaksi gagal dihapus",
                "error" => $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
