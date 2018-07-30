<?php

/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 6:45 PM
 */

namespace Voucher\Traits;

use Voucher\Constants\Constants;
use Voucher\Constants\HttpStatusCodes;


trait Response
{

    private function setOrigin($statusCode)
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, PUT, DELETE, PATCH');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Content-Length, User-Agent, Accept, Authorization');
        $this->response->setStatusCode($statusCode, HttpStatusCodes::getMessage($statusCode))->sendHeaders();
    }

    /**
     * Send a success response if an API call was successful
     *
     * @param $data
     */
    public function sendSuccess($data)
    {
        $this->setOrigin(200);

        $response = [
            'status' => Constants::SUCCESS,
            'data' => (is_array($data) && sizeof($data) == 0) ? new \stdClass() : $data
        ];

        $this->response->setJsonContent($response);

        if (!$this->response->isSent()) {
            $this->response->send();
        }
    }

    /**
     * Send an error response if an API call failed
     *
     * @param      $message
     * @param      $error_code
     * @param int $http_status_code
     * @param null $data
     */
    public function sendError($message, $error_code, $http_status_code = 500, $data = null)
    {
        if (is_array($message)) {
            $message = $message['message'];
        }

        $response = [
            'status' => Constants::ERROR,
            'message' => $message,
            'code' => $error_code,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        $this->setOrigin($http_status_code);
        $this->response->setJsonContent($response);

        if (!$this->response->isSent()) {
            $this->response->send();
        }
        // Prevent further processing once an error is returned
        exit;
    }

    /**
     * Send a api doc response
     *
     * @param $data
     */
    public function sendDocResponse($data)
    {
        $this->response->setStatusCode(200, HttpStatusCodes::getMessage(200));
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')->sendHeaders();

        $this->response->setJsonContent($data);

        if (!$this->response->isSent()) {
            $this->response->send();
        }

        exit;
    }
}