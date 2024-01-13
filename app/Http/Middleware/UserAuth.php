<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        // $user = Session::get('user');
        // if (!$user && !in_array($path, ['login', 'register'])) {
        //     return redirect(route('login'));
        // }
        if (session()->has('user')) {
            if (!in_array($path, ['login', 'register'])) {
                return $next($request);
            } else {
                return redirect(route('home'));
            }
        } else {
            return redirect(route('login'));
        }
    }
}
