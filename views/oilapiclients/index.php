<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OilapiclientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oil API';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapiclients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить API', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            'userpkk',
            // 'token',
            'name',
            'host',
            // 'ip',
            'limit',
            // 'begin_time',
            // 'update_time',
            // 'update_css',
            // 'json_css:ntext',

        	[
        	'class' => 'yii\grid\ActionColumn',
	        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{bill}&nbsp;&nbsp;{delete}',
    	    'buttons' =>
            	[
                'bill' => function ($url, $model) {
                	return Html::a('<span class="glyphicon glyphicon-wrench"></span>', Url::to(['/oilapiclients/update_bill/', 'id' => $model->id]), [
                    	'title' => Yii::t('yii', 'Управление лимитами')
                    ]); },
	             ]
	        ]
        ],
    ]); ?>
</div>
