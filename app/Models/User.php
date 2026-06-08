<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'password',
    'curp',
    'user_type',
    'profile_type',
    'area_id',
    'external_institution',
    'phone',
    'status',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasPublicId, HasRoles, Notifiable, VisibleToUser;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function microcredentials(): HasMany
    {
        return $this->hasMany(Microcredential::class);
    }

    public function userLearningPaths(): HasMany
    {
        return $this->hasMany(UserLearningPath::class);
    }

    public function evaluationResults(): HasMany
    {
        return $this->hasMany(EvaluationResult::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === 'activo'
            && $this->hasAnyRole([
                'Superadministrador',
                'Recursos Humanos',
                'Calidad Academica',
                'Educacion Continua',
                'Instructor',
                'Evaluador',
                'Responsable Area',
            ]);
    }

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

    protected function maskedCurp(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (blank($this->curp)) {
                return null;
            }

            return substr($this->curp, 0, 4).str_repeat('*', max(strlen($this->curp) - 8, 0)).substr($this->curp, -4);
        });
    }

    protected function maskedEmail(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (blank($this->email) || ! str_contains($this->email, '@')) {
                return $this->email;
            }

            [$name, $domain] = explode('@', $this->email, 2);

            return substr($name, 0, 1).str_repeat('*', max(strlen($name) - 1, 2)).'@'.$domain;
        });
    }
}
