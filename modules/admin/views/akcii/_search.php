<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AkciiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="akcii-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'akcii_id') ?>

    <?= $form->field($model, 'akcii_time') ?>

    <?= $form->field($model, 'akcii_name') ?>

    <?= $form->field($model, 'akcii_deistvie') ?>

    <?= $form->field($model, 'akcii_short_desc') ?>

    <?php // echo $form->field($model, 'akcii_desc') ?>

    <?php // echo $form->field($model, 'akcii_img') ?>

    <?php // echo $form->field($model, 'akcii_type') ?>

    <?php // echo $form->field($model, 'akcii_showme') ?>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
