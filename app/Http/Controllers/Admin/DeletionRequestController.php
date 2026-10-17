<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeletionRequest;
use App\Models\User;

class DeletionRequestController extends Controller
{
    public function index()
    {
        $requests = DeletionRequest::orderByRaw("status = 'en_attente' desc")->latest()->paginate(25);

        // Aide : retrouver le compte correspondant à l'e-mail ou au téléphone indiqué.
        $matches = [];
        foreach ($requests as $r) {
            $digits = preg_replace('/\D/', '', $r->contact);
            $matches[$r->id] = User::where('role', User::ROLE_CLIENT)
                ->where(fn ($q) => $q->where('email', strtolower(trim($r->contact)))
                    ->when(strlen($digits) >= 8, fn ($q2) => $q2->orWhere('phone', 'like', '%'.substr($digits, -8).'%')))
                ->first();
        }

        return view('admin.deletions', ['requests' => $requests, 'matches' => $matches]);
    }

    public function update(DeletionRequest $deletionRequest)
    {
        $deletionRequest->update(['status' => 'traitee', 'processed_at' => now()]);

        return back()->with('success', 'Demande marquée comme traitée.');
    }
}
