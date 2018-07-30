<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:53 PM
 */

namespace Voucher\Controllers\v1;

use Voucher\Library\Utils;
use SpecialOffer;
use Voucher\Constants\ResponseMessages;
use Voucher\Constants\ResponseCodes;
use Carbon\Carbon;
use Exception;
use EventObserver;
use Voucher\Observer\voucher\GenerateCode;

class SpecialOfferController extends BaseController
{
    /**
     * Endpoint for special offer creation
     * @SWG\Post(
     *     path="/special_offer",
     *     tags={"Offer"},
     *     operationId="createOffer",
     *     summary="Creating offer by supplying name and discount. If voucher code are to be generated then generate_voucher_code parameter should be send with true or 1 value",
     *     description="Offer creation with name and discount. Note that to generate voucher code for recipients then generate_voucher_code parameter should be send with true or 1 value",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="payload needed to complete request",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateOfferPayload"),
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
    public function create()
    {
        $payload = Utils::sanitizeInput($this->request->getJsonRawBody());
        $this->validateParameters($payload, [
            'name' => 'required|is_string',
            'discount' => 'required|integer',
            'generate_voucher_code' => 'boolean'
        ]);

        try {
            $offer = new SpecialOffer();
            $offer = $offer->add($payload->name, $payload->discount);
            if (!$offer) {
                $this->sendError(sprintf(ResponseMessages::ERROR_CREATING, 'Unable to create offer.'), ResponseCodes::ERROR_CREATING, 400);
            }

            if (isset($payload->generate_voucher_code) && $payload->generate_voucher_code) {
                $this->validateParameters($payload, [
                    'expires_in' => 'required|integer'
                ]);
                $params = [
                    'offer_id' => $offer->id,
                    'expires_in' => $payload->expires_in,
                    'expiry_date' => Carbon::now()->addSeconds($payload->expires_in)->toDateTimeString(),
                ];
                $fireEvt = new EventObserver('voucher:afterOfferCreation', GenerateCode::class, $params);
                $fireEvt->fireEvent();
            }
            $this->sendSuccess($offer);
        } catch (Exception $e) {
            $this->sendError(sprintf(ResponseMessages::ERROR_CREATING, $e->getMessage()), ResponseCodes::ERROR_CREATING);
        }
    }
}