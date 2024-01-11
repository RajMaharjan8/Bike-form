<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $path = $request->path();
        // $user = Session::get('user');

        // if ($user && in_array($path, ['login', 'register'])) {
        //     return redirect('/');
        // }
        // if (!$user && !in_array($path, ['login', 'register'])) {
        //     return redirect('/login');
        // }

        $path = $request->path();
        $user = Session::get('user');
        if (!$user && !in_array($path, ['login', 'register'])) {
            return redirect('/login');
        }




        return $next($request);
    }

    // public function logout(Request $request): RedirectResponse
    // {
    //     Auth::logout();
    
    //     $request->session()->invalidate();
    
    //     $request->session()->regenerateToken();
    
    //     return redirect('/logout');

    // }
}
