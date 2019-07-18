<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.11.2017
 * Time: 9:03
 */
//var_dump($_GET);
if (!empty($_GET)) {
    $jc = new LmJsonClient($_GET);
    $jc->getData();
} else {
    echo 'Error: Not get parametr';
}


class LmJsonClient
{
    private $request = null;

    public function __construct($var)
    {
        $this->request['clid'] = (isset($var['clid']) ? $var['clid'] : '');
        $this->request['operation'] = (isset($var['operation']) ? $var['operation'] : '');
        $this->request['id'] = (isset($var['id']) ? $var['id'] : '');
        $this->request['text'] = (isset($var['text']) ? $var['text'] : '');
    }

    public function getData()
    {
        $pd[] = $this->request;
        $post['data'] = json_encode($pd);
        var_dump($post);
        $result = $this->sendCurl($post);
        //echo $result;
        $matches = null;
        preg_match('/\[\{".*"\}\]/', $result, $matches);
        echo nl2br("JSON RESPONSE: " . PHP_EOL . $matches[0] . PHP_EOL);
        $dump = str_replace($matches[0], "", $result);

        $result = json_decode($matches[0], true);
        echo nl2br(PHP_EOL . "JSON TO ARRAY DECODE: " . PHP_EOL);
        var_dump($result);

        echo nl2br("DUMP WARNING: " . $dump);

    }

    // curl - запрос междоменный к этому скрипту
    private function sendCurl($post)
    {

        $url = "http://oilapi.local/lmoilapiajax.php";
        //$url = "http://my.liquimoly.ru/oilapi/lmoilapiajax.php";
        $headers = ['Origin: '.'http://oilapi.local'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $xmlResponse = curl_exec($ch);

        curl_close($ch);

        return $xmlResponse;
    }

}