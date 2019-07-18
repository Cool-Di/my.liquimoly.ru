<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.11.2017
 * Time: 16:20
 */

class LmUtil
{

    const
        ERROR_FIND_FILE = 'Файл не найден';

    public static function applyConfiguration(&$var, $file)
    {
        try {
            file_exists($file) or LmException::createLmException(self::ERROR_FIND_FILE, $file);
            $var = require $file;
        } catch (LmException $e) {
            $e->registerException();
        }
    }

    public static function validateJsonData($json, $pattern = '/[{].*[}]/')
    {
        json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $res = preg_match($pattern, $json, $var);
            $json = ($res) ? $var[0] : $json;
        }
        return $json;
    }

    public static function decodeJsonData($json, &$data)
    {
        $result = json_decode($json, true);
        $data = $result;
        return (!is_null($json) && !empty($json) && (json_last_error() === JSON_ERROR_NONE));
    }

    public static function encodeJsonData($data)
    {
        return (is_null($data)) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function getKey($array)
    {
        return md5(implode($array));
    }

    public static function getMinutes($second)
    {
        return $second * 60;
    }

    public static function getVars($array, $var = null)
    {

        return (is_null($var)) ? $array : ((isset($array[$var])) ? $array[$var] : null);
    }

    public static function intersectArrayProduct($a, $b)
    {

        $c = array_intersect_key($a, $b);
        foreach ($c as $k => $v) {
            if (!is_array($v)) {
                unset($c[$k]);
            }
        }
        return $c;
    }

}