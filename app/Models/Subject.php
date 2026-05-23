<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kelas',
        'icon',
        'color',
        'description',
        'sort_order',
    ];

    /**
     * Subject memiliki banyak materi.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('chapter_order');
    }

    /**
     * Scope: filter berdasarkan kelas.
     */
    public function scopeForKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Hitung total estimasi waktu belajar.
     */
    public function getTotalMinutesAttribute(): int
    {
        return $this->materials->sum('estimated_minutes');
    }

    /**
     * Label kelas yang readable.
     */
    public static function kelasOptions(): array
    {
        return [
            '7-smp'  => 'Kelas 7 SMP',
            '8-smp'  => 'Kelas 8 SMP',
            '9-smp'  => 'Kelas 9 SMP',
            '10-sma' => 'Kelas 10 SMA',
            '11-sma' => 'Kelas 11 SMA',
            '12-sma' => 'Kelas 12 SMA',
        ];
    }

    /**
     * Get label for a kelas code.
     */
    public static function kelasLabel(string $kelas): string
    {
        return self::kelasOptions()[$kelas] ?? $kelas;
    }
}
