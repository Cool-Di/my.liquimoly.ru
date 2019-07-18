<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.11.2017
 * Time: 11:40
 */

class LmException extends Exception
{
    const
        TYPE_STRING = 0,
        TYPE_ARRAY = 1,
        TYPE_JSON = 2,
        TYPE_HTML = 3;

    const

        ERROR_SOAP_CLIENT_ERROR = 'SoapClient Error',
        ERROR_SOAP_REQUEST = 'SoapClient request',
        ERROR_SOAP_RESPONSE = 'SoapClient response',
        ERROR_SOAP_BASE = 'Send request to web-service base type',
        ERROR_RESULTSOAP_NULL = 'Result data soap request is null',

        ERROR_LOAD_XML = 'XML converting to DOM',
        ERROR_PARSING_DOM = 'Parsing DOM problem',
        ERROR_PARSING_CSS = 'Parsing CSS problem',

        ERROR_JSON_DECODE = 'Problem decode JSON data';

    private $debug = true;

    public function __construct($message = "", $code = 0, Throwable $previous = null, $data = null, $type = 0)
    {
        parent::__construct($message, $code, $previous);

        if (($type === self::TYPE_JSON) || ($type === self::TYPE_ARRAY))
            $errorData = LmUtil::encodeJsonData($data);
        else
            $errorData = $data;

        $this->message = ($this->debug) ? $message . ((!empty($errorData)) ? ' : ' . $errorData : '') : $message;
    }


    static function createLmException($message, $data = null, $type = 0)
    {
        throw new LmException($message, 0, null, $data, $type);
    }

    public function registerException($obj = null, $ignore = false)
    {
        if (!$ignore) {
            throw $this;
        }
    }

}