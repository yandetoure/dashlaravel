<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actu extends Model
{
    // Spécifie la table si différente du nom pluriel du modèle (par défaut : 'actus')
    protected $table = 'actus';

    // Permet l'assignation massive pour ces champs
    protected $fillable = [
        'title',
        'content',
        'image',
        'category_id',
        'external_link'
    ];

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accesseur pour récupérer le nom de la catégorie
     * (pour compatibilité avec le code existant)
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? 'Non classé';
    }
}
