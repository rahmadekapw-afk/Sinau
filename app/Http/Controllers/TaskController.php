<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Tasks Page — Kanban board grouped by status.
     */
    public function index()
    {
        $tasks = Task::forUser(Auth::id())
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")
            ->orderBy('due_date')
            ->get();

        $todoTasks = $tasks->where('status', 'todo');
        $progressTasks = $tasks->where('status', 'progress');
        $doneTasks = $tasks->where('status', 'done');

        return view('tasks.index', compact('todoTasks', 'progressTasks', 'doneTasks'));
    }

    /**
     * Calendar Page — Monthly grid with tasks as events.
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Tasks milik user dalam bulan ini
        $tasks = Task::forUser(Auth::id())
            ->inDateRange($startOfMonth, $endOfMonth)
            ->orderBy('due_date')
            ->orderBy('due_time')
            ->get();

        // Group tasks berdasarkan tanggal (key = 'Y-m-d')
        $tasksGrouped = $tasks->groupBy(fn($task) => $task->due_date->format('Y-m-d'));

        // Data calendar
        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0=Minggu, 1=Senin, ...
        $today = now()->toDateString();

        // Upcoming tasks (untuk sidebar / widget)
        $upcomingTasks = Task::forUser(Auth::id())
            ->upcoming()
            ->limit(5)
            ->get();

        return view('calendar.index', compact(
            'month', 'year', 'startOfMonth', 'endOfMonth',
            'tasksGrouped', 'daysInMonth', 'firstDayOfWeek',
            'today', 'upcomingTasks'
        ));
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:deadline,exam,study',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
            'subject' => 'nullable|string|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'todo';

        Task::create($validated);

        // Redirect berdasarkan dari mana user datang
        if ($request->has('from_calendar')) {
            return redirect()->route('calendar.index', [
                'month' => $request->get('month', now()->month),
                'year' => $request->get('year', now()->year),
            ])->with('success', 'Jadwal berhasil ditambahkan!');
        }

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Update task status (for kanban drag & drop or quick update).
     */
    public function update(Request $request, Task $task)
    {
        // Pastikan task milik user
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:deadline,exam,study',
            'status' => 'sometimes|in:todo,progress,done',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
            'subject' => 'nullable|string|max:100',
            'progress_percent' => 'sometimes|integer|min:0|max:100',
        ]);

        // Jika status berubah ke done, set completed_at
        if (isset($validated['status']) && $validated['status'] === 'done') {
            $validated['completed_at'] = now();
            $validated['progress_percent'] = 100;
        }

        $task->update($validated);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}
