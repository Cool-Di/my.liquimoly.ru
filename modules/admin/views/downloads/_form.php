<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\DownloadsHashTag;

/* @var $this yii\web\View */
/* @var $model app\models\Downloads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="downloads-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'f_patch')->label('Файл')->fileInput() ?>

    <?= $form->field($model, 'f_img')->label('Превью')->fileInput() ?>

    <?= $form->field($model, 'short_desc')->textInput(['maxlength' => true]) ?>

	<?
		if (sizeof($model->hashtag_ids) > 0)
		foreach ($model->hashtag_ids as $h_id){
			$selectedValues[$h_id['tag_id']] = ['selected ' => 'selected'];
		}
	?>
    <?= $form->field($model, 'hashtag_ids')->label('Теги')->dropDownList(ArrayHelper::map(DownloadsHashTag::find()->all(),'id','name'),['options' => $selectedValues, 'multiple' => 'true']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
