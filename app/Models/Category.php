<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les actualités
     */
    public function actus(): HasMany
    {
        return $this->hasMany(Actu::class);
    }

    /**
     * Scope pour récupérer seulement les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Compter le nombre d'actualités dans cette catégorie
     */
    public function getActusCountAttribute(): int
    {
        return $this->actus()->count();
    }
}
