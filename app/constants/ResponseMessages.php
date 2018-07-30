<?php

namespace Voucher\Constants;


class ResponseMessages
{
    const USER_EXIST = 'Seems the user already exist';
    const INVALID_PARAMS = 'Bad request!. The parameters sent are not according to the api spec. %s';
    const INTERNAL_SERVER_ERROR = 'There was an error processing your request. Please try again later.';
    const ERROR_CREATING = 'An error occurred. %s';
    const NOT_FOUND = 'Seems %s can not be found';
    const VOUCHER_CODE_ERROR = 'Seems the voucher code %s';
}