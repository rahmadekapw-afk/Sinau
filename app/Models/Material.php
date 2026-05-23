<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'kelas',
        'title',
        'description',
        'content',
        'chapter_order',
        'difficulty',
        'estimated_minutes',
    ];

    /**
     * Material belongs to a subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope: filter berdasarkan kelas.
     */
    public function scopeForKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Label difficulty dalam bahasa Indonesia.
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty) {
            'mudah'  => '🟢 Mudah',
            'sedang' => '🟡 Sedang',
            'sulit'  => '🔴 Sulit',
            default  => 'Sedang',
        };
    }

    /**
     * Badge class berdasarkan difficulty.
     */
    public function getDifficultyClassAttribute(): string
    {
        return match ($this->difficulty) {
            'mudah'  => 'bg-green-100 text-green-700',
            'sedang' => 'bg-yellow-100 text-yellow-700',
            'sulit'  => 'bg-red-100 text-red-700',
            default  => 'bg-gray-100 text-gray-700',
        };
    }
}
