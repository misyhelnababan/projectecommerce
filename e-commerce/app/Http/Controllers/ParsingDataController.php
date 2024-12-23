<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParsingDataController extends Controller
{
    /**
     * Handle the incoming request for parsing data.
     */
    public function parseData($nama_lengkap, $email, $jenis_kelamin)
    {
        // Contoh logika untuk memproses data
        return response()->json([
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'jenis_kelamin' => $jenis_kelamin,
        ]);
    }
}
