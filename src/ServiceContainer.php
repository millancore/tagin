<?php

namespace Tagin;

use MongoDB\Client;
use Slim\App;
use Tagin\Config;
use Slim\Container;
use Slim\Views\Twig;
use Tagin\Controller;
use Tagin\Twig\Extension;
use Tagin\Middleware\Render;

class ServiceContainer extends Container
{

    protected static $_instance;

    public static function instance()
    {
        if (empty(static::$_instance)) {
            static::$_instance = new self();
        }
        return static::$_instance;
    }


    public function __construct()
    {
        parent::__construct([
            'settings' => [
                'displayErrorDetails' => true,
            ]
        ]);
        $this->init();
    }

    private function init()
    {
        $this['config'] = Config::all();
        $this->startApp();
        $this->_services();
        $this->_controllers();
    }


    // Create the Slim App.
    protected function startApp()
    {
        $this['app'] = function ($container) {
            return new App($container);
        };


        $this['view'] = function ($container) {

            $cacheDir = isset($container['config']['cache']) ? $container['config']['cache'] : TAGIN_ROOT . '/cache';

            $view = new Twig(TAGIN_ROOT . '/src/templates', [$cacheDir]);

            $view->addExtension(new Extension($container['app']));

            $view->parserOptions = array(
                'charset' => 'utf-8',
                'cache' => $cacheDir,
                'auto_reload' => true,
                'strict_variables' => false,
                'autoescape' => true
            );


            return $view;
        };


        /*$this['view'] = function ($container) {
            $cacheDir = isset($container['config']['cache']) ? $container['config']['cache'] : TAGIN_ROOT . '/cache';

            // Configure Twig view for slim
            $view = new Twig(TAGIN_ROOT.'/src/templates', [
                $cacheDir
            ]);

            $view->parserOptions = array(
                'charset' => 'utf-8',
                'cache' => $cacheDir,
                'auto_reload' => true,
                'strict_variables' => false,
                'autoescape' => true
            );

            return $view;
        };

        $this['app'] = function ($container) {
            $app = new App($container['config']);

            // Add renderer.
            $app->add(new Render());

            $view = $container['view'];
            $view->parserExtensions = array(
                new Extension($app)
            );
            $app->view($view);

            return $app;
        };*/
    }

    /**
     * Add common service objects to the container.
     */
    protected function _services()
    {
        $this['db'] = function ($container) {

            $config = $container['config'];

            if (empty($config['db.options'])) {
                $config['db.options'] = array();
            }

            $mongo = new Client($config['db.host']);
            return $mongo->{$config['db.database']};
        };

        $this['watchFunctions'] = function ($container) {
            return new WatchFunctions($container['db']);
        };

        $this['profiles'] = function ($container) {
            return new Profiles($container['db']);
        };

        $this['saver'] = function ($container) {
            return Saver::factory($container['config']);
        };

        $this['saverMongo'] = function ($container) {
            $config = $container['config'];
            $config['save.handler'] = 'mongodb';

            return Saver::factory($config);
        };
    }

    /**
     * Add controllers to the DI container.
     */
    protected function _controllers()
    {
        $this['watchController'] = function ($container) {
            return new Xhgui_Controller_Watch($container['app'], $container['watchFunctions']);
        };

        $this['IndexController'] = function ($container) {
            return new Controller\IndexController($container['app'], $container['profiles'], $container['watchFunctions']);
        };

        $this['customController'] = function ($container) {
            return new Xhgui_Controller_Custom($container['app'], $container['profiles']);
        };

        $this['waterfallController'] = function ($container) {
            return new Xhgui_Controller_Waterfall($container['app'], $container['profiles']);
        };

        $this['importController'] = function ($container) {
            return new Xhgui_Controller_Import($container['app'], $container['saverMongo']);
        };
    }

}
