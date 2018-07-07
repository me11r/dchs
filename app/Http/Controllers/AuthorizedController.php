<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 18.04.2017
 * Time: 10:17
 */

namespace App\Http\Controllers;


use App\Exceptions\AccessDeniedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthorizedController extends Controller
{
    public function before() {}

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
        if (!isset($user)||(!$user->hasRight($right)))
        {
            throw new AccessDeniedException();
        }

        return true;
    }
}