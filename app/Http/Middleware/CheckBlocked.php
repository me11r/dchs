<?php

namespace App\Http\Middleware;

use App\Exceptions\UserBlockedException;
use Closure;

class CheckBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if($user->isAdmin()){
            return $next($request);
        }

        if (!$user || !$user->canLogin()) {
            $message = $user ? "Пользователь ".$user->name .' заблокирован' : 'Пользователь заблокирован';
            throw new UserBlockedException($message);
        }

        return $next($request);
    }
}
