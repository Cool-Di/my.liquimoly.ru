<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\VebinarList */

$this->title                     = $model->title;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Список вебинаров', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="vebinar-list-view">

    <!--h1><?= Html::encode( $this->title ) ?></h1-->

    <p>
        <?= Html::a( 'Редактировать', [ 'update',
                                 'id' => $model->id ], [ 'class' => 'btn btn-primary' ] ) ?>
        <?= Html::a( 'Удалить', [ 'delete',
                                 'id' => $model->id ], [ 'class' => 'btn btn-danger',
                                                         'data' => [ 'confirm' => Yii::t('yii', 'Вы уверены что хотите удалить?'),
                                                                     'method' => 'post', ], ] ) ?>
    </p>

    <?= DetailView::widget( [ 'model' => $model,
                              'attributes' => [
                                                'year',
                                                [ 'attribute' => 'mouth',
                                                  'value' => Yii::$app->list->month_name[ $model->mouth ] ],
                                                'title',
                                                'date',
                                                'link:url',
                                                'video_link:url', ], ] ) ?>

</div>
