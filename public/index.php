<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 6:45 PM
 */

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);

use Voucher\Constants\HttpStatusCodes;
use Voucher\Constants\ResponseMessages;
use Voucher\Constants\ResponseCodes;

try {

    /**
     * Read the configuration
     */
    ini_set('display_errors', 1);
    $config = include __DIR__ . "/../app/config/config.php";


    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    include __DIR__ . '/../app/config/functions.php';

    /**
     * Handle the request
     */
    $app = new \Phalcon\Mvc\Micro($di);

    include __DIR__ . "/../app/routes/api.php";


    /**
     * Not found handler
     */
    $app->notFound(function () use ($app) {
        $app->response->setHeader('Access-Control-Allow-Origin', '*');
        $app->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, PUT, DELETE, PATCH');
        $app->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Content-Length, User-Agent, Accept, Authorization')->sendHeaders();
        $app->response->setContentType('application/json');
        $app->response->setJsonContent([
            'status' => 'error',
            'message' => 'not implemented',
            'code' => 'E001'
        ]);
        $app->response->send();
    });

    $app->handle();

} catch (\Exception $e) {
    $app->response->setStatusCode(500, HttpStatusCodes::getMessage(500))->sendHeaders();

    echo json_encode([
        'status' => 'error',
        'message' => ResponseMessages::INTERNAL_SERVER_ERROR,
        'code' => ResponseCodes::INTERNAL_SERVER_ERROR,
        'ex' => $e->getMessage()
    ]);
}