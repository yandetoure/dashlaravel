<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Info extends Model
{
    use HasFactory;

    protected $table = 'infos';

    protected $fillable = [
        'title',
        'image',
        'content',
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
