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

        // gestion de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/actus'); // stocke dans storage/app/public/actus
            $validated['image'] = basename($path);
        }

        Actu::create($validated);
        return redirect()->route('actus.index')->with('success', 'Actualité créée avec succès.');
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
