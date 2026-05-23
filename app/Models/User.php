<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'github_id',
        'facebook_id',
        'linkedin_id',
        'microsoft_id',
        'discord_id',
        'apple_id',
        'avatar',
        'kelas',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * User has many tasks.
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    /**
     * Get subjects for this user's kelas.
     */
    public function subjects()
    {
        return \App\Models\Subject::where('kelas', $this->kelas)->orderBy('sort_order')->get();
    }

    /**
     * Get materials for this user's kelas.
     */
    public function materials()
    {
        return \App\Models\Material::where('kelas', $this->kelas)->get();
    }

    /**
     * Get readable kelas label.
     */
    public function getKelasLabelAttribute(): string
    {
        return \App\Models\Subject::kelasLabel($this->kelas ?? '');
    }
}
