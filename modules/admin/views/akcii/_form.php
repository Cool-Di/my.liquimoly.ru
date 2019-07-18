<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Akcii */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="akcii-form">
    <?php $form = ActiveForm::begin(); ?>
<?
    if ( $model->akcii_time == '0000-00-00 00:00:00' || empty( $model->akcii_time ) )
        {
        $model->akcii_time = date( 'd.m.Y H:i', time() );
        }
    else
        {
        $model->akcii_time = date( 'd.m.Y H:i', strtotime( $model->akcii_time ) );
        }
    echo $form->field( $model, 'akcii_time' )->widget( DateTimePicker::classname(), [ 'options' => [ 'placeholder' => 'Enter event time ...' ],
                                                                                      'pluginOptions' => [ 'autoclose' => true,
                                                                                                           'format' => 'dd.mm.yyyy hh:ii' ] ] );
?>
    <?= $form->field( $model, 'akcii_name' )->textInput( [ 'maxlength' => true ] ) ?>
    <?= $form->field( $model, 'akcii_deistvie' )->textInput( [ 'maxlength' => true ] ) ?>
    <?= $form->field( $model, 'akcii_short_desc' )->widget( TinyMce::className(), [ 'options' => [ 'rows' => 6 ],
                                                                                    'language' => 'ru',
                                                                                    'clientOptions' => [ 'plugins' => [ "advlist autolink lists image link charmap print preview anchor jbimages",
                                                                                                                        "searchreplace visualblocks code fullscreen",
                                                                                                                        "insertdatetime media table contextmenu paste" ],
                                                                                                         'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages" ] ] ); ?>
    <?= $form->field( $model, 'akcii_desc' )->widget( TinyMce::className(), [ 'options' => [ 'rows' => 20 ],
                                                                              'language' => 'ru',
                                                                              'clientOptions' => [ 'plugins' => [ "advlist autolink lists image link charmap print preview anchor jbimages",
                                                                                                                  "searchreplace visualblocks code fullscreen",
                                                                                                                  "insertdatetime media table contextmenu paste" ],
                                                                                                   'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages" ] ] ); ?>
    <?= $form->field( $model, 'img_file' )->fileInput() ?>
    <?= $form->field( $model, 'akcii_type' )->dropDownList( Yii::$app->list->akcii_type ) ?>
    <?= $form->field( $model, 'akcii_showme' )->dropDownList( Yii::$app->list->showme ) ?>
    <div class="form-group">
        <?= Html::submitButton( $model->isNewRecord ? 'Добавить' : 'Сохранить', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ] ) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
