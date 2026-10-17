<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RealisationController extends Controller
{
    public function index()
    {
        return view('admin.realisations', [
            'realisations' => Realisation::orderBy('sort')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(config('fabara.realisation_categories'))],
            'location' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'before_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $path = $request->file('image')->store('realisations', 'public');
        $before = $request->hasFile('before_image') ? $request->file('before_image')->store('realisations', 'public') : null;

        Realisation::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'image_path' => $path,
            'before_image_path' => $before,
            'is_published' => true,
            'is_featured' => $request->boolean('is_featured'),
            'sort' => (int) Realisation::min('sort') - 1, // la plus récente en premier
        ]);

        return back()->with('success', $before ? 'Avant / après ajouté au site.' : 'Réalisation ajoutée au site.');
    }

    public function update(Request $request, Realisation $realisation)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(config('fabara.realisation_categories'))],
            'location' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'remove_before' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'before_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $dropBefore = function () use ($realisation) {
            if ($realisation->before_image_path && ! Realisation::isBundled($realisation->before_image_path)) {
                Storage::disk('public')->delete($realisation->before_image_path);
            }
            $realisation->before_image_path = null;
        };
        if ($request->hasFile('before_image')) {
            $dropBefore();
            $realisation->before_image_path = $request->file('before_image')->store('realisations', 'public');
        } elseif ($request->boolean('remove_before')) {
            $dropBefore();
        }

        if ($request->hasFile('image')) {
            if ($realisation->isUploaded()) {
                Storage::disk('public')->delete($realisation->image_path);
            }
            $realisation->image_path = $request->file('image')->store('realisations', 'public');
        }

        $realisation->fill([
            'title' => $data['title'],
            'category' => $data['category'],
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
        ])->save();

        return back()->with('success', 'Réalisation mise à jour.');
    }

    public function destroy(Realisation $realisation)
    {
        $realisation->delete();

        return back()->with('success', 'Réalisation retirée du site.');
    }
}
