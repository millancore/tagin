<?php

namespace Tagin\Middleware;

class Render
{
    public function call()
    {
        $app = $this->app;

        // Run the controller action/route function
        $this->next->call();

        // Render the template.
        if (isset($app->controller)) {
            $app->controller->render();
        }
    }

}
