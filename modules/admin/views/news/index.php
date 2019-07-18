<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Управление новостями';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="news-index">
    <p><?= Html::a( 'Добавить новость', [ 'create' ], [ 'class' => 'btn btn-success' ] ) ?></p>
    <?= GridView::widget( [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [ 'class' => 'yii\grid\SerialColumn' ],
            'news_id',
            'news_time',
            'news_name',
            [
                'label' => 'Краткое описание',
                'attribute' => 'news_short_desc',
                'format' => 'raw',
                'value' => function( $model ) { return strip_tags( $model->news_short_desc );},
            ],
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
        'layout' => '{items}<div style="clear:both;"></div>{pager}',
    ] ); ?>
</div>