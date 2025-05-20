<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actu extends Model
{
    // Spécifie la table si différente du nom pluriel du modèle (par défaut : 'actus')
    protected $table = 'actus';

    // Permet l'assignation massive pour ces champs
    protected $fillable = [
        'title',
        'content',
        'image',
    ];
}
