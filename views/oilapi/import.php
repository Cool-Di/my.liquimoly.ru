<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OilapilinksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Импорт';
$this->params['breadcrumbs'][] = ['label' => 'API подбор масла', 'url' => ['/oilapi']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapilinks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p><a href="<?=Url::toRoute('oilapi/importfile?id='.$id)?>">[Импортировать из XLS-файла]</a> (<a href="http://my.liquimoly.ru/uploads/import_sample.xlsx">пример файла</a>)</p>
    <p>
        <?= Html::a('Добавить ссылку', ['create?id='.$id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'r_code',
            'link',
			[
	           'attribute' => 'status',
	           'format' => 'raw',
     	       'value' => function ($model) {
     	       		return ($model->status == 1 ? 'работает' : 'отключен');
     	       },
     	       'filter' => [1 => 'работает', 0 => 'отключен']
			],
        	[
        	'class' => 'yii\grid\ActionColumn',
	        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
    	    'buttons' =>
            	[
		            'view' => function ($url, $model) {
		                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
		                            'title' => Yii::t('app', 'lead-view'),
		                ]);
		            },

		            'update' => function ($url, $model) {
		                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
		                            'title' => Yii::t('app', 'lead-update'),
		                ]);
		            },
		            'delete' => function ($url, $model) {
		                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
		                            'title' => Yii::t('app', 'lead-delete'),
		                ]);
		            }
				]
	        ]
        ],
    ]); ?>
</div>
