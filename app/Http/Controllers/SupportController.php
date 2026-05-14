<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Tampilkan halaman Pusat Bantuan (Support).
     */
    public function index()
    {
        // Simulasi status server AI
        $serverStatus = 'normal'; // normal, busy, maintenance

        $faqs = [
            [
                'question' => 'Bagaimana cara kerja Import Data AI?',
                'answer' => 'Sistem AI kami akan membaca teks di dalam file PDF atau Word yang Anda unggah, kemudian menggunakan NLP (Natural Language Processing) untuk mencari informasi relevan, seperti tanggal untuk jadwal, atau instruksi untuk memecahnya menjadi daftar tugas.'
            ],
            [
                'question' => 'Kapan kuota koin atau limit AI saya di-reset?',
                'answer' => 'Untuk pengguna Basic (Gratis), kuota 10x pertanyaan AI akan di-reset setiap hari pada pukul 00:00 waktu sistem. Jika Anda berlangganan Pro, Anda bebas menggunakan tanpa batas harian.'
            ],
            [
                'question' => 'Apakah file dokumen saya aman dan tidak disebarkan?',
                'answer' => 'Tentu saja! Keamanan privasi Anda adalah yang utama. File PDF atau Word Anda hanya akan diproses saat itu juga oleh AI dan langsung dihapus dari memori server sementara (tidak akan dilatihkan ke model AI lain).'
            ],
            [
                'question' => 'Mengapa alat konversi Word ke PDF saya gagal?',
                'answer' => 'Pastikan file Anda murni berekstensi .docx atau .doc dan ukurannya tidak melebihi 10 MB. Jika file terenkripsi password, sistem kami tidak akan dapat mengonversinya.'
            ],
            [
                'question' => 'Bagaimana cara membatalkan paket langganan Pro?',
                'answer' => 'Anda dapat membatalkan perpanjangan otomatis kapan saja melalui menu Billing -> Riwayat Transaksi -> Kelola Langganan. Anda tetap akan menikmati fitur Pro hingga sisa masa aktif bulan tersebut berakhir.'
            ],
        ];

        return view('support.index', compact('serverStatus', 'faqs'));
    }

    /**
     * Proses simulasi pengiriman lapor bug (Ticketing).
     */
    public function process(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'message' => 'required|string|min:10',
        ]);

        // Di sini logika asli akan menyimpan pesan ke database atau mengirim email ke Admin.
        
        return back()->with('success', '📧 Pesan Anda (Tiket Support) telah berhasil dikirim ke tim kami. Kami akan merespon via email dalam 24 jam.');
    }
}
