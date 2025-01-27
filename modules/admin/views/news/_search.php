<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="news-search">
    <?php $form = ActiveForm::begin( [ 'action' => [ 'index' ], 'method' => 'get', ] ); ?>
    <?= $form->field( $model, 'news_id' ) ?>
    <?= $form->field( $model, 'news_time' ) ?>
    <?= $form->field( $model, 'news_name' ) ?>
    <?= $form->field( $model, 'news_short_desc' ) ?>
    <div class="form-group">
        <?= Html::submitButton( 'Поиск', [ 'class' => 'btn btn-primary' ] ) ?>
        <?= Html::resetButton( 'Сброс', [ 'class' => 'btn btn-default' ] ) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
