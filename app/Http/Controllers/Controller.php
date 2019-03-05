<?php

namespace App\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Page\Metadata;
use App\Right;
use App\Services\CommonHelper;
use App\Services\FileHelper;
use Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;


abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $timezone = '';
    protected $layout;
    protected $data = [];
    protected $_user;
    protected $_locales;
    /** @var Metadata */
    private $metadata;

    public function before()
    {
        $params = [
            'check_roadtrips' => false,
            'check_service_plans' => false,
            'popup_notifications' => false,
        ];
        $user = \Auth::user();
        if ($user !== null) {
            $dept = $user->department;
            if ($dept !== null) {
                $params['check_roadtrips'] = true;
            }
            $service = $user->service_type;
            $canRecieve = $user->hasRight(Right::CAN_RECEIVE_SERVICE_PLAN);
            if ($service !== null && $canRecieve) {
                $params['check_service_plans'] = true;
            }

            $user->last_connect_at = Carbon::now();
            $user->save();

            if($user->hasRight('CAN_RECEIVE_NOTIFICATION_FORMATION_RECORD', false)){
                $params['popup_notifications'] = true;
            }
        }

        $this->set('_global_ajax_timers', json_encode($params));
    }

    public function after()
    {

    }

    public function __construct()
    {
        $this->metadata = new Metadata();
        $this->metadata->set('homepage', Request::is('/'));
        $this->metadata->title = Request::server('SERVER_NAME', 'localhost');
        $this->set('_metadata', $this->metadata);
        $this->metadata->title = env('app.name', 'Document');
    }

    protected function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    protected function noLayout()
    {
        $this->layout = null;

        return $this;
    }

    protected function detectLayout()
    {
        $router = app('router');
        $route = $router->current();
        $action = $route->getAction();
        preg_match('~^' . preg_quote($action['namespace'] . '\\', '~') . '([^@]+)Controller@(get|post|any)?(.*)' . '~i', $action['uses'], $matches);
        //dd($action);
        $controller = $matches[1];
        $action = $matches[3];
        //dd($this->metadata);
        $this->metadata->setMethod($controller, $action);
        $this->layout = strtolower($controller . DIRECTORY_SEPARATOR . $action);
    }

    protected function setupLayout()
    {
        if (null !== $this->layout) {
            $this->layout = \View::make($this->layout);
            $this->layout->with('_token', csrf_token());

            $message = \Session::pull('_message');
            if (null !== $message) {
                $this->layout->with('_message', $message);
            }
            $this->layout->with($this->data);
        }
    }

    public function callAction($method, $parameters)
    {
        $this->detectLayout();
        $this->before();
        $response = parent::callAction($method, $parameters);
        $user = \Auth::user();
        if (null !== $user) {
            $user->load('rights');
        }
        $this->set('_user', $user);

        if (null === $response && null !== $this->layout) {
            $this->setupLayout();
            $response = $this->layout;
        }

        $this->after();

        //замер жора памяти
        if(!in_array($method, [
            'getAnyUnread',
            'getServicePlans',
            'getRoadtripPlans',
            'getRightIds',
            'getMessengerPermissions',
            'checkPopupNotifications'])) {
            $memoryUsed = (new CommonHelper())->getMemoryUsed();
            $log = Request::url(). " -- {$memoryUsed} Mb";
            $log .= $memoryUsed > 50 ? ' !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!' : '';
            $today = today()->format('d-m-Y');
            (new FileHelper())->log($log, "logs/memory_info_{$today}.log");
        }


        return $response;
    }

    public function throwAccessDenied()
    {
        throw new AccessDeniedException();
    }

}
