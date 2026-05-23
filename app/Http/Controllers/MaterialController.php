<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Show subjects list for user's kelas.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $kelas = $user->kelas;

        if (!$kelas) {
            return redirect()->route('materials.pilih-kelas');
        }

        $subjects = Subject::forKelas($kelas)
            ->withCount('materials')
            ->orderBy('sort_order')
            ->get();

        return view('materials.index', compact('subjects', 'kelas'));
    }

    /**
     * Show materials for a specific subject.
     */
    public function subject(Subject $subject)
    {
        $materials = $subject->materials()->orderBy('chapter_order')->get();
        return view('materials.subject', compact('subject', 'materials'));
    }

    /**
     * Show single material detail.
     */
    public function show(Material $material)
    {
        $material->load('subject');

        // Get prev/next for navigation
        $prev = Material::where('subject_id', $material->subject_id)
            ->where('chapter_order', '<', $material->chapter_order)
            ->orderBy('chapter_order', 'desc')->first();
        $next = Material::where('subject_id', $material->subject_id)
            ->where('chapter_order', '>', $material->chapter_order)
            ->orderBy('chapter_order')->first();

        return view('materials.show', compact('material', 'prev', 'next'));
    }

    /**
     * Show kelas selection page.
     */
    public function pilihKelas()
    {
        $kelasOptions = Subject::kelasOptions();
        return view('materials.pilih-kelas', compact('kelasOptions'));
    }

    /**
     * Save user's kelas choice.
     */
    public function simpanKelas(Request $request)
    {
        $request->validate(['kelas' => 'required|string|in:' . implode(',', array_keys(Subject::kelasOptions()))]);
        $request->user()->update(['kelas' => $request->kelas]);
        return redirect()->route('materials.index')->with('success', 'Kelas berhasil dipilih!');
    }
}
