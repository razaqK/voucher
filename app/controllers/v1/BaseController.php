<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 6:47 PM
 */

namespace Voucher\Controllers\v1;

use \Phalcon\Mvc\Controller;
use Voucher\Traits\Response;
use Voucher\Library\GUMPHelper;
use Voucher\Library\Utils;
use Voucher\Constants\ResponseMessages;
use Voucher\Constants\ResponseCodes;

class BaseController extends Controller
{
    use Response;

    public function onConstruct()
    {
        $this->response->setContentType('application/json');
    }

    /**
     * @param $data
     * @param $parameters
     */
    protected function validateParameters($data, $parameters)
    {
        $validated = GUMPHelper::is_valid((array) $data, $parameters);
        if ($validated === true ) {
            return;
        }

        $messageList = Utils::stripHtmlTags($validated);
        $message = implode(',', $messageList);
        $this->sendError(sprintf(ResponseMessages::INVALID_PARAMS, $message), ResponseCodes::INVALID_PARAMS, 400,
            $messageList);
    }
}