<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/29/18
 * Time: 1:02 PM
 */

namespace Voucher\Controllers\v1;

class DocController extends BaseController
{
    public function documentation()
    {
        $swagger = \Swagger\scan(__DIR__ . '/../..');

        $this->sendDocResponse($swagger);
    }
}