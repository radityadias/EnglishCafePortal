<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Position;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'password', 'password_set_at','position'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, CanResetPassword;

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
            'position' =>  Position::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('admin') || $this->hasRole('user');
    }

    public function employeeProfile() : HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function internshipProfile() : HasOne
    {
        return $this->hasOne(InternshipProfile::class);
    }

    public function leaveRequest() : HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function attendanceRequest() : HasMany
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    public function attendances() : HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceRecap() : BelongsTo
    {
        return $this->belongsTo(AttendanceRecap::class);
    }

    public function hasSetPassword() : bool
    {
        return !is_null($this->password_set_at);
    }
}
