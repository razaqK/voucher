<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:06 PM
 */

namespace Voucher\Controllers\v1;

use Voucher\Library\Utils;
use User;
use Voucher\Constants\ResponseMessages;
use Voucher\Constants\ResponseCodes;
use Exception;

class UserController extends BaseController
{
    /**
     * Endpoint for user creation
     * @SWG\Post(
     *     path="/user",
     *     tags={"User"},
     *     operationId="createUser",
     *     summary="Creating user by supplying name and email.",
     *     description="User creation with name and email. Note that the email to be used not exist before",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="payload needed to complete request",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/CreateUserPayload"),
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
            'email' => 'required|valid_email'
        ]);

        try {
            $user = new User();
            $user = $user->add($payload->name, $payload->email);
            if (!$user) {
                $this->sendError(ResponseMessages::USER_EXIST, ResponseCodes::USER_EXIST, 400);
            }
            $this->sendSuccess([
                'email' => $user->email
            ]);
        } catch (Exception $e) {
            $this->sendError(sprintf(ResponseMessages::ERROR_CREATING, $e->getMessage()), ResponseCodes::ERROR_CREATING);
        }
    }

    /**
     * Endpoint to get special offer info about user
     * @param $email
     * @SWG\Get(
     *     path="/user/{email}/vouchers",
     *     tags={"User"},
     *     operationId="getOfferInfo",
     *     summary="Endpoint to get special offer info about user",
     *     description="Endpoint to get special offer info about user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="email to get vouchers",
     *         required=true,
     *         type="string"
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
     *     ),
     *     @SWG\Response(response="default",
     *         description="unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function getOfferInfo($email)
    {
        /** @var User $user */
        $user = User::getByField($email, 'email');
        if (empty($user)) {
            $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'user account'), ResponseCodes::NOT_FOUND, 404);
        }

        $info = $user->getInfo();
        if (empty($info)) {
            $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'info'), ResponseCodes::NOT_FOUND, 404);
        }

        $this->sendSuccess($info);
    }
}