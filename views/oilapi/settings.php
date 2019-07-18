<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'API подбор масла';
$this->params['breadcrumbs'][] = ['label' => 'API подбор масла', 'url' => ['/oilapi']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Лимиты и токен</h1>
<p>Осталось запросов к API: <b><?=$api_data['limit'];?></b></p>
<p><b>Код для сайта:</b></p>
<p><textarea name="Name" rows=5 cols=60 wrap="on">
<div data-view="lm_widget" data-clid="<?=$api_data['token'];?>"></div>
<script type="text/javascript" src="http://my.liquimoly.ru/oilapi/lmloader.js"></script>
</textarea></p>
<p><b>Настройки стилей:</b></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	    'columns' => [
           ['class' => 'yii\grid\SerialColumn'],
           [
            'label' => 'Класс',
            'attribute' => 'class_name',
            ],
           [
            'label' => 'Свойство',
            'attribute' => 'property_name',
            ],
           [
            'label' => 'Значение',
            'attribute' => 'css_value',
            ],
        	[
        	'class' => 'yii\grid\ActionColumn',
	        'template' => '{edit}',
    	    'buttons' =>
            	[
                'edit' => function ($url, $model) use ($clid){                	return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/oilapi/settings_edit/', 'clid' => $clid, 'id' => $model['id']]), [
                    	'title' => Yii::t('yii', 'Редактировать свойство')
                    ]); },
	             ]
	        ]
     	]
    ]);
    ?>
<p><b>Пример витрины:</b></p>
<div style="width:100%; background: #FFFFFF; border: 1px #000000 solid; padding: 10px;">
<div data-view="lm_widget" data-clid="<?=$api_data['token'];?>"></div>
</div>
<script type="text/javascript" src="http://my.liquimoly.ru/oilapi/lmloader.js"></script>