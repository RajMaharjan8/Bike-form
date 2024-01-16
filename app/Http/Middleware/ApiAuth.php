<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // if (Auth::check() && Auth::user()->remember_token !== null) {
        //     return $next($request);
        // }
        // return response()->json(['error' => 'Unauthorized.'], 401);



        // Retrieving the email from the request
        $email = $request->input('email');
        // Checking if a user with the specified email has a valid remember_token
        $user = User::where('email', $email)->where('remember_token', '!=', null)->first();
        if ($user) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized. Log in to gain access.'], 401);
    }
}
