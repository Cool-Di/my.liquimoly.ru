<?php

class LmOilApi
{
    const
        ERROR_UNKNOWN_FORMAT_TYPE = 'Неизвестный формат данных',
        ERROR_GET_DATA = 'Данные не получены';

    private $config = null;

    private $profile = null;

    private $format = null;

    private $data = null;

    private $response = null;
    private $request = null;

    private $statusCache = true;

    public function __construct($config, $request, $profile)
    {
        try {

            $this->config = $config;

            $this->request = $request;

            $this->setProfile($profile);

            $this->setFormat();

            $this->response = $this->getData();

        } catch (LmException $e) {

            $this->response = LmRouter::getErrorResponse($e->getMessage());

        } catch (Exception $e) {

            $this->response = LmRouter::getErrorResponse('Сервис недоступен ' . $e->getMessage());

        }
    }


    private function setProfile($profile)
    {
        $this->profile = $profile;
    }

    private function setFormat($var = null)
    {
        $this->format = (is_null($var)) ? $this->request['format'] : $var;
    }

    private function getFormat()
    {
        return $this->format;
    }

    private function getData()
    {
        $result = null;

        try {

            $key = LmUtil::getKey(array_values($this->request));
            $result = LmMemcache::getInstance()->getMemcache($key);
            $result = ($result === false) ? null : $result;

            if (is_null($result)) {

                $json = null;
                $fromService = false;
                if ($this->isNeedData()) {

                    $json = $this->getDataDbFromRequest();

                    if (is_null($json)) {
                        $json = $this->getService();
                        $json = LmUtil::validateJsonData($json);
                        $fromService = true;
                    }

                    (LmUtil::decodeJsonData($json, $this->data)) or
                    LmException::createLmException(LmException::ERROR_JSON_DECODE, $json, LmException::TYPE_JSON);
                }

                $result = $this->data;

                $this->parseData();

                if (!$this->isDataError()) {

                    $result = $this->prepareData();

                    if ($this->isNeedleCache()) {

                        if ($fromService) {
                            $this->setDataDbFromRequest($json);
                        }

                        if (!LmMemcache::getInstance()->replaceMemcache($key, $result)) {
                            LmMemcache::getInstance()->setMemcache($key, $result);
                        }
                    }
                }
            }

            $this->applyCssStyle($result['data']);

        } catch (LmException $e) {

            $e->registerException();

        }

        return $result;
    }

    private function prepareData()
    {
        $result = null;

        try {
            switch ($this->getFormat()) {
                case 'html' :
                    {
                        $filter = !$this->isNeedleCache();

                        $widgetHtml = new LmHtmlParser($this->config, $this->request, $this->data, $this->profile);

                        $result['data'] = $widgetHtml->getHtmlContent($filter);
                        $result['error'] = isset($this->data['error']) ? $this->data['error'] : '';
                        $result['message'] = isset($this->data['message']) ? $this->data['message'] : '';

                        break;
                    }
                case 'json' :
                    {
                        $result = $this->data;
                        break;
                    }
                default :
                    {
                        LmException::createLmException(LmOilApi::ERROR_UNKNOWN_FORMAT_TYPE, $this->getFormat());
                    }
            }

        } catch (LmException $e) {

            $e->registerException();

        }

        return $result;
    }

    private function applyCssStyle(&$data)
    {
        switch ($this->getFormat()) {
            case 'html' :
                {
                    if (!empty($data)) {
                        $cssParser = new LmCssParser($this->request, $this->profile);
                        $data = $cssParser->parseStyleHtml($data);
                    }
                    break;
                }
        }

    }

    private function getService()
    {
        $result = null;
        if (!LmRouter::checkServiceSource()) {
            $result = LmRouter::sendCrossCurl(LmRouter::URL_SERVICE, ['cross' => json_encode($this->request)]);
            LmUtil::decodeJsonData($result, $data);
            $result = LmUtil::encodeJsonData($data[0]);

        } else {
            $soap = new LmSoapClient($this->request, $this->config);
            $result = $soap->getData();
        }
        return $result;
    }

    private function isDataError()
    {
        return (!empty($this->data['error']));
    }

    public function getResponse()
    {
        return $this->response;
    }

