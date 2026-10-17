<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeletionRequest;
use App\Models\DevisRequest;
use App\Models\Message;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = DevisRequest::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.dashboard', [
            'counts' => $counts,
            'total' => $counts->sum(),
            'thisMonth' => DevisRequest::where('created_at', '>=', now()->startOfMonth())->count(),
            'clients' => User::where('role', User::ROLE_CLIENT)->count(),
            'unreadMessages' => Message::where('from_admin', false)->whereNull('read_at')->count(),
            'pendingDeletions' => DeletionRequest::where('status', 'en_attente')->count(),
            'wonAmount' => DevisRequest::whereIn('status', ['accepte', 'chantier', 'termine'])->sum('quoted_amount'),
            'latest' => DevisRequest::latest()->limit(8)->get(),
        ]);
    }
}
