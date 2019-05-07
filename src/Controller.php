<?php

namespace Tagin;

use Slim\App;
use Slim\Container;
use Slim\Http\Response;

class Controller
{
    protected $_templateVars = array();
    protected $_template = null;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var Container
     */
    protected $container;

    public function set($vars)
    {
        $this->_templateVars = array_merge($this->_templateVars, $vars);
    }

    public function templateVars()
    {
        return $this->_templateVars;
    }

    public function config(string $key)
    {
        $config = $this->container['config'];

        if (isset($config[$key])) {
            return $config[$key];
        }

        return null;
    }

    public function render(Response $response)
    {
        return $this->container['view']->render($response, $this->_template, $this->_templateVars);
    }
}
