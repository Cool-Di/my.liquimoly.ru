<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.11.2017
 * Time: 11:44
 */

class LmHtmlParser
{
    private $config = null;
    private $request = null;
    private $profile = null;

    private $input = null;


    private $data = null;
    private $error = null;
    private $message = null;

    private $clientId = null;
    private $type = null;
    private $prefix = null;
    private $displayCfg = null;

    private $cfg_desc = [
        'make' => 'Марка',
        'model' => 'Модель',
        'type' => 'Тип',
        'yearrange' => 'Год выпуска',
    ];
    private $mainDiv = 'choice';


    public function __construct($config, $request, $data, $profile)
    {
        $this->config = $config;
        $this->request = $request;
        $this->input = $data;
        $this->profile = $profile;

        $this->type = $this->request['operation'];
        $this->prefix = $this->request['prefix'];
        $this->clientId = $this->request['clid'];

        $this->prepareData();

        $this->setDisplayConfig();
    }

    public function getError()
    {
        return $this->error;
    }

    private function setDisplayConfig()
    {
        $step = $this->config['request'][$this->type];
        $alias = (isset($step['alias'])) ? $step['alias'] : null;
        $type = (!is_null($alias)) ? $alias : $this->type;
        $this->displayCfg = [
            'type' => $type,
            'alt' => $this->config['request'][$type]['alt'],
            'step' => $this->prefix . $type,
            'prefix' => $this->prefix
        ];
    }

    private function prepareData()
    {
        if (is_null($this->input))
            return;

        foreach ($this->input as $key => $value) {
            $var = $key;
            $this->$var = $this->input[$key];
        }
    }

    public function getHtmlContent($filter)
    {
        $result = '';

        switch ($this->type) {
            case 'search' :
                {
                    $result .= (is_null($this->data)) ? $this->getSearchHtml() : $this->getListHtml($this->data, 10);
                    break;
                }
            case 'categories';
            case 'makes';
            case 'models';
            case 'types' :
                {
                    $result .= $this->getListHtml($this->data);
                    break;
                }
            case 'recommendations':
                {
                    $result .= $this->getDescriptionHtml($this->data, $filter);
                    break;
                }
        }

        return $result;
    }

    public function getErrorHtml($text = null)
    {
        $text = (!is_null($text)) ? $text . $this->error : $this->error;
        $tag = $this->displayCfg['prefix'] . 'error';

        $html = '<div data-error="' . $tag . '" class="' . $tag . '">';
        $html .= '<span>' . $text . '</span>';
        $html .= '</div>';
        return $html;
    }

    private function getMessageHtml()
    {
        $result = '';
        if (!empty($this->message)) {
            $tag = $this->displayCfg['prefix'] . 'message';
            $result .= '<pre class="' . $tag . '">' . $this->message . '</pre>';
        }
        return $result;
    }

    private function getSearchHtml()
    {
        $step = $this->displayCfg['prefix'] . $this->type;
        $alt = $this->config['request'][$this->type]['alt'];
        $tag = $this->displayCfg['prefix'] . $this->mainDiv;
        $findButton = $this->displayCfg['prefix'] . 'find';

        $html = '<div data-choice="' . $tag . '" class="' . $tag . '">';
        $html .= '<div data-block="' . $this->displayCfg['prefix'] . 'block" class="' . $this->displayCfg['prefix'] . 'ui_block"><div class="' . $this->displayCfg['prefix'] . 'ui_spinner"></div></div>';
        $html .= '<div data-step="' . $step . '">';
        $html .= '<label  class="' . $this->displayCfg['prefix'] . 'field_header"> ' . $alt . ':</label><div style="width: 84%; float:left;"><input class="' . $this->displayCfg['prefix'] . 'input_field" name="' . $step . '" value=""></div>';
        $html .= '<div style="width: 15%; float: right;"><button name="' . $findButton . '" style="width: 100%;" >НАЙТИ</button></div>';

        $html .= '<div style="width: 100%; clear: both;"></div></div>';

        $html .= '</div>';

        return $html;
    }

    private function getListHtml($data, $size = 1)
    {
        $cfg = $this->displayCfg;

        $listSize = ($size > 1) ? 'size="' . $size . '"' : '';

        $html = '<div data-step="' . $cfg['step'] . '">';
        if (!empty($this->data)) {

            $html .= '<label class="' . $cfg['prefix'] . 'field_header">' . $cfg['alt'] . ':<select class="' . $cfg['prefix'] . 'select_field" name="' . $cfg['step'] . '" title="' . $cfg['alt'] . '" ' . $listSize . '"><option>&nbsp;</option>';

            foreach ($data as $key => $value) {
                $html .= '<option id="' . $key . '">' . $value . '</option>';
            }

            $html .= '</select></label>';
        }

        $html .= $this->getMessageHtml();

        $html .= '</div>';

        return $html;
    }

