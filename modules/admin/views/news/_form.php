<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="news-form">
    <?php $form = ActiveForm::begin(); ?>
<?
    $model->news_time = $model->news_time == '0000-00-00 00:00:00' || empty( $model->news_time ) ? date( 'd.m.Y H:i', time() ) : date( 'd.m.Y H:i', strtotime( $model->news_time ) );
?>
    <?= $form->field( $model, 'news_time' )->widget( DateTimePicker::classname(), [ 'options' => [ 'placeholder' => 'Введите дату и время' ],
                                                                                    'pluginOptions' => [ 'autoclose' => true,
                                                                                                         'format' => 'dd.mm.yyyy hh:ii' ] ] ); ?>
    <?= $form->field( $model, 'news_name' )->textInput( [ 'maxlength' => true ] ) ?>
    <?= $form->field( $model, 'news_short_desc' )->widget( TinyMce::className(), [ 'options' => [ 'rows' => 6 ],
                                                                                   'language' => 'ru',
                                                                                   'clientOptions' => [ 'plugins' => [ "advlist autolink lists image link charmap print preview anchor jbimages",
                                                                                                                       "searchreplace visualblocks code fullscreen",
                                                                                                                       "insertdatetime media table contextmenu paste" ],
                                                                                                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages" ] ] ); ?>
    <?= $form->field( $model, 'news_desc' )->widget( TinyMce::className(), [ 'options' => [ 'rows' => 20 ],
                                                                             'language' => 'ru',
                                                                             'clientOptions' => [ 'plugins' => [ "advlist autolink lists image link charmap print preview anchor jbimages",
                                                                                                                 "searchreplace visualblocks code fullscreen",
                                                                                                                 "insertdatetime media table contextmenu paste" ],
                                                                                                  'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages" ] ] ); ?>
    <?= $form->field( $model, 'img_file' )->fileInput() ?>
    <?= $form->field( $model, 'news_showme' )->dropDownList( [ 1 => 'Да',
                                                               0 => 'Нет' ] ) ?>
    <div class="form-group">
        <?= Html::submitButton( $model->isNewRecord ? 'Добавить' : 'Сохранить', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ] ) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
