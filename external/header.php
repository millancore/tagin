<?php

define('TAJIN_HEADER', dirname(__DIR__));

// Use the config directory defined in the xhgui application.
define('XHGUI_CONFIG_DIR', dirname(__DIR__) . '/config/');

//Include autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Include Collector script.
require_once dirname(__DIR__) . '/src/Collector/external/header.php';
