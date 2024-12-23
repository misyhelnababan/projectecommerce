<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pelanggan; // Pastikan untuk menggunakan model Pelanggan
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi dari database
        $transaksi = Transaksi::with('pelanggan')->get(); // Mengambil relasi pelanggan
        $pelanggans = Pelanggan::all(); // Mengambil semua pelanggan

        return view('dashboard.transaksi.home', compact('transaksi', 'pelanggans'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'total_harga' => 'required|numeric',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|string',
        ]);
    
        try {
            // Simpan transaksi baru
            Transaksi::create($request->all());
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Menangkap dan mencetak kesalahan
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        $transaksi = Transaksi::with('pelanggan')->findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'total_harga' => 'required|numeric',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|string',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return response()->json(['success' => true]);
    }
}