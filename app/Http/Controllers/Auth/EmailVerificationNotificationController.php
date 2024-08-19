<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use \App\Models\User;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if($request->user()){
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('app', absolute: false));
            }
            $request->user()->sendEmailVerificationNotification();
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->sendEmailVerificationNotification();
            } else {
                return back()->with('error', 'User not found');
            }
        }

        return back()->with('status', 'verification-link-sent');
    }
}
