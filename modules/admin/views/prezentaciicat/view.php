<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrezentaciiCat */

$this->title = 'Категория: ' . $model->name;

$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Prezentacii Cats',
                                     'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="prezentacii-cat-view">

    <h1><?= Html::encode( $this->title ) ?></h1>

    <p>
        <?= Html::a( 'Обновить', [ 'update',
                                   'id' => $model->id ], [ 'class' => 'btn btn-primary' ] ) ?>
        <?= Html::a( 'Удалить', [ 'delete',
                                  'id' => $model->id ], [ 'class' => 'btn btn-danger',
                                                          'data' => [ 'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                                      'method' => 'post', ], ] ) ?>
    </p>

    <?= DetailView::widget( [ 'model' => $model,
                              'attributes' => [ 'id',
                                                'name', ], ] ) ?>

</div>
