<?php

use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;

defined('APP_PATH') || define('APP_PATH', dirname(dirname(dirname(__FILE__))));
require APP_PATH . "/vendor/autoload.php";

$config = include APP_PATH . "/app/config/config.php";

require APP_PATH . "/app/config/loader.php";

$di = new FactoryDefault;

require APP_PATH . "/app/config/services.php";

date_default_timezone_set('UTC');

$application = new Application;
$application->setDI($di);
return $application;
