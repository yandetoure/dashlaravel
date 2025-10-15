<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Récupérer toutes les courses avec leurs réservations
        $courses = Course::with(['reservation.client', 'reservation.carDriver.chauffeur'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); 
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        // Récupérer les réservations confirmées sans course
        $reservations = Reservation::where('status', 'confirmée')
            ->whereDoesntHave('course')
            ->with(['client', 'carDriver.chauffeur'])
            ->get();
        
        return view('courses.create', compact('reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        Course::create([
            'reservation_id' => $request->reservation_id,
            'statut' => Course::STATUT_EN_ATTENTE,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course créée avec succès.');
    }

    public function show(Course $course)
    {
        $course->load(['reservation.client', 'reservation.carDriver.chauffeur', 'reservation.car']);
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $course->load(['reservation.client', 'reservation.carDriver.chauffeur']);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,terminee,annulee',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course mise à jour avec succès.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course supprimée avec succès.');
    }

    // Méthodes pour gérer les statuts des courses
    public function demarrer(Course $course)
    {
        $course->demarrerCourse();
        return redirect()->route('courses.suivi', $course)->with('success', 'Course démarrée.');
    }

    // Méthode pour afficher la page de suivi en temps réel
    public function suivi(Course $course)
    {
        // Vérifier que la course est bien en cours
        if ($course->statut !== Course::STATUT_EN_COURS) {
            return redirect()->route('courses.index')->with('error', 'Cette course n\'est pas en cours.');
        }

        $course->load(['reservation.client', 'reservation.carDriver.chauffeur']);
        return view('courses.suivi', compact('course'));
    }

    public function terminer(Course $course)
    {
        $course->terminerCourse();
        
        // Rediriger vers la page de notation pour que le client puisse noter la course
        return redirect()->route('courses.notation', $course)->with('success', 'Course terminée. Veuillez noter votre expérience.');
    }

    public function annuler(Course $course)
    {
        $course->annulerCourse();
        return redirect()->route('courses.index')->with('success', 'Course annulée.');
    }

    // Méthode pour noter une course
    public function noter(Request $request, Course $course)
    {
        // Vérifier que la course est terminée
        if ($course->statut !== Course::STATUT_TERMINEE) {
            return redirect()->route('courses.show', $course)->with('error', 'Cette course n\'est pas encore terminée.');
        }

        // Vérifier que la course n'a pas déjà été notée
        if ($course->note) {
            return redirect()->route('courses.show', $course)->with('info', 'Cette course a déjà été évaluée.');
        }

        $request->validate([
            'note' => 'required|in:satisfait,neutre,decu',
            'commentaire_positif' => 'nullable|string|max:1000',
            'commentaire_negatif' => 'nullable|string|max:1000',
        ]);

        $course->noter(
            $request->note,
            $request->commentaire_positif,
            $request->commentaire_negatif
        );

        return redirect()->route('courses.show', $course)->with('success', 'Merci pour votre évaluation ! Votre avis nous aide à améliorer notre service.');
    }

    // Méthode pour afficher le formulaire de notation
    public function notation(Course $course)
    {
        // Vérifier que la course est terminée
        if ($course->statut !== Course::STATUT_TERMINEE) {
            return redirect()->route('courses.show', $course)->with('error', 'Cette course n\'est pas encore terminée.');
        }

        // Vérifier que la course n'a pas déjà été notée
        if ($course->note) {
            return redirect()->route('courses.show', $course)->with('info', 'Cette course a déjà été évaluée.');
        }

        $course->load(['reservation.client', 'reservation.carDriver.chauffeur']);
        return view('courses.notation', compact('course'));
    }

    // Méthode pour filtrer les courses par statut
    public function parStatut($statut)
    {
        $courses = Course::parStatut($statut)
            ->with(['reservation.client', 'reservation.carDriver.chauffeur'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('courses.index', compact('courses', 'statut'));
    }
}