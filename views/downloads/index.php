<?php
	use yii\helpers\Html;
	use yii\widgets\ListView;
	use yii\helpers\Url;
?>
<style>
	.f_items {
		width: 200px;
		border: 1px solid #f4f4f4;
		padding: 10px;
	}
	.list-wrapper div[data-key] {
		display: inline-block;
		vertical-align: top;
	}
	.h_tag {
		margin-top: 10px;
	}
	.h_tag a {
		color: #808080;
	}
</style>
<div class="row">
<div class="col-md-12">
	<div class="box">
	<div class="box-body">

<p>
<?
	$hash_tag_array = [];
	foreach ($hash_tags_DataProvider->allModels as $ht){
		echo '<a href="">#'.$ht['name'].'</a>&nbsp;';
		$hash_tag_array[$ht['id']] = $ht['name'];
	}
?>
</p>
<?=ListView::widget([
	'dataProvider' => $all_files,
	'itemView' => '_list_d',
	'options' => [
       'tag' => 'div',
       'class' => 'list-wrapper',
       'id' => 'list-wrapper',
	],
    'viewParams' => array(
        'hash_tag_array' => $hash_tag_array
    ),
	'layout' => '{items}<div style="clear:both;"></div>{pager}',
]);?>
	</div><!-- /.box-body -->
	</div><!-- /.box -->
</div><!-- /.col -->
</div><!-- /.row -->