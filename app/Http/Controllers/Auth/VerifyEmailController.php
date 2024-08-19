<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
/*
 * Created a new Request instead of using the one from Illuminate
 * because otherwise it checks for the user to be already logged in
*/
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;


use App\Models\User;

use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        /*
         * The URL is signed and checked via 'signed' middleware and the custom Request
         * we can think that if we are at this stage the request is secure
         * possibly revisit later
         * This shouldn't be needed because it should fail in the request
         * if (!$user) {
         *  abort(404, 'User not found');
         * }
        */
        $user = User::find($request->route('id'));
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('app', absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        Auth::login($user);
        $user->update(['password' => null]);

        return redirect()->intended(route('app', absolute: false).'?verified=1');
    }
}
