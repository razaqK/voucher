<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        realpath($config->application->libraryDir),
        realpath($config->application->modelsDir),
        realpath($config->application->constantsDir),
        realpath($config->application->controllersDir),
        realpath($config->application->traitDir),
        realpath($config->application->routesDir),
        realpath($config->application->observerDir),
        realpath($config->application->observerConfigDir),
    )
)->register();
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    array(
        'Voucher\Constants' => $config->application->constantsDir,
        'Voucher\Controllers' => $config->application->controllersDir,
        'Voucher\Models' => $config->application->modelsDir,
        'Voucher\Library' => $config->application->libraryDir,
        'Voucher\Traits' => $config->application->traitDir,
        'Voucher\Observer' => $config->application->observerDir
    )
)->register();
