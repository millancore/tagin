<?php

namespace Tagin\Controller;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Tagin\Controller;
use Tagin\WatchFunctions;

class WatchController extends Controller
{
    protected $watches;

    public function __construct(App $app, WatchFunctions $watches)
    {
        $this->app = $app;
        $this->watches = $watches;
    }

    public function get(Request $request, Response $response)
    {
        $watched = $this->watches->getAll();

        $this->_template = 'watch/list.twig';
        $this->set(array('watched' => $watched));

        $this->render($response);
    }

    public function post(Request $request, Response $response)
    {
        $app = $this->app;
        $watches = $this->watches;

        $saved = false;

        foreach ((array)$request->getParam('watch') as $data) {
            $saved = true;
            $watches->save($data);
        }
        if ($saved) {
            //TODO: Recovery Flash
            //$app->flash('success', 'Watch functions updated.');
        }
        $app->redirect($app->urlFor('watch.list'));
    }
}
