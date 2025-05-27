<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on user type
                $user = Auth::user();
                $userType = $user->user_type_id;
                
                switch ($userType) {
                    case 1: // Super Admin
                        return redirect('/admin/dashboard');
                    case 2: // School Admin
                        return redirect('/school/dashboard');
                    case 3: // Student
                        return redirect('/student/dashboard');
                    default:
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
