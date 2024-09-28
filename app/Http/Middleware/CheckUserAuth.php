<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $currentPath = $request->path();
        $publicRoutes = [
            '/', 
            'login', 
            'contact-us', 
            'about-us', 
            'downloads', 
            'contact-message', 
            'user-login', 
            'initial-user'
        ];
        if (!Auth::check()) {
            if (in_array($currentPath, $publicRoutes)) {
                return $next($request);
            }
            return redirect('/login');
        }
        return $next($request);
    }
    

}
