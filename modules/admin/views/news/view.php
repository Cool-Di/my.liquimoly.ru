<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->news_name;
$this->params[ 'breadcrumbs' ][] = [
    'label' => 'Новости',
    'url' => [ 'index' ]
];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="news-view">
    <p>
        <?= Html::a( 'Редактировать', [ 'update', 'id' => $model->news_id ], [ 'class' => 'btn btn-primary' ] ) ?>
        <?= Html::a( 'Удалить', [ 'delete', 'id' => $model->news_id ], [ 'class' => 'btn btn-danger', 'data' => [ 'confirm' => 'Вы уверен в том что хотите удалить эту новость?', 'method' => 'post' ] ] ) ?>
    </p>

    <?= DetailView::widget( [
        'model' => $model,
        'attributes' => [
            'news_id',
            'news_time',
            'news_name',
            'news_short_desc:html',
            'news_desc:html',
            [
                'attribute' => 'news_img',
                'format' => 'raw',
                'value' => Html::img('@web/uploads/news/' . $model->news_img, ['style'=>'max-height:100px;max-width:100px'] )
            ],
            ['attribute' => 'news_showme',
             'value' => Yii::$app->list->showme[$model->news_showme]]
        ],
    ] ) ?>

</div>
