<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevisRequest;
use App\Notifications\NewReplyForClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('statut');
        $q = trim((string) $request->query('q'));

        $devis = DevisRequest::query()
            ->withCount(['messages as unread_count' => fn ($m) => $m->where('from_admin', false)->whereNull('read_at')])
            ->when($status && array_key_exists($status, config('fabara.statuses')), fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('reference', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('commune', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.devis-index', [
            'devisList' => $devis,
            'status' => $status,
            'q' => $q,
            'counts' => DevisRequest::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(DevisRequest $devis)
    {
        $devis->messages()->where('from_admin', false)->whereNull('read_at')->update(['read_at' => now()]);

        return view('admin.devis-show', ['devis' => $devis->load(['photos', 'messages', 'user'])]);
    }

    public function update(Request $request, DevisRequest $devis)
    {
        // « 4 500 000 » ou « 4.500.000 » → 4500000
        $amount = preg_replace('/\D/', '', (string) $request->input('quoted_amount'));
        $request->merge(['quoted_amount' => $amount === '' ? null : $amount]);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(config('fabara.statuses')))],
            'quoted_amount' => ['nullable', 'integer', 'min:0'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $devis->status = $data['status'];
        $devis->quoted_amount = $data['quoted_amount'] ?? null;
        $devis->admin_notes = $data['admin_notes'] ?? null;
        $devis->closed_at = $devis->isClosed() ? ($devis->closed_at ?? now()) : null;
        $devis->save();

        return back()->with('success', 'Demande mise à jour.');
    }

    public function message(Request $request, DevisRequest $devis)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $devis->messages()->create([
            'user_id' => $request->user()->id,
            'from_admin' => true,
            'body' => $data['body'],
        ]);

        if ($devis->user) {
            try {
                $devis->user->notify(new NewReplyForClient($devis));
            } catch (Throwable $e) {
                report($e);
            }
        }

        return redirect()->to(route('admin.devis.show', $devis).'#messages')->with('success', 'Réponse envoyée.');
    }

    public function destroy(DevisRequest $devis)
    {
        $ref = $devis->reference;
        $devis->delete();

        return redirect()->route('admin.devis.index')->with('success', "Demande {$ref} supprimée.");
    }
}
