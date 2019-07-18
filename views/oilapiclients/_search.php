<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OilapiclientsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oilapiclients-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'token') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'host') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'limit') ?>

    <?php // echo $form->field($model, 'begin_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'update_css') ?>

    <?php // echo $form->field($model, 'json_css') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
