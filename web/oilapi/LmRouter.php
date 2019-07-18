<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.11.2017
 * Time: 13:16
 */


class LmRouter
{
    const
        SOAP_CLIENT_SERVER_NAME = 'my.liquimoly.ru',
        SOAP_CLIENT_HOST_NAME = 'http://my.liquimoly.ru',
        URL_SERVICE = 'http://my.liquimoly.ru/oilapi/lmoilapiajax.php';

    public function __construct()
    {

    }

    public static function checkServiceSource()
    {
        return ($_SERVER['SERVER_NAME'] === self::SOAP_CLIENT_SERVER_NAME);
    }

    public static function checkHostSource()
    {
        return (self::getHost() === self::SOAP_CLIENT_HOST_NAME);
    }

    public static function getPostDataFromJsonKey(&$type)
    {
        $result = null;

        if (isset($_POST['data'])) {
            $type = 'data';
        }

        if (isset($_POST['cross'])) {
            $type = 'cross';
        }

        empty($type) or LmUtil::decodeJsonData($_POST[$type], $result);

        return $result;
    }

    public static function sendCrossCurl($url, $post)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = @curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function getHost()
    {
        return isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] :
            (isset($_SERVER['HTTP_REFERER']) ? rtrim($_SERVER['HTTP_REFERER'], '/') : '');
    }

    public static function getRealIp()
    {
        return (isset($_SERVER['HTTP_X_REAL_IP'])) ? $_SERVER['HTTP_X_REAL_IP'] : '';
    }

    public static function sendResponse($data)
    {

        header('Access-Control-Allow-Origin:' . LmRouter::getHost());

        exit($data);
    }

    public static function getErrorResponse($error, $format = 'array')
    {
        $result = ['data' => '', 'message' => '', 'error' => $error];
        if ($format === 'json'){
            $result = LmUtil::encodeJsonData([$result]);
        }

        return $result;
    }
}