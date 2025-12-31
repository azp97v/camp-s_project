<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Mass assignable attributes.
     *
     * الحقول التي يمكن تعبئتها بالجُمل (mass assignable).
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'identifier',
    ];

    /**
     * Hidden attributes for array / JSON serialization.
     *
     * الحقول المخفية عند تحويل النموذج إلى مصفوفة أو JSON.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Attributes appended to the model's array form (accessors).
     *
     * سمات تضاف تلقائياً عند تحويل النموذج إلى مصفوفة.
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Attribute casting definitions.
     *
     * يحول القيم ل Types مناسبة عند الوصول لها.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (empty($user->identifier)) {
                $user->identifier = (string) Str::uuid();
            }
        });
    }
    public function goals(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Goal::class);
}
}
