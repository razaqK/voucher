<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/29/18
 * Time: 7:47 AM
 */


class UserCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function testUserCreation(ApiTester $I)
    {
        $url = '/v1/user';
        $I->wantTo('SUCCESS CASE - creation of user');

        $I->sendPOST($url, [
            'name' => 'abc voucher',
            'email' => 'abc@gmail.com',
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContains('email');
        $I->seeResponseContainsJson(
            [
                'status' => 'success'
            ]
        );
    }

    public function testUserCreationWithEmailAlreadyExist(ApiTester $I)
    {
        $url = '/v1/user';
        $I->wantTo('FAIL CASE - creation of user with already existing email');

        $I->sendPOST($url, [
            'name' => 'abc voucher',
            'email' => 'abc@gmail.com',
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0001'
            ]
        );
    }

    public function testUserCreationBadRequest(ApiTester $I)
    {
        $url = '/v1/user';
        $I->wantTo('FAIL CASE - creation of user with invalid email');

        $I->sendPOST($url, [
            'name' => 'abc voucher',
            'email' => 'abc',
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