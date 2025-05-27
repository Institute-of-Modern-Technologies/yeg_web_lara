<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserType;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        // Get the user type ID for the required user type
        $requiredTypeId = UserType::where('slug', $userType)->first()?->id;
        
        if (!$requiredTypeId) {
            abort(404, 'User type not found');
        }

        // Check if the user has the required user type
        if ($request->user()->user_type_id !== $requiredTypeId) {
            abort(403, 'Unauthorized action');
        }

        return $next($request);
    }
}
