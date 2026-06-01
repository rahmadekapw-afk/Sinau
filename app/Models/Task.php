<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'status',
        'priority',
        'due_date',
        'due_time',
        'subject',
        'is_ai',
        'ai_content',
        'progress_percent',
        'completed_at',
        'submission',
        'submitted_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_ai' => 'boolean',
    ];

    /**
     * Task belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Color mapping berdasarkan type task.
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'deadline' => 'red',
            'exam' => 'yellow',
            'study' => 'indigo',
            default => 'gray',
        };
    }

    /**
     * Full CSS classes for calendar event badge.
     * Returns complete class strings so Tailwind can detect them during purge/JIT.
     */
    public function getEventClassesAttribute(): string
    {
        return match ($this->type) {
            'deadline' => 'bg-red-50 border-red-100 text-red-700',
            'exam' => 'bg-yellow-50 border-yellow-100 text-yellow-700',
            'study' => 'bg-indigo-50 border-indigo-100 text-indigo-700',
            default => 'bg-gray-50 border-gray-100 text-gray-700',
        };
    }

    /**
     * Full CSS class for the color bar/dot indicator.
     */
    public function getBarClassAttribute(): string
    {
        return match ($this->type) {
            'deadline' => 'bg-red-500',
            'exam' => 'bg-yellow-500',
            'study' => 'bg-indigo-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Emoji berdasarkan type task.
     */
    public function getEmojiAttribute(): string
    {
        return match ($this->type) {
            'deadline' => '🔴',
            'exam' => '⚠️',
            'study' => '🤖',
            default => '📌',
        };
    }

    /**
     * Label type dalam bahasa Indonesia.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'deadline' => 'Deadline Tugas',
            'exam' => 'Ujian / Kuis',
            'study' => 'Jadwal Belajar AI',
            default => 'Lainnya',
        };
    }

    /**
     * Scope: tasks milik user tertentu.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: tasks dalam rentang tanggal (untuk calendar).
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('due_date', [$startDate, $endDate]);
    }

    /**
     * Scope: tasks hari ini.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('due_date', now()->toDateString());
    }

    /**
     * Scope: tasks yang belum selesai dan upcoming.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', '!=', 'done')
                     ->where('due_date', '>=', now()->toDateString())
                     ->orderBy('due_date');
    }
}
