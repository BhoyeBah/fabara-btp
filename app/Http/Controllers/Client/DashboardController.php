<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $devis = $user->devisRequests()
            ->withCount(['messages as unread_count' => fn ($q) => $q->where('from_admin', true)->whereNull('read_at')])
            ->latest()->get();

        return view('client.dashboard', [
            'user' => $user,
            'devisList' => $devis,
            'unread' => $devis->sum('unread_count'),
        ]);
    }
}
