<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Tampilkan halaman Import Data.
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Proses file upload (Simulasi proses AI).
     */
    public function process(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // Maksimal 10MB
            'actions' => 'required|array|min:1',
        ]);

        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $actions = $request->input('actions');

        // Di sini nantinya adalah logika nyata untuk parsing PDF dan memanggil AI API
        // Untuk sekarang, kita mensimulasikan proses tersebut.
        
        $messages = [];
        if (in_array('schedule', $actions)) {
            $messages[] = "Jadwal dan silabus berhasil diekstrak ke Kalender.";
        }
        if (in_array('tasks', $actions)) {
            $messages[] = "Instruksi tugas dipecah menjadi 5 tasks baru.";
        }
        if (in_array('summary', $actions)) {
            $messages[] = "Ringkasan dan 10 latihan soal telah dibuat.";
        }
        if (in_array('chat', $actions)) {
            $messages[] = "Dokumen sekarang terhubung dengan Chat AI.";
        }

        $successMessage = "✨ File '{$fileName}' berhasil diproses AI! " . implode(' ', $messages);

        // Jika user memilih memecah tasks, arahkan ke halaman Tasks
        if (in_array('tasks', $actions)) {
            return redirect()->route('tasks.index')->with('success', $successMessage);
        }
        
        // Jika jadwal, arahkan ke Kalender
        if (in_array('schedule', $actions)) {
            return redirect()->route('calendar.index')->with('success', $successMessage);
        }

        return back()->with('success', $successMessage);
    }
}
