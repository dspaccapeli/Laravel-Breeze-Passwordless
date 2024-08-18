<?php

namespace App\Models;

/* 
 * Uncommented the MustVerifyEmail interface and added the implements to the User class
 * to enable email verification from the auth/verify-email route
 */
use App\Notifications\VerifyEmailWithPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Get the user's password for the login and registration flow.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * Override the standard email verification notification
     */ 
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailWithPassword);
    }

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
            // 'password' => 'hashed', // This is commented, otherwise $user->update(['password'=>'X']) will hash 'X'
        ];
    }
}
