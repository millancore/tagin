<?php
/**
 * Boostrapping and common utility definition.
 */
define('TAGIN_ROOT', dirname(__DIR__));

require_once TAGIN_ROOT.'/vendor/autoload.php';

use Tagin\Config;

Config::load(TAGIN_ROOT . '/config/config.default.php');


if (file_exists(TAGIN_ROOT . '/config/config.php')) {
    Config::load(TAGIN_ROOT . '/config/config.php');
}
