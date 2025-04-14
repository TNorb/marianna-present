<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // Middleware - Adminhoz - csak adminoknak és superadminoknak engedélyezett oldalak
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')) {
            return $next($request);
        }

        return redirect()->route('/')->with('error', 'You are not autorized.');
    }
}
