<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Une seule page de connexion pour tout le monde.
 * Après connexion : l'admin part vers l'administration, le client vers son espace.
 * Rien sur ces pages n'indique qu'un espace admin existe.
 */
class AuthController extends Controller
{
    /**
     * Venu d'un bouton « Demander un devis » (?suite=devis ou ?suite=renovation) :
     * après connexion ou inscription, le client arrive directement sur le formulaire.
     */
    private function rememberNextStep(Request $request): void
    {
        $next = match ($request->query('suite')) {
            'devis' => route('client.devis.create'),
            'renovation' => route('client.devis.create', ['type' => 'renovation']),
            default => null,
        };
        if ($next) {
            $request->session()->put('url.intended', $next);
        }
    }

    public function showLogin(Request $request)
    {
        $this->rememberNextStep($request);

        return view('auth.login', ['suite' => $request->query('suite')]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = Str::lower($credentials['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($key)]),
            ]);
        }

        if (! Auth::attempt(['email' => Str::lower($credentials['email']), 'password' => $credentials['password']], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('client.dashboard'));
    }

    public function showRegister(Request $request)
    {
        $this->rememberNextStep($request);

        return view('auth.register', ['suite' => $request->query('suite')]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9 +().-]{8,30}$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
            'marketing' => ['nullable', 'boolean'],
        ]);

        $user = new User([
            'name' => $data['name'],
            'email' => Str::lower($data['email']),
            'phone' => $data['phone'],
            'password' => $data['password'],
            'terms_accepted_at' => now(),
            'privacy_version' => config('fabara.privacy_version'),
            'marketing_opt_in' => $request->boolean('marketing'),
        ]);
        $user->last_login_at = now();
        $user->role = User::ROLE_CLIENT; // toujours client, jamais admin
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))
            ->with('success', 'Bienvenue '.$user->name.' ! Votre compte est créé.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
