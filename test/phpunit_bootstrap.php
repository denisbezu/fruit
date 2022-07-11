<?php

$basedir = '/var/www/html/';
require_once $basedir . 'config/config.inc.php';

require_once __DIR__ . '/../vendor/autoload.php';

// init a default front controller with context, cart,
$controller = new FrontController();
$controller->init();
