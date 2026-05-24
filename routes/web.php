<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::middleware('auth')->group(function () {
    // Dashboard — menampilkan tasks hari ini
    Route::get('/dashboard', function () {
        $todayTasks = \App\Models\Task::forUser(auth()->id())->today()->orderBy('due_time')->get();
        $upcomingTasks = \App\Models\Task::forUser(auth()->id())->upcoming()->limit(5)->get();
        $subjects = auth()->user()->kelas
            ? \App\Models\Subject::forKelas(auth()->user()->kelas)->withCount('materials')->orderBy('sort_order')->limit(4)->get()
            : collect();
        return view('dashboard', compact('todayTasks', 'upcomingTasks', 'subjects'));
    })->name('dashboard');

    // Tasks — Kanban board (CRUD)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Calendar — Monthly grid terintegrasi dengan tasks
    Route::get('/calendar', [TaskController::class, 'calendar'])->name('calendar.index');

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');

    // Import Data
    Route::get('/import', [\App\Http\Controllers\ImportController::class, 'index'])->name('import.index');
    Route::post('/import/process', [\App\Http\Controllers\ImportController::class, 'process'])->name('import.process');

    // Word to PDF Converter
    Route::get('/converter', [\App\Http\Controllers\ConverterController::class, 'index'])->name('converter.index');
    Route::post('/converter/process', [\App\Http\Controllers\ConverterController::class, 'convert'])->name('converter.process');

    // Billing
    Route::get('/billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/process', [\App\Http\Controllers\BillingController::class, 'process'])->name('billing.process');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    // Support
    Route::get('/support', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::post('/support/process', [\App\Http\Controllers\SupportController::class, 'process'])->name('support.process');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat AI routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/send', function () {
        return redirect()->route('chat.index');
    });
    Route::get('/chat/new', [ChatController::class, 'newSession'])->name('chat.new');
    Route::get('/chat/history', [ChatController::class, 'history'])->name('chat.history');
    Route::get('/chat/load/{sessionId}', [ChatController::class, 'loadSession'])->name('chat.load');

    // Floating Chatbot routes
    Route::post('/chatbot/send', [ChatController::class, 'chatbotSend'])->name('chatbot.send');
    Route::get('/chatbot/send', function () {
        return redirect()->route('dashboard');
    });

    // AI Quiz Generator routes
    Route::get('/quiz', [\App\Http\Controllers\QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/generate', [\App\Http\Controllers\QuizController::class, 'generate'])->name('quiz.generate');

    // Materials — Materi belajar berdasarkan kelas
    Route::get('/materials', [\App\Http\Controllers\MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/pilih-kelas', [\App\Http\Controllers\MaterialController::class, 'pilihKelas'])->name('materials.pilih-kelas');
    Route::post('/materials/pilih-kelas', [\App\Http\Controllers\MaterialController::class, 'simpanKelas'])->name('materials.simpan-kelas');
    Route::get('/materials/subject/{subject}', [\App\Http\Controllers\MaterialController::class, 'subject'])->name('materials.subject');
    Route::get('/materials/{material}', [\App\Http\Controllers\MaterialController::class, 'show'])->name('materials.show');
});

require __DIR__.'/auth.php';
