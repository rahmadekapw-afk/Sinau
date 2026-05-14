<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConverterController extends Controller
{
    /**
     * Tampilkan halaman Word to PDF Converter.
     */
    public function index()
    {
        return view('converter.index');
    }

    /**
     * Proses konversi file (Simulasi).
     */
    public function convert(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:doc,docx|max:10240', // Word files only
        ]);

        $file = $request->file('document');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Simulasi proses konversi...
        // Di dunia nyata, ini akan menggunakan library seperti DomPDF, PhpWord, atau API pihak ketiga.
        
        $successMessage = "✨ File Word berhasil dikonversi! Dokumen '{$fileName}.pdf' sedang diunduh.";

        return back()->with('success', $successMessage)->with('download', $fileName . '.pdf');
    }
}
