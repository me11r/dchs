<?php

namespace App\Http\Controllers;


use App\Exceptions\AccessDeniedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthorizedController extends Controller
{
    public function before()
    {
        parent::before();
    }

    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
        $this->_user = \Auth::user();
        if ($this->_user) {
            $this->set('_user', $this->_user);
        }
    }

    /**
     * @param $right
     * @return bool
     * @throws \App\Exceptions\AccessDeniedException
     */
    public function needRight($right)
    {
        $user = \Auth::user();
        if (!isset($user) || (!$user->hasRight($right))) {
            throw new AccessDeniedException();
        }

        return true;
    }

    /**
     * @param array $rights
     * @return bool
     * @throws AccessDeniedException
     */
    public function needAnyRight(array $rights)
    {
        $user = \Auth::user();
        if (!isset($user) || (!$user->hasRight($rights))) {
            throw new AccessDeniedException();
        }

        return true;
    }
}
