<?php

namespace App\Http\Controllers;

use App\Models\DeletionRequest;
use App\Models\Realisation;
use App\Models\Service;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $realisations = Realisation::where('is_published', true)
            ->orderBy('sort')->latest()->limit(24)->get();

        return view('home', [
            'realisations' => $realisations,
            'categories' => $realisations->pluck('category')->unique()->values(),
            'pairs' => Realisation::beforeAfterPairs(3),
            'services' => Service::published(),
        ]);
    }

    /** Page dédiée à la rénovation de vieilles maisons (à partager sur WhatsApp, Facebook…). */
    public function renovation()
    {
        return view('pages.renovation', [
            'pairs' => Realisation::beforeAfterPairs(6),
        ]);
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function deletion()
    {
        return view('pages.deletion');
    }

    /** Demande de suppression pour une personne qui ne peut plus se connecter. */
    public function storeDeletion(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'contact' => ['required', 'string', 'max:190'],
            'message' => ['nullable', 'string', 'max:1000'],
            'confirm' => ['accepted'],
        ], [], ['confirm' => 'la confirmation']);

        DeletionRequest::create($data + ['status' => 'en_attente']);

        return back()->with('success', 'Votre demande de suppression est enregistrée. Nous la traitons sous 30 jours maximum et vous confirmons par e-mail ou téléphone.');
    }
}
