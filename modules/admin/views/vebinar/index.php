<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VebinarlistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                     = 'Список вебинаров';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="vebinar-list-index">

    <!--h1><?= Html::encode( $this->title ) ?></h1-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a( 'Добавить вебинар', [ 'create' ], [ 'class' => 'btn btn-success' ] ) ?>
    </p>
    <?= GridView::widget( [ 'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [ [ 'class' => 'yii\grid\SerialColumn' ],
                                           'year',
                                           [ 'attribute' => 'mouth',
                                             'format' => 'raw',
                                             'value' => function( $data )
                                                 {
                                                 return Yii::$app->list->month_name[ $data->mouth ];
                                                 }, ],
                                           'title',
                                           'date',
                                           'link:url',
                                           'video_link:url',

                                           [ 'class' => 'yii\grid\ActionColumn' ], ],
        'layout' => '{items}<div style="clear:both;"></div>{pager}' ] ); ?>
</div>
