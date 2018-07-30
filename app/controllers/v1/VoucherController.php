<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 8:39 PM
 */

namespace Voucher\Controllers\v1;

use Voucher\Constants\Status;
use Voucher\Library\Utils;
use Voucher;
use Voucher\Constants\ResponseMessages;
use Voucher\Constants\ResponseCodes;
use User;
use Carbon\Carbon;
use Exception;

class VoucherController extends BaseController
{
    /**
     * Endpoint for generating voucher code
     * @SWG\Post(
     *     path="/voucher",
     *     tags={"Voucher"},
     *     operationId="generateVoucher",
     *     summary="Generating of voucher code to offer discount to recipient.",
     *     description="Endpoint to generate voucher code.",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="payload needed to complete request",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/GenerateCodePayload"),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *         @SWG\Schema(ref="#/definitions/SuccessModel")
     *     ),
     *     @SWG\Response(response="400",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(response="500",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function generateCode()
    {
        $payload = Utils::sanitizeInput($this->request->getJsonRawBody());
        $this->validateParameters($payload, [
            'special_offer_id' => 'required|integer',
            'email' => 'required|valid_email',
            'expires_in' => 'required|integer'
        ]);

        try {
            $user = User::getByField($payload->email, 'email');
            if (empty($user)) {
                $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'user account'), ResponseCodes::NOT_FOUND, 404);
            }
            $voucher = new Voucher();
            $voucher = $voucher->generate($payload->special_offer_id, $user->id, Carbon::now()->addSeconds($payload->expires_in)->toDateTimeString(), $payload->expires_in);
            if (!$voucher) {
                $this->sendError(ResponseMessages::USER_EXIST, ResponseCodes::USER_EXIST, 400);
            }
            $this->sendSuccess([
                'code' => $voucher->code,
                'expiry_date' => $voucher->expiry_date
            ]);
        } catch (Exception $e) {
            $this->sendError(sprintf(ResponseMessages::ERROR_CREATING, $e->getMessage()), ResponseCodes::ERROR_CREATING, 400);
        }
    }

    /**
     * Endpoint for generating voucher code for all recipients
     * @SWG\Post(
     *     path="/voucher/recipients",
     *     tags={"Voucher"},
     *     operationId="generateCodeForAllRecipient",
     *     summary="Endpoint for generating voucher code for all recipients",
     *     description="Endpoint for generating voucher code for all recipients",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="payload needed to complete request",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/GenerateCodeForAllRecipientPayload"),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *         @SWG\Schema(ref="#/definitions/SuccessModel")
     *     ),
     *     @SWG\Response(response="400",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(response="500",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function generateCodeForAllRecipient()
    {
        $payload = Utils::sanitizeInput($this->request->getJsonRawBody());
        $this->validateParameters($payload, [
            'special_offer_id' => 'required',
            'expires_in' => 'required|integer'
        ]);

        try {
            $voucher = new Voucher();
            $voucher->generateForAllRecipient($payload->special_offer_id, Carbon::now()->addSeconds($payload->expires_in)->toDateTimeString(), $payload->expires_in);
            $this->sendSuccess([]);
        } catch (Exception $e) {
            $this->sendError(sprintf(ResponseMessages::ERROR_CREATING, $e->getMessage()), ResponseCodes::ERROR_CREATING);
        }
    }

    /**
     * Endpoint for verifying and redeeming of voucher code
     * @SWG\Post(
     *     path="/voucher/redeem",
     *     tags={"Voucher"},
     *     operationId="redeemCode",
     *     summary="Endpoint for verifying and redeeming of voucher code",
     *     description="Endpoint for verifying and redeeming of voucher code",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="payload needed to complete request",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/RedeemCodePayload"),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *         @SWG\Schema(ref="#/definitions/SuccessModel")
     *     ),
     *     @SWG\Response(response="400",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(response="500",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function redeemCode()
    {
        $payload = Utils::sanitizeInput($this->request->getJsonRawBody());
        $this->validateParameters($payload, [
            'voucher_code' => 'required',
            'email' => 'required|valid_email'
        ]);

        $user = User::getByField($payload->email, 'email');
        if (empty($user)) {
            $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'user account'), ResponseCodes::NOT_FOUND, 404);
        }

        /** @var Voucher $code */
        $code = Voucher::getByFields(['code' => $payload->voucher_code, 'user_id' => $user->id], 'AND', null, true);
        if (empty($code)) {
            $this->sendError(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'provided is invalid'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
        }

        if ($code->isUsed()) {
            $this->sendError(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'has been used.'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
        }

        if ($code->hasExpired()) {
            $code->status = Status::DISABLED;
            $code->save();
            $this->sendError(sprintf(ResponseMessages::VOUCHER_CODE_ERROR, 'has expired'), ResponseCodes::VOUCHER_CODE_ERROR, 400);
        }

        $code->updateUsage();

        $this->sendSuccess([
            'discount_percentage' => $code->specialOffer->discount
        ]);
    }

    public function getAll()
    {
        $query = $this->request->getQuery();

        $this->validateParameters($query, [
            'page' => 'integer|min_numeric,0',
            'limit' => 'integer|min_numeric,1'
        ]);

        $limit = !empty($query['limit']) ? $query['limit'] : 100;
        $page = !empty($query['page']) ? $query['page'] : 1;

        $this->sendSuccess(Voucher::getAll($page,$limit));
    }
}