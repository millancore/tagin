<?php

/** Trigger manual loader class */
if (!defined('TAGIN_TEST')) {
    define('TAGIN_TEST', true);
}

if (!defined('TAJIN_HEADER')) {
    define('TAJIN_HEADER', dirname(__DIR__));
}

// Use the config directory defined in the Tagin application.
if (!defined('XHGUI_CONFIG_DIR')) {
    define('XHGUI_CONFIG_DIR', dirname(__DIR__) . '/config/');
}

//Include autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Include Collector script.
require_once dirname(__DIR__) . '/src/Collector/header.php';
