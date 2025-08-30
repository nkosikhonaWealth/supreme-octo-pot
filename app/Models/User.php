<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    protected static function booted()
    {
        static::created(function (User $user) {
            $user->assignRole('youth');
        });
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

    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));

        \Log::info("Password reset link: " . $resetUrl); 

        $this->notify(new ResetPassword($token));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasRole('super_admin')|| $this->hasRole('executive') || $this->hasRole('m_&_e')|| $this->hasRole('regional_programs_support_officer'),
            'user' => $this->hasRole('user') || $this->hasRole('youth'),
            default => false,
        };

    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function participant()
    {
        return $this->hasOne(Participant::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function consultation()
    {
        return $this->hasMany(Consultation::class);
    }

    public function youth_message_log()
    {
        return $this->hasMany(YouthMessageLog::class);
    }

    public function participant_details()
    {
        $participant = Participant::where('user_id',$this->id)->first();
        if($participant)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function internal_attendance()
    {
        return $this->hasMany(InternalAttendance::class);
    }
}
