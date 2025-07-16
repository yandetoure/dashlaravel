<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TrafficIncident extends Model
{
    protected $fillable = [
        'incident_id',
        'type',
        'severity',
        'description',
        'latitude',
        'longitude',
        'road_name',
        'direction',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Scope pour les incidents actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les incidents par gravité
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Vérifier si l'incident est récent (moins de 2 heures)
     */
    public function isRecent()
    {
        return $this->created_at->diffInHours(now()) < 2;
    }

    /**
     * Obtenir la couleur CSS selon la gravité
     */
    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'critical' => 'red',
            'major' => 'orange',
            'minor' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Obtenir l'icône selon le type d'incident
     */
    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'accident' => '🚗',
            'construction' => '🚧',
            'congestion' => '🚦',
            'slow_traffic' => '🐌',
            'normal' => '⚡',
            'weather' => '🌧️',
            'road_closed' => '🚫',
            default => '⚠️'
        };
    }
}
