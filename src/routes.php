<?php

/**
 * Index Route
 */
$app->get('/', function ($request, $response, $args) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->index($request, $response);
})->setName('home');


/**
 * View Profile
 */
$app->get('/run/view', function ($request, $response, $arg) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->view($request, $response, $arg);
})->setName('run.view');


/**
 * Single Delete profile view
 */
$app->get('/run/delete', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->deleteForm($request, $response);
})->setName('run.delete.form');


/**
 * Single Delete profile Submit
 */
$app->post('/run/delete', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->deleteSubmit($request, $response);
})->setName('run.delete.submit');


/**
 * Delete all profiles form
 */
$app->get('/run/delete_all', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->deleteAllForm($request, $response);
})->setName('run.deleteAll.form');

/**
 * Delete all profiles submit
 */
$app->post('/run/delete_all', function ($request, $response) use ($container, $app) {
    return $container['RunController']->deleteAllSubmit($request, $response);
})->setName('run.deleteAll.submit');

/**
 * View Url
 */
$app->get('/url/view', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->url($request, $response);
})->setName('url.view');


/**
 * View Compare
 */
$app->get('/run/compare', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->compare($request, $response);
})->setName('run.compare');


/**
 * View Symbol
 */
$app->get('/run/symbol', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->symbol($request, $response);
})->setName('run.symbol');

/**
 * View Symbol short
 */
$app->get('/run/symbol/short', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->symbolShort($request, $response);
})->setName('run.symbol-short');


/**
 * View Callgraph
 */
$app->get('/run/callgraph', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->callgraph($request, $response);
})->setName('run.callgraph');

/**
 * Get callgraph data
 */
$app->get('/run/callgraph/data', function ($request, $response) use ($container, $app) {
    $app->controller = $container['RunController'];
    return $app->controller->callgraphData($request, $response);
})->setName('run.callgraph.data');

/**
 * Get Callgraph dot
 */
$app->get('/run/callgraph/dot', function ($request, $response) use ($container, $app) {
    $app->controller = $container['runController'];
    return $app->controller->callgraphDataDot($request, $response);
})->setName('run.callgraph.dot');

// Import route
$app->post('/run/import', function () use ($container, $app) {
    $app->controller = $container['importController'];
    $app->controller->import();
})->setName('run.import');


// Watch function routes.
$app->get('/watch', function ($request, $response) use ($container, $app) {
    $app->controller = $container['WatchController'];
    return $app->controller->get($request, $response);
})->setName('watch.list');

$app->post('/watch', function ($request, $response) use ($container) {
    return $container['WatchController']->post($request, $response);
})->setName('watch.save');


// Custom report routes.
$app->get('/custom', function () use ($container, $app) {
    $app->controller = $container['customController'];
    $app->controller->get();
})->setName('custom.view');


/**
 * View Custom Help
 */
$app->get('/custom/help', function ($request, $response) use ($container, $app) {
    $app->controller = $container['CustomController'];
    return $app->controller->help($request, $response);
})->setName('custom.help');

$app->post('/custom/query', function () use ($container) {
    $container['customController']->query();
})->setName('custom.query');


// Waterfall routes
$app->get('/waterfall', function () use ($container, $app) {
    $app->controller = $container['waterfallController'];
    $app->controller->index();
})->setName('waterfall.list');

$app->get('/waterfall/data', function () use ($container) {
    $container['waterfallController']->query();
})->setName('waterfall.data');
