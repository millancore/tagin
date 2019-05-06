<?php

namespace Tagin\Middleware;

class RenderMiddleware
{
    public function call()
    {
        $app = $this->app;

        // Run the controller action/route function
        $this->next->call();


    }


    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        // RenderMiddleware the template.
        if (isset($app->controller)) {
            $app->controller->render();
        }

        return $response;
    }

}
