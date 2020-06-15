<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use Closure;

class CheckRight
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $rights
     * @return mixed
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next, ...$rights)
    {
        $user = auth()->user();
        
        if($user->isAdmin()){
            return $next($request);
        }
        
        if (!isset($user) || !$user->role || (!$user->role->hasRight($rights))) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}
