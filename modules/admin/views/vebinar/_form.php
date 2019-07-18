<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VebinarList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vebinar-list-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'year')->dropDownList( array_combine( range(date('Y'),2017), range(date('Y'),2017) ) );?>
	<?= $form->field($model, 'mouth')->dropDownList( Yii::$app->list->month_name );?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'video_link')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
