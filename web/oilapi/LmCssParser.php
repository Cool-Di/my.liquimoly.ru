<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.11.2017
 * Time: 14:34
 */

class LmCssParser
{
    const
        REFRESH_CACHE_DATA_CSS = 30,
        CACHE_KEY_NAME = 'css';

    private $styleFile = 'css.json';

    private $request = null;
    private $profile = null;
    private $css = null;

    public function __construct($request, $profile)
    {
        $this->request = $request;
        $this->profile = $profile;
        $this->prepareCss();
    }

    private function prepareCss()
    {
        $data = null;

        $clientId = LmUtil::getVars($this->request, 'clid');

        $key = LmUtil::getKey([self::CACHE_KEY_NAME, $clientId]);

        if (!LmRouter::checkHostSource()) {
            $data = LmMemcache::getInstance()->getMemcache($key);
            $data = ($data === false) ? null : $data;
        }

        if (is_null($data)) {

            $css = LmDb::getInstance()->getCssClient($clientId);

            $json = LmUtil::encodeJsonData($this->convertCssArrayToJson($css));

            if (is_null($css) || (empty($css))) {
                $json = $this->getCssFromFile($this->styleFile);
            }

            if (LmUtil::decodeJsonData($json, $data)) {

                $cache = LmMemcache::getInstance();
                $time = LmUtil::getMinutes(self::REFRESH_CACHE_DATA_CSS);
                if (!$cache->replaceMemcache($key, $data, $time)) {
                    $cache->setMemcache($key, $data, $time);
                }

            }

        }

        $this->css = $data;
    }

    public function parseStyleHtml($html)
    {
        try {

            $mappingCss = $this->convertClassToStyle($this->css, LmUtil::getVars($this->request, 'prefix'));

            if (!empty($mappingCss)) {
                $html = str_replace(array_keys($mappingCss), array_values($mappingCss), $html);
            }

        } finally {

            return $html;

        }
    }

    private function convertClassToStyle($css, $prefix)
    {
        $result = null;

        if (!is_null($css)) {

            foreach ($css as $key => $value) {
                $k = 'class="' . $prefix . $key . '"';
                $v = '';
                foreach ($value as $prop => $val) {
                    $v .= $prop . ': ' . $val . '; ';
                }
                $v = 'style="' . rtrim($v) . '"';
                $result[$k] = $v;
            }
        }

        return $result;
    }

    private function getCssFromFile($file)
    {
        $result = false;
        if (file_exists($file)) {
            $result = file_get_contents($file);
        }
        return ($result !== false) ? $result : null;
    }

    private function convertCssArrayToJson($data)
    {
        $result = null;

        if (!is_null($data)) {

            foreach ($data as $k => $v) {

                foreach ($v as $value) {
                    list($prop, $val) = explode(': ', $value);
                    $result[$k][$prop] = $val;
                }
            }
        }

        return $result;
    }

}