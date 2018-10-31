<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use Closure;

class CheckRole
{
    /**
     * @param $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return mixed
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        /** @var /App/User $user */
        $user = auth()->user();
        $role = $user->role;
        if (!$role && !in_array($user->role, $roles)) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}
