<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 6:41 PM
 */

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Voucher\Controllers\v1\DocController;
use Voucher\Controllers\v1\UserController;
use Voucher\Controllers\v1\VoucherController;
use Voucher\Controllers\v1\SpecialOfferController;

/**
 * Expose endpoints
 */

$user = new MicroCollection();
$user->setHandler(UserController::class, true);
$user->setPrefix('/v1/user');
$user->post('/', 'create');
$user->get('/{email}/vouchers', 'getOfferInfo');
$app->mount($user);


$voucher = new MicroCollection();
$voucher->setHandler(VoucherController::class, true);
$voucher->setPrefix('/v1');
$voucher->post('/voucher', 'generateCode');
$voucher->post('/voucher/recipients', 'generateCodeForAllRecipient');
$voucher->post('/voucher/redeem', 'redeemCode');
$voucher->get('/vouchers', 'getAll');
$app->mount($voucher);

$offer = new MicroCollection();
$offer->setHandler(SpecialOfferController::class, true);
$offer->setPrefix('/v1/special_offer');
$offer->post('/', 'create');
$app->mount($offer);

$apiDoc = new MicroCollection();
$apiDoc->setHandler(DocController::class, true)
    ->setPrefix('/v1/api/docs')
    ->get('/', 'documentation', 'API Documentation');
$app->mount($apiDoc);