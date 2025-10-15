<?php declare(strict_types=1); 

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'name',
        'email',
        'password',
        'profile_photo',
        'day_off', 
        'day_off_assigned_at',
        'points',
        'loyalty_points',
        'current_lat',
        'current_lng',
        'location_updated_at',
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
            'location_updated_at' => 'datetime',
        ];
    }

    // Dans le modèle User
    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_drivers', 'chauffeur_id', 'car_id');
    }

    // Dans le modèle Reservation
 
    public function car()
{
    return $this->hasOne(Car::class);
}

    
// Dans le modèle User
public function car_drivers()
{
    return $this->hasMany(CarDriver::class, 'chauffeur_id');
}

// Dans le modèle CarDriver (relation vers Reservation)
public function reservations()
{
    return $this->hasMany(Reservation::class, 'cardriver_id');
}

}
