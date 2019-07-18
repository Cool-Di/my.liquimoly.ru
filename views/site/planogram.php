<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\web\View;

$this->title = 'Планограмма';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', [ 'depends' => 'yii\web\JqueryAsset' ] );
$this->registerJsFile('@web/js/planogram.js', ['depends' => 'yii\web\JqueryAsset', 'position'=>View::POS_END] );
$this->registerCssFile('@web/css/planogram.css');
?>
<div class="site-planogram">
    <h1><?= Html::encode($this->title) ?></h1>
    <div id="my_canvas">
        <div class="stelaj">
            <div style="border-top: 0;" id="p_top" class="polka">
                <div style="top: 20px; left: 10px;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>
                <div style="top: 20px; left: 80px;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>
                <div style="top: 20px; left: 160px;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>
                <div style="top: 20px; left: 240px;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>
            </div>
            <div class="polka">
                <div class="item"><img src="/images/planogram/33981863-a302-11da-babc-505054503030.png" height="100" border="0"></div>
                <div style="top: 0; left: 120px;" class="item"><img src="/images/planogram/33981863-a302-11da-babc-505054503030.png" height="100" border="0"></div>
            </div>
            <div class="polka">
                <div style="top: 40px; left: 10px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 60px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 120px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
            </div>
            <div class="polka">
                <div style="top: 40px; left: 10px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 40px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 80px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 120px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 160px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 40px; left: 200px;" class="item"><img src="/images/planogram/bb9e10a8-655e-11da-acfb-0040f46c7325.png" height="60" border="0"></div>
                <div style="top: 20px; left: 230px;" class="item"><img src="/images/planogram/135b9c3b-9a7a-11e5-83f7-0015175582ed.png" height="80" border="0"></div>
            </div>
            <div class="polka">&nbsp;</div>
            <div style="border-top: 5px #ECE8E8 solid;"></div>
        </div>
    </div>

    <button id="add_item">Добавить товар</button>
</div>
