<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Tampilkan halaman Billing & Subscription.
     */
    public function index()
    {
        // Data simulasi (Di aplikasi asli, ini akan diambil dari tabel users, subscriptions, dan transactions)
        $currentPlan = 'Basic (Gratis)';
        $aiQuotaLeft = 3;
        $aiQuotaTotal = 10;
        
        $transactions = [
            [
                'date' => now()->subDays(5)->format('d M Y'),
                'item' => 'Top Up 100 Koin AI',
                'amount' => 'Rp 10.000',
                'method' => 'GoPay',
                'status' => 'Sukses'
            ],
            [
                'date' => now()->subDays(20)->format('d M Y'),
                'item' => 'Top Up 50 Koin AI',
                'amount' => 'Rp 5.000',
                'method' => 'QRIS',
                'status' => 'Sukses'
            ]
        ];

        return view('billing.index', compact('currentPlan', 'aiQuotaLeft', 'aiQuotaTotal', 'transactions'));
    }

    /**
     * Proses simulasi pembayaran.
     */
    public function process(Request $request)
    {
        $package = $request->input('package');
        
        // Simulasi logika pembayaran
        // Di aplikasi asli, ini akan redirect ke payment gateway (contoh: Midtrans Snap)
        
        $message = "Simulasi Pembayaran Berhasil! Anda telah berlangganan paket: " . strtoupper($package) . ". Fitur premium sekarang aktif.";
        
        if ($package == 'koin_100') {
            $message = "Top Up 100 Koin AI Berhasil! Saldo koin Anda telah ditambahkan.";
        }

        return redirect()->route('billing.index')->with('success', $message);
    }
}
