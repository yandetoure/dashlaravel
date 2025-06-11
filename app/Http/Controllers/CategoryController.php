<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Affiche la liste de toutes les catégories
     */
    public function index(): View
    {
        $categories = Category::withCount('actus')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle catégorie
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Enregistre la nouvelle catégorie en base
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            Log::info('Début de création de catégorie', [
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:500',
                'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire',
                'name.unique' => 'Cette catégorie existe déjà',
                'color.required' => 'La couleur est obligatoire',
                'color.regex' => 'La couleur doit être au format hexadécimal (#RRGGBB)',
            ]);

            $validated['is_active'] = $request->has('is_active');

            Log::info('Données validées', ['validated' => $validated]);

            $category = Category::create($validated);

            Log::info('Catégorie créée avec succès', [
                'category_id' => $category->id,
                'category_name' => $category->name
            ]);

            return redirect()->route('categories.index')
                            ->with('success', 'Catégorie créée avec succès');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation lors de la création de catégorie', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de catégorie', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                            ->withErrors(['error' => 'Une erreur est survenue lors de la création de la catégorie'])
                            ->withInput();
        }
    }

    /**
     * Affiche une catégorie spécifique
     */
    public function show(Category $category): View
    {
        $category->load('actus');
        return view('categories.show', compact('category'));
    }

    /**
     * Affiche le formulaire pour éditer une catégorie
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie existante
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'name.required' => 'Le nom de la catégorie est obligatoire',
            'name.unique' => 'Cette catégorie existe déjà',
            'color.required' => 'La couleur est obligatoire',
            'color.regex' => 'La couleur doit être au format hexadécimal (#RRGGBB)',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie mise à jour avec succès');
    }

    /**
     * Supprime une catégorie
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Vérifier si la catégorie a des actualités associées
        if ($category->actus()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'Impossible de supprimer cette catégorie car elle contient des actualités');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * API pour récupérer toutes les catégories actives (pour AJAX)
     */
    public function getActive()
    {
        $categories = Category::active()->get(['id', 'name', 'color']);
        return response()->json($categories);
    }
}
