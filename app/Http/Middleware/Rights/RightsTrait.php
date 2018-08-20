<?php

namespace App\Http\Middleware\Rights;

use App\User;
use Illuminate\Support\Facades\Auth;

trait RightsTrait
{
    /**
     * @param $right
     * @return bool
     */
    public function userHasRight($right): bool
    {
        /**@var User $user */
        $user = Auth::user();
        return $user && $user->hasRight($right);
    }
}
