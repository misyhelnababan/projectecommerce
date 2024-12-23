<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->avatar===null && !file_exists(Storage::path('public/'.auth()->user()->avatar))){
            return redirect()->route('dashboard.profile.index');
        }
        return $next($request);
    }
}
