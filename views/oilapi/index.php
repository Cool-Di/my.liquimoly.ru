<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OilapiclientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'API подбор масла';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapiclients-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'host',
            'limit',
        	[
        	'class' => 'yii\grid\ActionColumn',
	        'template' => '{bill}&nbsp;&nbsp;{import}',
    	    'buttons' =>
            	[
                'bill' => function ($url, $model) {
	                	return Html::a('<span class="glyphicon glyphicon-wrench"></span>', Url::to(['/oilapi/settings/', 'id' => $model->id]), [
                    		'title' => Yii::t('yii', 'Настройка витрины')
                    	]);
                	},
                'import' => function ($url, $model) {
	                	return Html::a('<span class="glyphicon glyphicon-download-alt"></span>', Url::to(['/oilapi/import/', 'id' => $model->id]), [
                    		'title' => Yii::t('yii', 'Загрузка остатков')
                    	]);
                	},
				]
	        ]
        ],
    ]); ?>
</div>
