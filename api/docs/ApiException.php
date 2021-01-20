<?php
namespace app\src\exception;

class ApiException extends \Exception
{
    // error codes
    const BASE_OFFSET = 1000;

    const GENERAL_OFFSET = self::BASE_OFFSET + 100;
    // general error response
    /**
     * @code 1101
     * @codeMessage Model {model} not found.
     * @codeResponse {
     *      "message": "Model user not found.",
     *      "code": 1101,
     *      "model": "user",
     *      "success": false
     * }
     * @codeDescription The reason of this could be the incorrect identity
     */
    const MODEL_NOT_FOUND = self::GENERAL_OFFSET + 1;

    /**
     * @code 1102
     * @codeMessage Required param {param} not found.
     * @codeResponse {
     *      "message": "Required param id not found.",
     *      "code": 1102,
     *      "param": "id",
     *      "success": false
     * }
     * @codeDescription The reason of this could be incorrect format of params or because they weren"t be sent.
     */
    const REQUIRED_PARAMS_NOT_FOUND = self::GENERAL_OFFSET + 2;

    /**
     * @code 1103
     * @codeMessage Param {param} must be unique.
     * @codeResponse {
     *      "message": "Param username must be unique.",
     *      "code": 1103,
     *      "param": "username",
     *      "success": false
     * }
     * @codeDescription This could be because of not unique param.
     */
    const PARAM_MUST_BE_UNIQUE = self::GENERAL_OFFSET + 3;

    /**
     * @code 1104
     * @codeMessage Relation {param} already exist.
     * @codeResponse {
     *      "message": "Relation customer-place already exist.",
     *      "code": 1104,
     *      "relation": "customer-club",
     *      "success": false
     * }
     * @codeDescription This could be because relation is already exist.
     */
    const RELATION_ALREADY_EXIST = self::GENERAL_OFFSET + 4;

    const SPECIAL_OFFSET = self::BASE_OFFSET + 200;

    /**
     * @code 1201
     * @codeMessage Capacity ({capacity}) limit reached.
     * @codeResponse {
     *      "message": "Capacity (3) limit reached.",
     *      "code": 1201,
     *      "capacity": 3,
     *      "success": false
     * }
     * @codeDescription This can be because the training capacity limit is exceeded.
     */
    const EVENT_CAPACITY_LIMIT = self::SPECIAL_OFFSET + 1;

    /**
     * @code 1202
     * @codeMessage The subscription to the event is ready.
     * @codeResponse {
     *      "message": "The subscription to the event is ready.",
     *      "code": 1202,
     *      "success": false
     * }
     * @codeDescription The reason could be that the user isn"t signed on an event.
     */
    const USER_EVENT_SUBSCRIBE_EXIST = self::SPECIAL_OFFSET + 2;

    /**
     * @code 1203
     * @codeMessage Identity or password incorrect.
     * @codeResponse {
     *       "message": "Identity or password incorrect.",
     *       "code": 1203,
     *       "success": false
     * }
     * @codeDescription The reason of this could be the incorrect identity or password
     */
    const IDENTITY_OR_PASSWORD_INCORRECT = self::SPECIAL_OFFSET + 3;

    /**
     * @code 1204
     * @codeMessage ID or token incorrect.
     * @codeResponse {
     *      "message": "ID or token incorrect.",
     *      "code": 1204,
     *      "success": false
     * }
     * @codeDescription The reason of this could be the incorrect users facebook id or facebook token
     */
    const ID_OR_TOKEN_INCORRECT = self::SPECIAL_OFFSET + 4;

    /**
     * @code 1205
     * @codeMessage Facebook API bad response. Check the token and userId and try again.
     * @codeResponse {
     *      "message": "Facebook API bad response. Check the token and userId and try again.",
     *      "code": 1205,
     *      "success": false
     * }
     * @codeDescription The reason could be that the user are't registered on Facebook or the wrong params of inquiry.
     */
    const FB_API_BAD_RESPONSE = self::SPECIAL_OFFSET + 5;

    /**
     * @code 1206
     * @codeMessage Customer are not a club member.
     * @codeResponse {
     *      "message": "Customer are not a club member.",
     *      "code": 1206,
     *      "success": false
     * }
     * @codeDescription The reason could be that the user are't club member.
     */
    const CUSTOMER_NOT_IN_CLUB = self::SPECIAL_OFFSET + 6;

    /**
     * @code 1207
     * @codeMessage Payment signature is incorrect.
     * @codeResponse {
     *      "message": "Payment signature is incorrect.",
     *      "code": 1207,
     *      "success": false
     * }
     * @codeDescription The reason could be that the payment signature is incorrect.
     */
    const INCORRECT_SIGNATURE = self::SPECIAL_OFFSET + 7;
}