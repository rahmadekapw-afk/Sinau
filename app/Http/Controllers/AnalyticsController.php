<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // 1. Task Completion Stats
        $totalTasks = Task::forUser($userId)->count();
        $completedTasks = Task::forUser($userId)->where('status', 'done')->count();
        $pendingTasks = $totalTasks - $completedTasks;
        
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // 2. Tasks by Subject
        $tasksBySubject = Task::forUser($userId)
            ->whereNotNull('subject')
            ->selectRaw('subject, count(*) as count')
            ->groupBy('subject')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // 3. Tasks by Type
        $tasksByType = Task::forUser($userId)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get()
            ->keyBy('type');
            
        $typeStats = [
            'deadline' => $tasksByType->get('deadline')->count ?? 0,
            'exam' => $tasksByType->get('exam')->count ?? 0,
            'study' => $tasksByType->get('study')->count ?? 0,
        ];

        // 4. Activity last 7 days (Completion vs Created - simplified to completed)
        $last7Days = collect();
        $activityData = collect();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days->push($date->format('d M'));
            
            $completed = Task::forUser($userId)
                ->where('status', 'done')
                ->whereDate('completed_at', $date)
                ->count();
                
            $activityData->push($completed);
        }

        // 5. AI Engagement (Simple stat based on is_ai)
        $aiGeneratedTasks = Task::forUser($userId)->where('is_ai', true)->count();
        
        // 6. AI Insight Generation (Rule-based simple insight for now)
        $insight = "Kamu belum memiliki banyak data tugas. Tambahkan lebih banyak tugas agar AI bisa menganalisanya!";
        if ($totalTasks > 0) {
            if ($completionRate > 80) {
                $insight = "Luar Biasa! 🔥 Kamu memiliki tingkat penyelesaian tugas yang sangat tinggi ({$completionRate}%). Pertahankan konsistensi ini!";
            } elseif ($completionRate > 50) {
                $insight = "Kerja bagus! 👍 Kamu telah menyelesaikan lebih dari separuh tugasmu. Coba prioritaskan tugas dengan deadline terdekat.";
            } else {
                $insight = "Banyak tugas yang menumpuk. 💡 Gunakan fitur AI Task Breakdown untuk memecah tugas besarmu menjadi lebih ringan.";
            }
        }

        return view('analytics.index', compact(
            'totalTasks', 'completedTasks', 'pendingTasks', 'completionRate',
            'tasksBySubject', 'typeStats', 'last7Days', 'activityData',
            'aiGeneratedTasks', 'insight'
        ));
    }
}
