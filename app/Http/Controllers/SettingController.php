<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman Pengaturan.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Simpan pembaruan pengaturan.
     */
    public function update(Request $request)
    {
        // Dalam implementasi nyata, data ini akan disimpan ke database (tabel settings atau json column di users)
        // Untuk sekarang kita lakukan simulasi sukses.
        
        return back()->with('success', '✨ Pengaturan berhasil disimpan!');
    }
}
