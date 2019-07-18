<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PrezentaciiCat;

/* @var $this yii\web\View */
/* @var $model app\models\Prezentacii */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prezentacii-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'img_file')->fileInput() ?>
    <?= $form->field($model, 'prezent_file')->fileInput() ?>
    <?= $form->field($model, 'cat_id')->dropDownList(ArrayHelper::map(PrezentaciiCat::find()->all(),'id','name'),['prompt'=>'Выберите категорию']); ?>
    <?= $form->field($model, 'show_yn')->dropDownList(Yii::$app->list->showme); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
