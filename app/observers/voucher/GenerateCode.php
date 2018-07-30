<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/29/18
 * Time: 7:28 AM
 */

namespace Voucher\Observer\voucher;

use Phalcon\Events\Event;
use Voucher;

class GenerateCode
{
    public function afterOfferCreation(Event $event)
    {
        $data = (object)$event->getData();
        try {
            $voucher = new Voucher();
            $voucher->generateForAllRecipient($data->offer_id, $data->expiry_date, $data->expires_in);
        }catch (\Exception $e) {
            //ToDO log error
        }
    }
}