    private function isNeedData()
    {
        $rules[] = ($this->request['operation'] === 'search')
            && ($this->request['id'] === '')
            && ($this->request['text'] === '');

        $result = true;
        for ($i = 0; $i < count($rules); $i++) {
            if ($rules[$i]) {
                return false;
            }
        }

        return $result;
    }

    private function getDataDbFromRequest()
    {
        return LmDb::getInstance()->getRequestData($this->request);
    }

    private function setDataDbFromRequest($json)
    {
        LmDb::getInstance()->setRequestData($json, $this->request);
    }

    private function parseData()
    {
        $type = LmUtil::getVars($this->request, 'operation');
        $products = null;

        switch ($type) {
            case 'recommendations':
                $linkProducts = null;

                $products = $this->getCodeProductList($this->data['data']);

                $clientId = LmUtil::getVars($this->request, 'clid');

                if (!is_null($products)) {
                    $linkProducts = LmDb::getInstance()->getLinkProducts(array_keys($products), $clientId);

                    if (!is_null($linkProducts)) {
                        $codes = $this->addLinkToProduct($products, $linkProducts);

                        $dataProducts = LmDb::getInstance()->getDataProductsByVendorCode($codes);

                        $dataProducts = $this->orderArrayByKey($dataProducts, 'code', ['code', 'name', 'description', 'photo']);

                        $this->addDescriptionToProduct($products, $dataProducts);

                        $products = $this->updateCodeProductList($this->data['data'], $products);
                    }

                }

                if (is_null($products) || is_null($linkProducts)) {
                    $this->setStatusCacheResult(false);
                }

                break;
        }
        return $products;
    }

    private function getCodeProductList(&$data)
    {
        $result = null;

        if (isset($data['component'])) {

            foreach ($data['component'] as $i => $v) {

                foreach ($v['use'] as $key => $value) {

                    if (!empty($value['product'])) {

                        foreach ($value['product'] as $kk => $vv) {

                            $result[$kk] = $vv['name'];
                        }
                    }
                }
            }
        }

        return $result;
    }

    private function updateCodeProductList(&$data, $filter = null)
    {
        $result = null;

        if (isset($data['component'])) {
            foreach ($data['component'] as $i => $v) {
                foreach ($v['use'] as $key => $value) {

                    if (!empty($value['product'])) {
                        foreach ($value['product'] as $kk => $vv) {
                            if (!is_null($filter)) {

                                if (isset($filter[$kk]) && is_array($filter[$kk])) {

                                    $result[$kk] = $filter[$kk];
                                    $data['component'][$i]['use'][$key]['product'][$kk] = $filter[$kk];

                                } else {
                                    unset($data['component'][$i]['use'][$key]['product'][$kk]);
                                }

                            } else {
                                $result[$kk] = $vv['name'];
                            }
                        }
                        if (empty($data['component'][$i]['use'][$key]['product'])) {
                            unset($data['component'][$i]['use'][$key]['product']);
                        }

                    }

                }
            }
        }

        return $result;
    }

    private function addLinkToProduct(&$product, $link)
    {
        $codes = [];
        foreach ($link as $value) {
            $product[$value['g_code']] = ['code' => $value['r_code'], 'link' => $value['link']];
            array_push($codes, $value['r_code']);
        }
        return $codes;
    }

    private function addDescriptionToProduct(&$product, $data)
    {

        foreach ($product as $key => $value) {
            if (!empty($value) && is_array($value)) {
                if (array_key_exists($product[$key]['code'], $data)) {
                    $product[$key] = array_merge($product[$key], $data[$product[$key]['code']]);
                } else {
                    unset($product[$key]);
                }
            }
        }
    }

    private function orderArrayByKey($array, $k, $filter = null)
    {

        $result = null;

        foreach ($array as $key => $value) {

            if (array_key_exists($k, $value)) {
                if (!is_null($filter)) {
                    foreach ($filter as $v) {
                        $result[$value[$k]][$v] = $value[$v];
                    }
                } else {
                    $result[$value[$k]] = $value;
                }
            }
        }

        return $result;
    }

    private function setStatusCacheResult($status)
    {
        $this->statusCache = $status;
    }

    public function isNeedleCache()
    {
        return $this->statusCache;
    }
}





