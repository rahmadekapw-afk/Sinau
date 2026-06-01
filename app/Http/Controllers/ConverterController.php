<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class ConverterController extends Controller
{
    /**
     * Tampilkan halaman Word to PDF Converter.
     * Mengarahkan ke halaman Import utama tempat interface konverter berada.
     */
    public function index()
    {
        return redirect()->route('import.index');
    }

    /**
     * Proses konversi file Word (.doc, .docx) ke PDF secara nyata.
     */
    public function convert(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:doc,docx|max:10240', // Word files only (max 10MB)
        ]);

        $file = $request->file('document');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $tempPath = $file->getRealPath();

        try {
            // 1. Tentukan dompdf sebagai PDF renderer untuk PHPWord
            Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

            // 2. Load file Word yang diunggah
            Log::info("Memuat file Word: {$fileName} dari path: {$tempPath}");
            $phpWord = IOFactory::load($tempPath);

            // 3. Tentukan nama dan lokasi penyimpanan PDF hasil konversi
            $pdfFileName = $fileName . '_' . time() . '.pdf';
            $pdfDirectory = storage_path('app/public/converted');
            
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }
            
            $pdfPath = $pdfDirectory . '/' . $pdfFileName;

            // 4. Lakukan penyimpanan PDF
            Log::info("Mengekspor file Word ke PDF: {$pdfPath}");
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($pdfPath);

            Log::info("File PDF berhasil dibuat: {$pdfPath}");

            // 5. Kembalikan URL unduhan sebagai JSON
            return response()->json([
                'success' => true,
                'download_url' => asset('storage/converted/' . $pdfFileName),
                'file_name' => $fileName . '.pdf'
            ]);

        } catch (\Exception $e) {
            Log::error("Word to PDF Conversion Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengonversi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
