<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public const ROLE_SUPER_ADMIN = 1;

    public const ROLE_ADMIN = 2;

    public const ROLE_EDITOR = 3;

    /**
     * @return list<int>
     */
    public static function adminPanelRoleIds(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_EDITOR,
        ];
    }

    /**
     * Roles that can be assigned when creating or editing users in the admin panel.
     *
     * @return array<int, string>
     */
    public static function assignableRoleOptions(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_EDITOR => 'Editor',
        ];
    }

    public function roleLabel(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super admin';
        }

        return self::assignableRoleOptions()[(int) $this->role] ?? 'User';
    }

    public function isSuperAdmin(): bool
    {
        return (int) ($this->role ?? 0) === self::ROLE_SUPER_ADMIN;
    }

    public function canViewHandoverFeedback(): bool
    {
        return strtolower(trim((string) $this->email)) === 'admin@iremetech.com';
    }

    public function hasAdminPanelAccess(): bool
    {
        return in_array((int) ($this->role ?? 0), self::adminPanelRoleIds(), true);
    }

    public function scopeVisibleToAdmins($query)
    {
        return $query->whereNotIn('role', [
            (string) self::ROLE_SUPER_ADMIN,
            self::ROLE_SUPER_ADMIN,
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
