<?php

namespace App\Http\Controllers;

use App\Support\DevisCreator;
use App\Support\DevisRules;
use Illuminate\Http\Request;

/**
 * Formulaire de devis de l'accueil et de la page Rénovation.
 * Réservé aux clients connectés : le consentement a été donné à l'inscription.
 */
class PublicDevisController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(DevisRules::rules(true, false));

        $devis = DevisCreator::create($request, $data);

        return redirect()->route('client.devis.show', $devis->reference)
            ->with('success', 'Votre demande '.$devis->reference.' est enregistrée. Vous pouvez suivre son avancement ici.');
    }
}
