<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan; // Jika Anda menggunakan model Pelanggan
use Illuminate\Http\Request;
class PelangganController extends Controller
{
    // Fungsi untuk menampilkan semua pelanggan
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('dashboard.pelanggan.index', compact('pelanggan'));
    }

    // Fungsi untuk menampilkan form tambah pelanggan
    public function create()
    {
        return view('pelanggan.create');
    }

    // Fungsi untuk menyimpan data pelanggan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'alamat'=>'required'
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit($id) {
        $pelanggan = Pelanggan::findOrFail($id); // Ganti dengan model yang sesuai
        return response()->json(['data' => $pelanggan]);
    }
    

    // Fungsi untuk memperbarui data pelanggan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy($id) {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus.']);
    }
    
} 

