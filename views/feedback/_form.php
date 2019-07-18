<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="feedback-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email_phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'type')->dropDownList([
	    	'manager' => 'персональный менеджер',
	    	'tech_support' => 'тех. отдел',
	    	'advertising' => 'отдел рекламы',
	    	'lawyer' => 'юр. отдел',
	    	'accountant' => 'бухгалтерия',
    		'directing' => 'руководство'
    	]); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Отправить' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>