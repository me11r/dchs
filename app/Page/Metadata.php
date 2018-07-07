<?php
namespace App\Page;

class Metadata
{
    public    $append_separator = ' • ';
    protected $data             = [];
    public $module = '';
    public $action = '';

    public function __construct()
    {
        $this->data['title'] = \Request::server('SERVER_NAME', 'localhost');
    }

    public function setMethod($module, $action)
    {
        $this->module = strtolower($module);
        $this->action = strtolower($action);
    }

    public function title($title, $append = true)
    {
        if ($append)
        {
            $this->set('title', $title . $this->append_separator . $this->get('title'));
        } else
        {
            $this->set('title', $title);
        }

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __get($key)
    {
        return isset($this->data[$key])?$this->data[$key]:null;
    }
}