<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.11.2017
 * Time: 13:45
 */


class LmApp
{
    private $request = null;
    private $requestType = '';
    private $response = null;
    private $error = null;

    private $config = null;

    private $fileCfg = __DIR__ . DIRECTORY_SEPARATOR . 'cfg.php';

    public function __construct()
    {
        try {

            LmUtil::applyConfiguration($this->config, $this->fileCfg);

            $this->applyRequest(LmRouter::getPostDataFromJsonKey($this->requestType));

            LmAuth::getInstance()->authClient($this->getRequestClientID())
            or LmException::createLmException(LmAuth::ERROR_AUTH, LmAuth::getInstance()->getError());

            $this->addDataResponse();

        } catch (LmException $e) {

            $this->error = $e->getMessage();
            $this->response = LmRouter::getErrorResponse($this->error, 'json');

        } finally {

            LmRouter::sendResponse($this->response);

        }
    }

    private function applyRequest($data)
    {
        try {
            $result = false;
            $request = null;

            if (!empty($data)) {

                if (!$this->checkMultiRequest($data)) {
                    $request[] = $data;
                } else {
                    $request = $data;
                }

                foreach ($request as $key => $value) {

                    if (!empty($value)) {

                        if (count($request[$key]) < count($this->config['json']['data'])) {

                            (isset($request[$key]['format'])) or $request[$key]['format'] = 'json';
                            (isset($request[$key]['prefix'])) or $request[$key]['prefix'] = '';
                        }

                        foreach ($value as $k => $v) {

                            if (in_array($k, $this->config['json']['data'])) {
                                $request[$key][$k] = $v;
                            } else {
                                unset($request[$key][$k]);
                            }
                        }

                        $result = count($request[$key]) === count($this->config['json']['data']);

                        $result = $result && $this->validateRequestOperation($request[$key]['operation']);

                    }
                }
            }
            $result or LmException::createLmException('Неверные параметры запроса', $data, LmException::TYPE_ARRAY);


            $this->request = $request;

        } catch (LmException $e) {
            $e->registerException();
        }
    }

    private function validateRequestOperation($operation)
    {

        return array_key_exists($operation, $this->config['request']);

    }

    private function addDataResponse()
    {
        switch ($this->requestType) {
            case 'data' :

                for ($i = 0, $count = count($this->request); $i < $count; $i++) {
                    $this->response[] = $this->getOilApi($this->request[$i]);

                    if (!empty($this->response[$i]['error'])) {
                        break;
                    }
                }
                $this->response = LmUtil::encodeJsonData($this->response);

                break;

            case 'cross' :

                $sc = new LmSoapClient($this->request[0], $this->config);
                $json = $sc->getData();
                if (LmUtil::decodeJsonData($json, $this->response[])) {
                    $this->response = LmUtil::encodeJsonData($this->response);
                }

                break;
        }
    }

    private function getOilApi($request)
    {
        $oa = new LmOilApi($this->config, $request, LmAuth::getInstance()->getProfile());
        return $oa->getResponse();
    }

    private function getRequestClientID()
    {
        return $this->checkMultiRequest($this->request) ?
            LmUtil::getVars($this->request[0], 'clid') : LmUtil::getVars($this->request, 'clid');
    }

    private function checkMultiRequest($request)
    {
        return is_array($request[array_keys($request)[0]]);
    }


}