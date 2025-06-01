<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'position',
        'passport_series',
        'passport_number',
        'passport_issue_date',
        'passport_issued_by',
        'personal_data_agreement',
        'status',
        'role',
        'membership_number',
        'rejection_reason',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'passport_issue_date' => 'date',
        'approved_at' => 'datetime',
        'personal_data_agreement' => 'boolean',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'accepted_events');
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // При создании нового пользователя, если это первый пользователь, сделать его админом
        static::creating(function ($user) {
            if (User::count() === 0) {
                $user->role = 'admin';
            }
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Переопределяем метод для проверки пароля
    public function validateCredentials($user, $credentials)
    {
        // Если пароль пустой, не проверяем его
        if (empty($user->password)) {
            return false;
        }
        
        return parent::validateCredentials($user, $credentials);
    }

    public function getFullNameAttribute()
    {
        return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name);
    }
}

