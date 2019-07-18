<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Oilapilinks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oilapilinks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=Html::hiddenInput('Oilapilinks[id_client]', $id);?>

    <?= $form->field($model, 'r_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'работает', '0' => 'отключен']);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
