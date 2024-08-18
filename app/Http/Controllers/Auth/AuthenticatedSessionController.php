<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\LoginLink;
use Illuminate\Auth\Access\AuthorizationException;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
    * Display the login view.
    */
    public function create(): View
    {
        return view('auth.login');
    }
    
    /**
    * Handle an incoming authentication request.
    */
    public function store(LoginRequest $request): RedirectResponse
    {   
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('status', __('If the user exists, an email has been sent with login instructions.'));
        }
        
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')->with('email', $user->email);
        }
        
        $password = Str::random(64);

        $user->update(['password' => $password]);

        $user->notify(new LoginLink($password));
        
        return back()->with('status', __('If the user exists, an email has been sent with login instructions.'));
    }
    
    public function verifyLogin(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }
        
        if ($user->getPassword() !== $request->password) {
            throw new AuthorizationException;
        }
        
        Auth::login($user);
        $user->update(['password' => null]);

        $request->session()->regenerate();
        
        return redirect()->intended(route('dashboard', absolute: false));
    }
    
    /**
    * Destroy an authenticated session.
    */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
