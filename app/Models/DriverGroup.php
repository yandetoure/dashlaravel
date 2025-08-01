<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'driver_1_id',
        'driver_2_id', 
        'driver_3_id',
        'driver_4_id',
        'current_rotation_day',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_rotation_day' => 'integer'
    ];

    // Relations avec les chauffeurs
    public function driver1()
    {
        return $this->belongsTo(User::class, 'driver_1_id');
    }

    public function driver2()
    {
        return $this->belongsTo(User::class, 'driver_2_id');
    }

    public function driver3()
    {
        return $this->belongsTo(User::class, 'driver_3_id');
    }

    public function driver4()
    {
        return $this->belongsTo(User::class, 'driver_4_id');
    }

    // Méthode pour obtenir tous les chauffeurs du groupe
    public function getAllDrivers()
    {
        return collect([
            $this->driver1,
            $this->driver2,
            $this->driver3,
            $this->driver4
        ])->filter();
    }

    // Méthode pour calculer les jours de repos pour une date donnée
    public function getRestDaysForDate($date = null)
    {
        if (!$date) {
            $date = Carbon::now();
        } else {
            $date = Carbon::parse($date);
        }

        // Calculer le jour de rotation (0-3) basé sur le jour de la semaine
        $dayOfWeek = $date->dayOfWeek; // 0 = dimanche, 1 = lundi, etc.
        $rotationDay = ($dayOfWeek + $this->current_rotation_day) % 4;

        $restDays = [];

        // Premier chauffeur : repos les jours 2 et 3
        if ($rotationDay == 2 || $rotationDay == 3) {
            $restDays[] = $this->driver_1_id;
        }

        // Deuxième chauffeur : repos les jours 3 et 0
        if ($rotationDay == 3 || $rotationDay == 0) {
            $restDays[] = $this->driver_2_id;
        }

        // Troisième chauffeur : repos les jours 0 et 1
        if ($rotationDay == 0 || $rotationDay == 1) {
            $restDays[] = $this->driver_3_id;
        }

        // Quatrième chauffeur : repos les jours 1 et 2
        if ($rotationDay == 1 || $rotationDay == 2) {
            $restDays[] = $this->driver_4_id;
        }

        return $restDays;
    }

    // Méthode pour obtenir les chauffeurs disponibles pour une date
    public function getAvailableDriversForDate($date = null)
    {
        $restDays = $this->getRestDaysForDate($date);
        $allDrivers = $this->getAllDrivers()->pluck('id')->toArray();
        
        return array_diff($allDrivers, $restDays);
    }

    // Méthode pour obtenir le planning complet de la semaine
    public function getWeeklySchedule($startDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->startOfWeek();
        } else {
            $startDate = Carbon::parse($startDate)->startOfWeek();
        }

        $schedule = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $restDays = $this->getRestDaysForDate($date);
            
            $schedule[$date->format('Y-m-d')] = [
                'date' => $date,
                'day_name' => $date->format('l'),
                'rest_drivers' => $restDays,
                'available_drivers' => $this->getAvailableDriversForDate($date)
            ];
        }

        return $schedule;
    }

    // Méthode pour avancer la rotation
    public function advanceRotation()
    {
        $this->current_rotation_day = ($this->current_rotation_day + 1) % 4;
        $this->save();
    }

    // Méthode pour reculer la rotation
    public function reverseRotation()
    {
        $this->current_rotation_day = ($this->current_rotation_day - 1 + 4) % 4;
        $this->save();
    }

    // Méthode pour réinitialiser la rotation
    public function resetRotation()
    {
        $this->current_rotation_day = 0;
        $this->save();
    }
} 