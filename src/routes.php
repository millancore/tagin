<?php

// Profile Runs routes
$app->get('/', function ($request, $response, $args) use ($container, $app) {
    $app->controller = $container['IndexController'];
    return $app->controller->index($request, $response);
})->setName('home');

$app->get('/run/view', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->view();
})->setName('run.view');

$app->get('/run/delete', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->deleteForm();
})->setName('run.delete.form');

$app->post('/run/delete', function () use ($container, $app) {
    $container['runController']->deleteSubmit();
})->setName('run.delete.submit');

$app->get('/run/delete_all', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->deleteAllForm();
})->setName('run.deleteAll.form');

$app->post('/run/delete_all', function () use ($container, $app) {
    $container['runController']->deleteAllSubmit();
})->setName('run.deleteAll.submit');

$app->get('/url/view', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->url();
})->setName('url.view');

$app->get('/run/compare', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->compare();
})->setName('run.compare');

$app->get('/run/symbol', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->symbol();
})->setName('run.symbol');

$app->get('/run/symbol/short', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->symbolShort();
})->setName('run.symbol-short');

$app->get('/run/callgraph', function () use ($container, $app) {
    $app->controller = $container['runController'];
    $app->controller->callgraph();
})->setName('run.callgraph');

$app->get('/run/callgraph/data', function () use ($container, $app) {
    $container['runController']->callgraphData();
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