    private function getDescriptionHtml($data, $filter = null)
    {
        $cfg = $this->displayCfg;
        $prf = $cfg['prefix'];

        $html = '<div data-step="' . $cfg['step'] . '">';

        if (!empty($this->data)) {

            // коротрое описание : марка модель тип год выпуска
            $html .= '<ul class="' . $prf . 'select_list">';
            foreach ($data['vehicle'] as $key => $value) {
                $html .= '<li><b>' . $this->cfg_desc[$key] . '</b>: ' . $value . '</li>';
            }
            $html .= '</ul>';

            // ссылка - свернуть всё
            $html .= '<div class="' . $prf . 'text_right"><span data-updown="updown"  class="' . $prf . 'a_link">Свернуть всё</span></div>';

            // перечисление компонентов
            $setProduct = false;
            $listComponent = '';
            $html .= '<div class="' . $prf . 'component_list">';
            if (isset($data['component'])) {
                foreach ($data['component'] as $key => $value) {

                    $component = '';
                    $component .= '<div data-component="component" class="' . $prf . 'component_element">
                <span class="' . $prf . 'component_header"><span data-arrow="arrow">&#9660;</span>&nbsp;' . $value['name'] . '</span>';

                    // описание компонента
                    $component .= '<div class="' . $prf . 'component_desc">';

                    $component .= '<ul class="' . $prf . 'component_property"><li class="' . $prf . 'step_header">Рабочий объём:</li></ul>';
                    $component .= '<ul class="' . $prf . 'component_property">';

                    if (isset($value['capacity'])) {
                        foreach ($value['capacity'] as $k => $val) {
                            $component .= '<li>' . $val . '</li>';
                        }
                    }

                    $component .= '</ul>';
                    $component .= '<div  class="' . $prf . 'clr"></div>';

                    // режимы интервалы + масла
                    $component .= '<div class="' . $prf . 'component_fragment">';

                    if (isset($value['use'])) {

                        foreach ($value['use'] as $k1 => $val1) {
                            $use = '';
                            // проверяем наличие продуктов по нашей базе
                            $setProduct = isset($val1['product']);

                            $use .= '<ul class="' . $prf . 'component_property"><li class="' . $prf . 'step_header">Режим:</li></ul>';
                            $use .= '<ul class="' . $prf . 'component_property"><li>' . $val1['name'] . '</li></ul>';

                            // интервал
                            $use .= '<ul class="' . $prf . 'component_property"><li class="' . $prf . 'step_header">Интервал:</li></ul>';
                            $use .= '<ul class="' . $prf . 'component_property">';
                            if (isset($val1['interval'])) {
                                foreach ($val1['interval'] as $k3 => $val3) {
                                    $use .= '<li>' . $val3 . '</li>';
                                }
                            }
                            $use .= '</ul>';

                            $use .= '<div class="' . $prf . 'clr"></div>';

                            // продукты
                            $use .= '<div class="' . $prf . 'component_product">';

                            $use .= '<ul  class="' . $prf . 'component_list">';

                            if (isset($val1['product']) && !empty($val1['product'])) {

                                foreach ($val1['product'] as $k3 => $val3) {

                                    if ($filter) {
                                        $setProduct = true;
                                        $use .= '<li data-productid="' . $val3['id'] . '">' . $val3['name'] . '</li>';

                                    } else {

                                        $setProduct = is_array($val1['product']);

                                        if ($setProduct) {

                                            $pk = $val3;

                                            if (!empty($pk['code'])) {

                                                $use .= '<div class="' . $prf . 'description_product" data-productid="' . $k3 . '">';
                                                //$photoPath = 'http://liquimoly.ru/catalogue_images/thumbs/' . $pk['photo'];
                                                $use .= '<a target="_blank" class="' . $prf . 'link_product" href="' . $pk['link'] . '"><img class="' . $prf . 'image_product" src="' . $pk['photo'] . '">';
                                                $use .= '' . $pk['name'] . '</a>';
                                                $use .= '<div>' . $pk['description'] . '</div>';
                                                $use .= '<div class="' . $prf . 'clr"></div>';
                                                $use .= '</div>';
                                            }
                                        }
                                    }

                                }
                            }

                            $use .= '</ul>';
                            $use .= '</div>';
                            $use .= '<div class="' . $prf . 'clr"></div>';

                            $component .= $setProduct ? $use : '';
                        }
                    }

                    $component .= '</div>';

                    $component .= '</div>';

                    $component .= '</div>';

                    $listComponent .= $setProduct ? $component : '';
                }
            } else {
                $listComponent .= 'Данные не найдены!';
            }
            $html .= $listComponent;

            $html .= '</div>';

            $html .= '<div class="' . $prf . 'text_right"><span data-init="init" class="' . $prf . 'a_link">Вернуться к выбору</span></div>';

        }

        $html .= $this->getMessageHtml();

        $html .= '</div>';
        return $html;
    }

}