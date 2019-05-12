<?php

namespace Tagin\Controller;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Tagin\Controller;
use Tagin\Profiles;

class CustomController extends Controller
{
    /**
     * @var Profiles
     */
    protected $profiles;

    public function __construct(App $app, Profiles $profiles)
    {
        $this->app = $app;
        $this->profiles = $profiles;
    }

    public function get()
    {
        $this->_template = 'custom/create.twig';
    }

    public function help(Request $request, Response $response)
    {
        if ($request->getParam('id')) {
            $res = $this->profiles->get($request->getParam('id'));
        } else {
            $res = $this->profiles->latest();
        }

        $this->_template = 'custom/help.twig';
        $this->set(array(
            'data' => print_r($res->toArray(), 1)
        ));

        $this->render($response);
    }

    public function query(Request $request, Response $response)
    {
        $query = json_decode($request->getParam('query'), true);
        $error = array();
        if (is_null($query)) {
            $error['query'] = json_last_error();
        }

        $retrieve = json_decode($request->getParam('retrieve'), true);
        if (is_null($retrieve)) {
            $error['retrieve'] = json_last_error();
        }

        if (count($error) > 0) {
            $json = json_encode(array('error' => $error));
            return $response->body($json);
        }

        $perPage = $this->config('page.limit');

        $res = $this->profiles->query($query, $retrieve)
            ->limit($perPage);
        $r = iterator_to_array($res);

        return $response->withJson($r);
    }
}
