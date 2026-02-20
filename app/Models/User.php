<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 *
 * @phpstan-method static \Illuminate\Database\Eloquent\Builder<User> query()
 * @phpstan-method static \Illuminate\Database\Eloquent\Builder<User> role($roles, $guard = null)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'divisi', 'sekolah', 'mentor_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's absent records.
     *
     * @return HasMany<AbsentUser,User>
     */
    public function absents(): HasMany
    {
        return $this->hasMany(AbsentUser::class);
    }

    /**
     * Get the user's jurnal records.
     *
     * @return HasMany<JurnalUser,User>
     */
    public function jurnals(): HasMany
    {
        return $this->hasMany(JurnalUser::class);
    }

    /**
     * Get the user's mentor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Mentor,User>
     */
    public function mentor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    /**
     * Get the user's schedules.
     *
     * @return HasMany<Schedule,User>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
