<?php

define('TAJIN_HEADER', dirname(__DIR__));

// Use the config directory defined in the xhgui application.
define('XHGUI_CONFIG_DIR', dirname(__DIR__) . '/config/');

//Include autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Include collector script.
require_once dirname(__DIR__) . '/src/collector/external/header.php';
