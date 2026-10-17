<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DevisRequest;
use App\Support\DevisCreator;
use App\Support\DevisRules;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function create(Request $request)
    {
        $preset = $request->query('type') === 'renovation'
            ? ['property_type' => 'Vieille maison à rénover', 'works' => ['Rénovation / modernisation']]
            : [];

        return view('client.devis-create', ['user' => $request->user(), 'preset' => $preset]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(DevisRules::rules(true, false));

        $devis = DevisCreator::create($request, $data);

        return redirect()->route('client.devis.show', $devis->reference)
            ->with('success', 'Votre demande '.$devis->reference.' est enregistrée. Nous vous recontactons rapidement.');
    }

    public function show(Request $request, DevisRequest $devis)
    {
        $this->authorizeOwner($request, $devis);

        // Les réponses de FABARA sont marquées comme lues à l'ouverture.
        $devis->messages()->where('from_admin', true)->whereNull('read_at')->update(['read_at' => now()]);

        return view('client.devis-show', [
            'devis' => $devis->load(['photos', 'messages']),
        ]);
    }

    public function message(Request $request, DevisRequest $devis)
    {
        $this->authorizeOwner($request, $devis);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $devis->messages()->create([
            'user_id' => $request->user()->id,
            'from_admin' => false,
            'body' => $data['body'],
        ]);

        return redirect()->to(route('client.devis.show', $devis->reference).'#messages')
            ->with('success', 'Message envoyé.');
    }

    private function authorizeOwner(Request $request, DevisRequest $devis): void
    {
        abort_unless($devis->user_id === $request->user()->id, 404);
    }
}
