<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.11.2017
 * Time: 11:42
 */

class LmSoapClient
{
    const
        OK_RESPONSE = 1,

        ERROR_UNKNOWN_MESSAGE = 'Неизвестный код ошибки',
        ERROR_REQUEST_TYPE = 'Неизвестный тип операции',
        ERROR_REQUEST_VALIDATE = 'Ошибка параметров запроса';

    private $errorFileCfg = __DIR__ . DIRECTORY_SEPARATOR . 'error.php';

    private $request = null;

    private $config = null;

    private $errorSoap = null;

    private $type = null;

    private $send = null;

    private $data = null;

    private $xml = null;

    private $code = null;

    private $error = null;

    private $fileСache = false;

    public function __construct($request, $config)
    {
        $this->request = $request;
        $this->config = $config;

        LmUtil::applyConfiguration($this->errorSoap, $this->errorFileCfg);
    }

    public function getData()
    {
        try {

            $this->setTypeRequest();

            $this->validateRequest();

            $this->prepareSendRequest();

            if ($this->fileСache) {
                $this->xml = $this->readSoapResult();
            }

            if (is_null($this->xml)) {
                $this->sendSoapRequest();
            }

            $this->parseXmlData();

            $this->getCodeResponse();

            if (!$this->checkError()) {
                $this->parseDOMTree($this->data);
            }

        } catch (Exception $e) {

            $this->error = $e->getMessage();

        } finally {

            return $this->sendReturn();

        }
    }


    private function setTypeRequest()
    {
        $result = false;
        try {
            $operation = $this->request['operation'];

            if (!is_array($operation))
                $result = array_key_exists($operation, $this->config['request']);
            $result or LmException::createLmException(self::ERROR_REQUEST_TYPE, $operation);
            $this->type = $operation;

        } catch (LmException $e) {
            $e->registerException();
        }
        return $result;
    }

    private function validateRequest()
    {
        $result = false;
        try {
            $rp = $this->config['request'][$this->type]['request_param'];
            foreach ($rp as $key => $value) {
                $result = $result || (!empty($this->request[$value]));
            }
            $result = empty($rp) || $result;
            $result or LmException::createLmException(LmSoapClient::ERROR_REQUEST_VALIDATE, $this->request);

        } catch (LmException $e) {
            $e->registerException();
        }
        return $result;
    }

    private function prepareSendRequest()
    {
        $params = $this->config['request'][$this->type]['request_param'];

        foreach ($params as $key => $value) {
            if (array_key_exists($value, $this->request)) {
                $params[$key] = (!is_null($this->request[$value])) ? $this->request[$value] : '';
            } else {
                $params[$key] = '';
            }

        }
        $this->send = (!is_null($params)) ? array_merge($this->config['auth'], $params) : $this->config['auth'];
    }

    private function sendSoapRequest()
    {
        try {
            $client = new SoapClient($this->config['soap']['url'], $this->config['soap']['options']);

            $func = $this->config['request'][$this->type]['method'];
            $response = $client->$func($this->send);

            $func = $this->config['request'][$this->type]['method'] . 'Result';
            $result = $response->$func->any;

            !is_null($result) or LmException::createLmException(LmException::ERROR_RESULTSOAP_NULL, $func);

            $this->xml = $result;

            if ($this->fileСache) {
                $this->saveSoapResult($result);
            }

        } catch (LmException $e) {
            $e->registerException();
        } catch (SoapFault  $e) {
            LmException::createLmException(LmException::ERROR_SOAP_REQUEST, $e->getMessage());
        } catch (Exception $e) {
            LmException::createLmException(LmException::ERROR_SOAP_BASE, $e->getMessage());
        }
    }

    private function parseXmlData()
    {
        try {
            $xmlDoc = new DOMDocument();
            $result = $xmlDoc->loadXML($this->xml);
            $result or LmException::createLmException(LmException::ERROR_LOAD_XML, $this->xml);
            $this->data = $xmlDoc;
        } catch (LmException $e) {
            $e->registerException();
        } catch (Exception $e) {
            LmException::createLmException(LmException::ERROR_LOAD_XML, $this->xml);
        }
    }

