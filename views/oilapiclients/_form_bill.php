<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Oilapiclients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oilapiclients-form">

    <?php $form = ActiveForm::begin(); ?>

    <label class="label-class">Лимиты:</label>
    <p><?= Html::textInput('bill_limit', '0', ['class'=>'form-control']); ?></p>
    <?=Html::hiddenInput('clid', $model->id);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
