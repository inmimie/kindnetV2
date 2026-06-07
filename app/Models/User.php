<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'phone_number',
        'ic_type',
        'ic_number',
        'date_of_birth',
        'place_of_birth',
        'marital_status',
        'race',
        'religion',
        'citizen',
        'address_line1',
        'address_line2',
        'address_line3',
        'city',
        'postcode',
        'district',
        'state_nation',
        'phone_home',
        'gender',
        'profile_picture',
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
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    protected function maritalStatus(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => match ($value) {
                'Bujang' => 'Single',
                'Berkahwin' => 'Married',
                'Duda/Janda' => 'Divorced',
                'Balu' => 'Widowed',
                'Lain-lain' => 'Other',
                default => $value,
            }
        );
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
