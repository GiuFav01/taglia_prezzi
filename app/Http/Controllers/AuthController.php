<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class AuthController
 *
 * Handles user authentication, including login and logout functionality.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Display the login page.
     *
     * This method renders the login page with optional flash messages (success or error)
     * if they exist in the session.
     *
     * @return Response The rendered login page.
     */
    public function index(): Response
    {
        return Inertia::render('Login', [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Authenticate the user and generate a personal access token.
     *
     * This method validates the user's credentials and attempts to log them in. If successful,
     * it generates a personal access token using Laravel Passport, stores it in an HttpOnly cookie,
     * and redirects to the APIs index page. If authentication fails, it redirects back with an error message.
     *
     * @param Request $request The incoming request containing user credentials.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the appropriate route based on authentication result.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generate a personal access token
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->accessToken;

            // Store the token in an HttpOnly cookie
            Cookie::queue('auth_token', $token, 120, null, null, true, true, false, 'Strict');

            return redirect()->route('apis.index')->with('success', 'Login eseguito con successo!');
        }

        return back()->with('error', 'Credenziali non valide.');
    }

    /**
     * Logout the user and invalidate their tokens.
     *
     * This method deletes all personal access tokens for the authenticated user, removes the `auth_token`
     * cookie, and redirects the user to the login page with a success message.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the login page after logout.
     */
    public function logout()
    {
        Auth::user()->tokens()->delete(); // Invalidate all tokens
        Cookie::queue(Cookie::forget('auth_token')); // Remove the auth_token cookie

        return redirect()->route('login')->with('success', 'Logout eseguito con successo.');
    }
}
