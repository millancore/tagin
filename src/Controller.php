<?php

namespace Tagin;

use Slim\App;
use Slim\Container;

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

    public function render()
    {
        $this->app->render($this->_template, $this->_templateVars);
    }

}
