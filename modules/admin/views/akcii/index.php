<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AkciiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление акциями';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="akcii-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить акцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?
		$dataProvider->pagination->pageSize = 10;
     ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'akcii_id',
            'akcii_time',
            [
                'attribute' => 'akcii_img',
                'format' => 'raw',
                'value' => function($model){return Html::img('@web/uploads/akcii/' . $model->akcii_img, ['style'=>'max-width:150px'] );}
            ],
            'akcii_name',
            'akcii_deistvie',
            [
                    'attribute' => 'akcii_type',
                    'format' => 'text',
                    'value' => function ($model) {return Yii::$app->list->akcii_type[ $model->akcii_type ];}
            ],
            [
                    'attribute' => 'akcii_showme',
                    'value' => function ($model){return Yii::$app->list->showme[$model->akcii_showme];}
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        //'rowOptions' => function($model){if(1 == $model->akcii_type){return ['style'=>'background-color:#eff0f1'];}},
        'layout' => '{items}<div style="clear:both;"></div>{pager}',
    ]); ?>
</div>
