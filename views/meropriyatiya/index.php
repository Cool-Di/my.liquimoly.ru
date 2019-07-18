<?php
	use yii\helpers\Html;
	use yii\widgets\ListView;
	use yii\helpers\Url;
?>
<style>
	.list-wrapper div[data-key], .img_g {
		display: inline-block;
		vertical-align: top;
	}
	.banner {
		width: 100%;
		max-width: 500px;
		height: auto;
	}
</style>

<div class="row">
    <div class="col-md-12">
		<div class="box">
	        <div class="box-body">
<?= ListView::widget([
	'dataProvider' => $meropriyatiya,
	'itemView' => '_list_m',
	'options' => [
       'tag' => 'div',
       'class' => 'list-wrapper',
       'id' => 'list-wrapper',
	],
	'layout' => '{items}<div style="clear:both;"></div>{pager}',
]);?>
			</div>
		</div>
	</div>
</div>
