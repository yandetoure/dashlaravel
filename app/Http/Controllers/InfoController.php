<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
{
    public function index()
    {
        $infos = Info::with('category')->latest()->get();
        return view('infos.index', compact('infos'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $infos = Info::with('category')->latest()->take(3)->get();
        return view('infos.create', compact('categories', 'infos'));
    }

    public function store(Request $request)
    {
        try {
            // Validation détaillée
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'content' => 'required|string|min:10',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'required|exists:categories,id',
                'external_link' => 'nullable|url|max:500'
            ], [
                'title.required' => 'Le titre est obligatoire',
                'title.min' => 'Le titre doit contenir au moins 3 caractères',
                'content.required' => 'Le contenu est obligatoire',
                'content.min' => 'Le contenu doit contenir au moins 10 caractères',
                'image.image' => 'Le fichier doit être une image',
                'image.mimes' => "L'image doit être de type : jpeg, png, jpg, gif",
                'image.max' => "L'image ne doit pas dépasser 2Mo",
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => "La catégorie sélectionnée n'est pas valide",
                'external_link.url' => 'Le lien externe doit être une URL valide'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('infos', 'public');
                $validated['image'] = $path;
            }

            $info = Info::create($validated);

            Log::info('Info créée avec succès:', $info->toArray());
            return redirect()->route('infos.index')
                           ->with('success', 'Info créée avec succès');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'info:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->withErrors(['error' => "Une erreur est survenue lors de la création de l'info"])
                           ->withInput();
        }
    }

    public function show(Info $info)
    {
        return view('infos.show', compact('info'));
    }

    public function edit(Info $info)
    {
        $categories = \App\Models\Category::active()->get();
        return view('infos.edit', compact('info', 'categories'));
    }

    public function update(Request $request, Info $info)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'external_link' => 'nullable|url|max:500'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('infos', 'public');
            $validated['image'] = $path;
        } else {
            // Si pas de nouvelle image, garder l'ancienne
            $validated['image'] = $info->image;
        }

        $info->update($validated);
        return redirect()->route('infos.index')->with('success', 'Info mise à jour avec succès.');
    }

    public function destroy(Info $info)
    {
        $info->delete();
        return redirect()->route('infos.index')->with('success', 'Info supprimée avec succès.');
    }
}
