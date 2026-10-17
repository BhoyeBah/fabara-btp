<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $clients = User::where('role', User::ROLE_CLIENT)
            ->withCount('devisRequests')
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.clients-index', ['clients' => $clients, 'q' => $q]);
    }

    public function show(User $user)
    {
        abort_if($user->isAdmin(), 404);

        return view('admin.client-show', [
            'client' => $user,
            'devisList' => $user->devisRequests()->latest()->get(),
        ]);
    }

    /** Suppression complète d'un compte client (ex. suite à une demande reçue hors du site). */
    public function destroy(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 404);
        $request->validate(['confirm_email' => ['required', Rule::in([$user->email])]], [
            'confirm_email.in' => 'Recopiez exactement l\'e-mail du client pour confirmer.',
        ]);

        foreach ($user->devisRequests()->with('photos')->get() as $devis) {
            $devis->delete();
        }
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Le compte client et toutes ses données ont été supprimés.');
    }
}
