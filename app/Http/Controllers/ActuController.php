<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actu; // Assure-toi que le modèle existe

class ActuController extends Controller
{
    // Affiche la liste de toutes les actualités
    public function index()
    {
        $actus = Actu::all(); // Récupère toutes les actualités
        return view('actus.index', compact('actus'));
    }

    // Affiche le formulaire pour créer une nouvelle actualité
    public function create()
    {
        return view('actus.create');
    }

    // Enregistre la nouvelle actualité en base
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);
    
        // Si une image est uploadée
        if ($request->hasFile('image')) {
            // Stocker dans 'storage/app/public/actus'
            $path = $request->file('image')->store('actus', 'public');  
            // Stocke le chemin relatif dans la BD, par ex: 'actus/filename.jpg'
            $validated['image'] = $path;
        } else {
            $validated['image'] = null; // ou laisser vide si optionnel
        }
    
        // crée l'actu avec le chemin complet dans la BDD
        Actu::create($validated);
    
        return redirect()->route('acutus.index')->with('success', 'Actualité créée avec succès.');
    }
    
    public function show($id)
    {
        $actu = Actu::findOrFail($id);
        return view('actus.show', compact('actu'));
    }
    
    // Affiche le formulaire pour éditer une actualité existante
    public function edit($id)
    {
        $actu = Actu::findOrFail($id);
        return view('actus.edit', compact('actu'));
    }

    // Met à jour une actualité existante
    public function update(Request $request, $id)
    {
        $actu = Actu::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // gestion de la nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer ancienne image si nécessaire
            if ($actu->image && \Storage::exists('public/actus/' . $actu->image)) {
                \Storage::delete('public/actus/' . $actu->image);
            }
            $path = $request->file('image')->store('public/actus');
            $validated['image'] = basename($path);
        }

        $actu->update($validated);
        return redirect()->route('actus.index')->with('success', 'Actualité mise à jour.');
    }

    // Supprime une actualité
    public function destroy($id)
    {
        $actu = Actu::findOrFail($id);
        // Supprimer l’image si existante
        if ($actu->image && \Storage::exists('public/actus/' . $actu->image)) {
            \Storage::delete('public/actus/' . $actu->image);
        }
        $actu->delete();
        return redirect()->route('actus.index')->with('success', 'Actualité supprimée.');
    }
}
