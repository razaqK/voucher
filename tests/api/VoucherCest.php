<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/29/18
 * Time: 9:43 PM
 */

class VoucherCest
{
    private $voucher;

    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    private function setVoucher($voucher)
    {
        $this->voucher = $voucher;
    }

    private function getVoucher()
    {
        return $this->voucher;
    }

    public function testVoucherGeneration(ApiTester $I)
    {
        $url = '/v1/voucher';
        $I->wantTo('SUCCESS CASE - generate voucher code for a user');

        $I->sendPOST($url, [
            'special_offer_id' => 1,
            'email' => 'abc@gmail.com',
            'expires_in' => 300,
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContains('code', 'expiry_date');
        $I->seeResponseContainsJson(
            [
                'status' => 'success'
            ]
        );

        $this->setVoucher(json_decode($I->grabResponse()));
    }

    public function testVoucherGenerationForInvalidUser(ApiTester $I)
    {
        $url = '/v1/voucher';
        $I->wantTo('FAIL CASE - generate voucher for invalid user');

        $I->sendPOST($url, [
            'special_offer_id' => 1,
            'email' => 'abcde@gmail.com',
            'expires_in' => 300
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0005'
            ]
        );
    }

    public function testVoucherGenerationWithBadRequest(ApiTester $I)
    {
        $url = '/v1/voucher';
        $I->wantTo('FAIL CASE - generate voucher by supplying invalid parameter');

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

    public function testVoucherGenerationForAllUsers(ApiTester $I)
    {
        $url = '/v1/voucher/recipients';
        $I->wantTo('SUCCESS CASE - generate voucher code for all users');

        $I->sendPOST($url, [
            'special_offer_id' => 2,
            'expires_in' => 300,
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'success'
            ]
        );
    }

    public function testRedeemVoucherCode(ApiTester $I)
    {
        $url = '/v1/voucher/redeem';
        $I->wantTo('SUCCESS CASE - redeem voucher code');

        $I->sendPOST($url, [
            'voucher_code' => $this->getVoucher()->data->code,
            'email' => 'abc@gmail.com'
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContains('discount_percentage');
        $I->seeResponseContainsJson(
            [
                'status' => 'success'
            ]
        );
    }

    public function testRedeemInvalidVoucherCode(ApiTester $I)
    {
        $url = '/v1/voucher/redeem';
        $I->wantTo('FAIL CASE  - redeem invalid voucher code');

        $I->sendPOST($url, [
            'voucher_code' => 'ERUU123',
            'email' => 'abc@gmail.com'
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0006'
            ]
        );
    }

    public function testRedeemUsedVoucherCode(ApiTester $I)
    {
        $url = '/v1/voucher/redeem';
        $I->wantTo('FAIL CASE  - redeem already used voucher code');

        $I->sendPOST($url, [
            'voucher_code' => $this->getVoucher()->data->code,
            'email' => 'abc@gmail.com'
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0006'
            ]
        );
    }

    public function testRedeemVoucherCodeWithInvalidUser(ApiTester $I)
    {
        $url = '/v1/voucher/redeem';
        $I->wantTo('FAIL CASE  - redeem voucher code using invalid user');

        $I->sendPOST($url, [
            'voucher_code' => $this->getVoucher()->data->code,
            'email' => 'abcio@gmail.com'
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(
            [
                'status' => 'error',
                'code' => 'E0005'
            ]
        );
    }


}