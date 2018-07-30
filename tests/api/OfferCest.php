<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/29/18
 * Time: 9:36 PM
 */

class OfferCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function testSpecialOfferCreation(ApiTester $I)
    {
        $url = '/v1/special_offer';
        $I->wantTo('SUCCESS CASE - creation of special offer');

        $I->sendPOST($url, [
            'name' => 'bloom',
            'discount' => 80
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContains('name', 'discount');
        $I->seeResponseContainsJson(
            [
                'status' => 'success'
            ]
        );
    }

    public function testSpecialOfferCreationBadRequest(ApiTester $I)
    {
        $url = '/v1/special_offer';
        $I->wantTo('FAIL CASE - creation of special with bad request');

        $I->sendPOST($url, [
            'name' => ''
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0002'
            ]
        );
    }
}