    private function getCodeResponse()
    {
        $xpath = new DOMXPath($this->data);
        $this->code = (integer)$xpath->query("//status/resultcode")->item(0)->nodeValue;
    }

    private function parseDOMTree(DOMDocument $data)
    {
        $result = null;
        try {
            switch ($this->type) {
                case 'recommendations' :
                    {

                        $xpath = new DOMXPath($data);

                        $res = $xpath->query("vehicle")->item(0)->childNodes;
                        foreach ($res as $value) {
                            $result['vehicle'][$value->nodeName] = $value->getAttribute('name');
                        }

                        $res = $xpath->query("advice/salesitem/component");
                        if ($res->length === 0) {
                            $res = $xpath->query("advice/brandrange/component");
                        }

                        foreach ($res as $key => $value) {

                            $result['component'][$key]['name'] = $value->getAttribute('name') . ' ' . $value->getAttribute('code');
                            foreach ($xpath->query("capacities", $value)->item(0)->childNodes as $item) {
                                $result['component'][$key]['capacity'][] = $item->nodeValue;
                            }

                            foreach ($xpath->query("use", $value) as $k => $i) {

                                $result['component'][$key]['use'][$k]['name'] = $i->getAttribute('name');
                                foreach ($xpath->query("intervals/interval", $i) as $k1 => $i1) {
                                    $result['component'][$key]['use'][$k]['interval'][] = $i1->nodeValue;
                                }

                                foreach ($xpath->query("product", $i) as $val) {
                                    $pCode = trim($val->getAttribute('productcode'));
                                    if (!empty($pCode)) {
                                        //$num = $val->getAttribute('number');
                                        $num = $val->getAttribute('productcode');
                                        $result['component'][$key]['use'][$k]['product'][$pCode]['id'] = $val->getAttribute('id');
                                        $result['component'][$key]['use'][$k]['product'][$pCode]['name'] = $val->getAttribute('name');
                                        $result['component'][$key]['use'][$k]['product'][$pCode]['apporder'] = $val->getAttribute('apporder');
                                        $result['component'][$key]['use'][$k]['product'][$pCode]['productcode'] = trim($val->getAttribute('productcode'));
                                    }

                                }
                            }
                        }
                        break;
                    }
                default :
                    {
                        $step = $this->config['request'][$this->type];
                        $alias = (isset($step['alias'])) ? $step['alias'] : null;
                        $type = (!is_null($alias)) ? $alias : $this->type;
                        $type = ucfirst($type);
                        foreach ($this->data->getElementsByTagName($type) as $item) {
                            $result[$item->getAttribute('id')] = $item->getAttribute('result');
                        }
                    }
            }

            $this->data = $result;

        } catch (Exception $e) {
            LmException::createLmException(LmException::ERROR_PARSING_DOM, $e->getMessage());
        }
        return $result;
    }

    public function sendReturn()
    {
        $result['data'] = (is_null($this->data)) ? [] : $this->data;
        $result['message'] = (is_integer($this->code) && ($this->code != self::OK_RESPONSE)) ? $this->getErrorMessage($this->code) : '';
        $result['error'] = (is_string($this->error)) ? $this->error : '';

        return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function getErrorMessage($code)
    {
        $result = self::ERROR_UNKNOWN_MESSAGE . ' - ' . $code;;
        if (is_array($this->errorSoap)) {
            if (key_exists($code, $this->errorSoap)) {
                $result = $this->errorSoap[$code];
            }
        }
        return $result;
    }

    private function checkError()
    {
        return ($this->code !== self::OK_RESPONSE);
    }

    private function getFileNameCache()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $this->type . '_' . $this->request['id'] . $this->request['text'];
    }

    private function saveSoapResult($var)
    {

        $fileName = $this->getFileNameCache();
        if (!file_exists($fileName)) {
            file_put_contents($fileName, $var);
        }
    }

    private function readSoapResult()
    {
        $result = null;
        $fileName = $this->getFileNameCache();
        if (file_exists($fileName)) {
            $result = file_get_contents($fileName);
        }
        return $result;

    }
}