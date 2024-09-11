<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'online_admission_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirmation_code_expires_at',
        'confirmation_code',
        'is_email_verify'
    ];
   
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'confirmation_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'confirmation_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     /**
     * Generate and set a confirmation code and expiration.
     *
     * @return void
     */
    public function generateConfirmationCode()
    {
        $this->confirmation_code = Str::random(6);
        $this->confirmation_code_expires_at = now()->addMinutes(3); // Set expiration time to 3 minutes
    }

    /**
     * Regenerate confirmation code and expiration.
     *
     * @return void
     */
    public function regenerateConfirmationCode()
    {
        $this->generateConfirmationCode();
        $this->save(); // Save the updated user with the new confirmation code
    }
}
