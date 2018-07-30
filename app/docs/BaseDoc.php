<?php
/**
 * @SWG\Swagger(
 *   schemes={"https"},
 *   host="",
 *   basePath="/v1",
 *   @SWG\Info(
 *     title="Voucher API Documentation",
 *     version="1.0.0",
 *     @SWG\Contact(name="Voucher API Team")
 *   ),
 *     @SWG\Definition(
 *         definition="ErrorModel",
 *         type="object",
 *         required={"status", "message", "code"},
 *         @SWG\Property(
 *             property="status",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="code",
 *             type="string"
 *         ),
 *     ),
 *     @SWG\Definition(
 *         definition="SuccessModel",
 *         type="object",
 *         required={"status", "data"},
 *         @SWG\Property(
 *             property="status",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="data",
 *             type="object"
 *         )
 *     ),
 *     @SWG\Definition(
 *         definition="CreateUserPayload",
 *         type="object",
 *         required={"email", "name"},
 *         @SWG\Property(
 *             property="email",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="name",
 *             type="string"
 *         )
 *     ),
 *     @SWG\Definition(
 *         definition="CreateOfferPayload",
 *         type="object",
 *         required={"name", "discount"},
 *         @SWG\Property(
 *             property="name",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="discount",
 *             type="number"
 *         ),
 *         @SWG\Property(
 *             property="generate_voucher_Code",
 *             type="boolean"
 *         )
 *     ),
 *     @SWG\Definition(
 *         definition="GenerateCodePayload",
 *         type="object",
 *         required={"special_offer_id", "email", "expires_in"},
 *         @SWG\Property(
 *             property="special_offer_id",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="email",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="expires_in",
 *             type="integer"
 *         )
 *     ),
 *     @SWG\Definition(
 *         definition="GenerateCodeForAllRecipientPayload",
 *         type="object",
 *         required={"special_offer_id", "expires_in"},
 *         @SWG\Property(
 *             property="special_offer_id",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="expires_in",
 *             type="integer"
 *         )
 *     ),
 *     @SWG\Definition(
 *         definition="RedeemCodePayload",
 *         type="object",
 *         required={"voucher_code", "email"},
 *         @SWG\Property(
 *             property="voucher_code",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="email",
 *             type="string"
 *         )
 *     )
 * )
 */