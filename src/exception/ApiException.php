<?php
namespace app\src\exception;

class ApiException extends \Exception
{
    // error codes
    const BASE_OFFSET = 1000;

    const GENERAL_OFFSET = self::BASE_OFFSET + 100;
    // general error response
    const MODEL_NOT_FOUND = self::GENERAL_OFFSET + 1;
    const REQUIRED_PARAMS_NOT_FOUND = self::GENERAL_OFFSET + 2;
    const PARAM_MUST_BE_UNIQUE = self::GENERAL_OFFSET + 3;
    const RELATION_ALREADY_EXIST = self::GENERAL_OFFSET + 4;
    const INVALID_ARGUMENT = self::GENERAL_OFFSET + 5;

    const SPECIAL_OFFSET = self::BASE_OFFSET + 200;

    // special error response
    const EVENT_CAPACITY_LIMIT = self::SPECIAL_OFFSET + 1;
    const USER_EVENT_SUBSCRIBE_EXIST = self::SPECIAL_OFFSET + 2;
    const IDENTITY_OR_PASSWORD_INCORRECT = self::SPECIAL_OFFSET + 3;
    const ID_OR_TOKEN_INCORRECT = self::SPECIAL_OFFSET + 4;
    const FB_API_BAD_RESPONSE = self::SPECIAL_OFFSET + 5;
    const CUSTOMER_NOT_IN_CLUB = self::SPECIAL_OFFSET + 6;
    const INCORRECT_SIGNATURE = self::SPECIAL_OFFSET + 7;

    public static $messages = [
        self::MODEL_NOT_FOUND => 'Model {model} not found.',
        self::REQUIRED_PARAMS_NOT_FOUND => 'Required param {param} not found.',
        self::PARAM_MUST_BE_UNIQUE => 'Param {param} must be unique.',
        self::RELATION_ALREADY_EXIST => 'Relation {relation} already exist.',
        self::INVALID_ARGUMENT => 'Invalid argument {param}.',

        self::EVENT_CAPACITY_LIMIT => 'Capacity ({capacity}) limit reached.',
        self::USER_EVENT_SUBSCRIBE_EXIST => 'The subscription to the event is ready.',
        self::IDENTITY_OR_PASSWORD_INCORRECT => 'Identity or password incorrect.',
        self::ID_OR_TOKEN_INCORRECT => 'ID or token incorrect.',
        self::FB_API_BAD_RESPONSE => 'Facebook API bad response. Check the token and userId and try again.',
        self::CUSTOMER_NOT_IN_CLUB => 'Customer are not a club member.',
        self::INCORRECT_SIGNATURE => 'Payment signature is incorrect',
    ];

    public $message;
    public $code;

    public $data;

    public function __construct($code, $data = [], $message = null)
    {
        $this->code = $code;
        $this->data = $data;

        $message = $message ?? self::$messages[$code] ?? '';

        // bind data
        foreach ($data as $placeholder => $value) {
            $message = str_replace('{' . $placeholder . '}', $value, $message);
        }

        // replace unbinded values
        $this->message = \Yii::t('app', preg_replace('/\{[\w-]*\}/', '', $message));
    }

    public function isSuccess(): bool
    {
        return $this->code < 100;
    }
}