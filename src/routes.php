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

$app->get('/run/delete_all', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->deleteAllForm();
})->setName('run.deleteAll.form');

$app->post('/run/delete_all', function () use ($container, $app) {
    $container['runController']->deleteAllSubmit();
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

$app->get('/run/symbol/short', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->symbolShort();
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

$app->get('/run/callgraph/dot', function () use ($container, $app) {
    $container['runController']->callgraphDataDot();
})->setName('run.callgraph.dot');

// Import route
$app->post('/run/import', function () use ($container, $app) {
    $app->controller = $container['importController'];
    $app->controller->import();
})->setName('run.import');


// Watch function routes.
$app->get('/watch', function () use ($container, $app) {
    $app->controller = $container['watchController'];
    $app->controller->get();
})->setName('watch.list');

$app->post('/watch', function () use ($container) {
    $container['watchController']->post();
})->setName('watch.save');


// Custom report routes.
$app->get('/custom', function () use ($container, $app) {
    $app->controller = $container['customController'];
    $app->controller->get();
})->setName('custom.view');

$app->get('/custom/help', function () use ($container, $app) {
    $app->controller = $container['customController'];
    $app->controller->help();
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
