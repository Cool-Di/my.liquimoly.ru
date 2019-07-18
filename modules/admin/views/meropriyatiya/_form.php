<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model app\models\Meropriyatiya */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.upload_prev {
	max-width: 80px;
	max-height: 80px;
}
.f_upload {
	border: 1px #E8E8E8 solid;
	padding: 10px;
	margin-bottom: 10px;
}
.new div {
	float: left;
}
<?if (!isset($model->files_array)){?>
#myTable {
	display: none;
}
<?}?>
#myTable {
	margin-bottom: 15px;
}
#myTable td {
	padding: 3px;
	border: 1px solid #808080;
}
</style>

<div class="meropriyatiya-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'banner')->fileInput() ?>

    <?= $form->field($model, 'images_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_txt')->textarea(['rows' => 6]) ?>

	<?/*?>
	<div class="f_upload">
	<b>Загрузить файлы презентации:</b><br />
	<?= FileUploadUI::widget([
	    'model' => $model,
	    'attribute' => 'image',
	    'url' => ['meropriyatiya/imageupload'],
	    'gallery' => false,
    	'fieldOptions' => [
            'accept' => '*'
	    ],
	    'clientOptions' => [
            'maxFileSize' => 20000000
	    ],
	    'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
            'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
    	],
	]);
	?>
    </div>

	<?
	if (empty($model->form_id))
		$f_id = md5('f_id_'.(rand(0,999999)*10));
	else $f_id = $model->form_id;
	?>

	<input name="Meropriyatiya[form_id]" type="hidden" value="<?=$f_id?>">
	<?*/?>

	<table width="600" id="myTable">
	  <tbody>
	  	<tr>
	  		<td><b>Название презентации</b></td>
	  		<td><b>Имя файла</b></td>
	  		<td><b>Действия</b></td>
	  	</tr>
<?
		if (sizeof($model->files_array) > 0)
			foreach ($model->files_array as $f){
?>
	  	<tr id="tr_<?=$f->id?>">
	  		<td><?=$f->f_name;?></td>
	  		<td><?=$f->f_path;?></td>
	  		<td><a onclick="delete_file(<?=$model->id;?>,<?=$f->id?>);" href="javascript://">удалить</a></td>
	  	</tr>
<?
			}
?>
	  </tbody>
	</table>

	<div class="pdf_files">
		<div class="new">
			<div>Название: <input id="f_name" name="f_name" type="input" value=""></div>
			<div><input name="f_data" type="file" value=""></div>
			<div class="b_upload"><a class="submit button" href="">Загрузить</a></div>
			<input id="m_id" name="m_id" type="hidden" value="<?=$model->id?>">
		</div>
	</div>

	<div style="clear:both;">

    <div style="padding-top: 15px;" class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?

		$this->registerJsFile('@web/js/send_file.js', ['depends' => 'yii\web\JqueryAsset']);
	/*
	if (!empty($output)){
		$js_str = "var j_srt = '$output'; var j_array = jQuery.parseJSON(j_srt); $('#meropriyatiya-image-fileupload').fileupload('add', {files: j_array['files']});";
		$this->registerJs($js_str, yii\web\View::POS_LOAD);
	}
	*/
	?>
</div>
