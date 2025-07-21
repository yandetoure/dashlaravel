<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Actu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ActuController extends Controller
{
    // Affiche la liste de toutes les actualités
    public function index()
    {
        $actus = Actu::with('category')->latest()->get();
        return view('actus.index', compact('actus'));
    }

    // Affiche le formulaire pour créer une nouvelle actualité
    public function create()
    {
        $categories = Category::active()->get();
        $actus = Actu::with('category')->latest()->take(3)->get();
        return view('actus.create', compact('categories', 'actus'));
    }

    // Enregistre la nouvelle actualité en base
    public function store(Request $request)
    {
        try {
            // Validation détaillée
        $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:10',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'required|exists:categories,id',
                'external_link' => 'nullable|url|max:255'
            ], [
                'title.required' => 'Le titre est obligatoire',
                'title.min' => 'Le titre doit contenir au moins 3 caractères',
                'content.required' => 'Le contenu est obligatoire',
                'content.min' => 'Le contenu doit contenir au moins 10 caractères',
                'image.image' => 'Le fichier doit être une image',
                'image.mimes' => 'L\'image doit être de type : jpeg, png, jpg, gif',
                'image.max' => 'L\'image ne doit pas dépasser 2Mo',
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => 'La catégorie sélectionnée n\'est pas valide',
                'external_link.url' => 'Le lien externe doit être une URL valide'
            ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('actus', 'public');  
            $validated['image'] = $path;
            }

            $actu = Actu::create($validated);

            Log::info('Actualité créée avec succès:', $actu->toArray());
            return redirect()->route('actus.index')
                           ->with('success', 'Actualité créée avec succès');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'actualité:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'actualité'])
                           ->withInput();
        }
    }
    
    public function show($id)
    {
        $actu = Actu::with('category')->findOrFail($id);
        $actus = Actu::with('category')
                     ->where('id', '!=', $id)
                     ->latest()
                     ->take(3)
                     ->get();
        return view('actus.show', compact('actu', 'actus'));
    }
    
    // Affiche le formulaire pour éditer une actualité existante
    public function edit($id)
    {
        $actu = Actu::with('category')->findOrFail($id);
        $categories = Category::active()->get();
        $actus = Actu::with('category')
                     ->where('id', '!=', $id)
                     ->latest()
                     ->take(3)
                     ->get();
        return view('actus.edit', compact('actu', 'categories', 'actus'));
    }

    // Met à jour une actualité existante
    public function update(Request $request, $id)
    {
        $actu = Actu::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'external_link' => 'nullable|url|max:255'
        ], [
            'title.required' => 'Le titre est obligatoire',
            'title.min' => 'Le titre doit contenir au moins 3 caractères',
            'content.required' => 'Le contenu est obligatoire',
            'content.min' => 'Le contenu doit contenir au moins 10 caractères',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'L\'image doit être de type : jpeg, png, jpg, gif',
            'image.max' => 'L\'image ne doit pas dépasser 2Mo',
            'category_id.required' => 'La catégorie est obligatoire',
            'category_id.exists' => 'La catégorie sélectionnée n\'est pas valide',
            'external_link.url' => 'Le lien externe doit être une URL valide'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer ancienne image si nécessaire
            if ($actu->image && Storage::disk('public')->exists($actu->image)) {
                Storage::disk('public')->delete($actu->image);
            }
            $path = $request->file('image')->store('actus', 'public');
            $validated['image'] = $path;
        }

        $actu->update($validated);
        return redirect()->route('actus.show', $actu->id)
                        ->with('success', 'Actualité mise à jour avec succès');
    }

    // Supprime une actualité
    public function destroy($id)
    {
        $actu = Actu::findOrFail($id);
        
        // Supprimer l'image si elle existe
        if ($actu->image && Storage::disk('public')->exists($actu->image)) {
            Storage::disk('public')->delete($actu->image);
        }
        
        $actu->delete();
        
        return redirect()->route('actus.index')
                        ->with('success', 'Actualité supprimée avec succès');
    }
}
