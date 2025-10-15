<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'statut',
        'note',
        'commentaire_positif',
        'commentaire_negatif',
        'debut_course',
        'fin_course'
    ];

    protected $casts = [
        'debut_course' => 'datetime',
        'fin_course' => 'datetime',
    ];

    // Constantes pour les statuts
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINEE = 'terminee';
    const STATUT_ANNULEE = 'annulee';

    // Constantes pour les notes
    const NOTE_SATISFAIT = 'satisfait';
    const NOTE_NEUTRE = 'neutre';
    const NOTE_DECU = 'decu';

    // Relation avec Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Méthodes pour gérer les statuts
    public function demarrerCourse()
    {
        $this->update([
            'statut' => self::STATUT_EN_COURS,
            'debut_course' => now()
        ]);
    }

    public function terminerCourse()
    {
        $this->update([
            'statut' => self::STATUT_TERMINEE,
            'fin_course' => now()
        ]);
    }

    public function annulerCourse()
    {
        $this->update([
            'statut' => self::STATUT_ANNULEE
        ]);
    }

    // Méthode pour noter une course
    public function noter($note, $commentairePositif = null, $commentaireNegatif = null)
    {
        $this->update([
            'note' => $note,
            'commentaire_positif' => $commentairePositif,
            'commentaire_negatif' => $commentaireNegatif
        ]);
    }

    // Accessor pour le statut en français
    public function getStatutFrancaisAttribute()
    {
        return match($this->statut) {
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_EN_COURS => 'En cours',
            self::STATUT_TERMINEE => 'Terminée',
            self::STATUT_ANNULEE => 'Annulée',
            default => $this->statut
        };
    }

    // Accessor pour la note en français
    public function getNoteFrancaisAttribute()
    {
        return match($this->note) {
            self::NOTE_SATISFAIT => 'Satisfait',
            self::NOTE_NEUTRE => 'Neutre',
            self::NOTE_DECU => 'Déçu',
            default => $this->note
        };
    }

    // Scope pour filtrer par statut
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Scope pour filtrer par note
    public function scopeParNote($query, $note)
    {
        return $query->where('note', $note);
    }

    // Méthode pour calculer la durée de la course
    public function getDureeCourseAttribute()
    {
        if ($this->debut_course && $this->fin_course) {
            return $this->debut_course->diffForHumans($this->fin_course, true);
        }
        return null;
    }
}