<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', ['values' => Setting::allValues()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'slogan' => ['required', 'string', 'max:120'],
            'promise' => ['required', 'string', 'max:60'],
            'hero_text' => ['required', 'string', 'max:400'],
            'phone_display' => ['required', 'string', 'max:30'],
            'whatsapp' => ['required', 'regex:/^\d{8,15}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'address' => ['required', 'string', 'max:190'],
            'hours' => ['nullable', 'string', 'max:120'],
            'announcement' => ['nullable', 'string', 'max:160'],
        ], [
            'whatsapp.regex' => 'Le numéro WhatsApp doit contenir uniquement des chiffres, indicatif compris (ex. 224626432276).',
        ]);

        Setting::put(array_map(fn ($v) => $v ?? '', $data));

        return back()->with('success', 'Paramètres enregistrés. Le site est à jour.');
    }

    public function password(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->forceFill(['password' => $data['password']])->save();

        return back()->with('success', 'Mot de passe administrateur modifié.');
    }
}
