<?php
	use yii\helpers\Html;
	use yii\widgets\ListView;
	use yii\helpers\Url;
	use yii\data\ArrayDataProvider;
?>
<div class="meropriyatie">
	<h2><?=$model['name'];?></h2>
	<p><?=$model['desc_txt'];?></p>
	<?if(!empty($model['banner'])){?><img class="banner" src="/uploads/meropriyatiya/<?=$model['banner']?>" border="0"><?}?>
	<?if(!empty($model['dokladyall'])){?>
	<br /><br />
	<p><b>Доклады:</b></p>
    <ul>
    	<?
    		foreach ($model['dokladyall'] as $doklad){
	    ?>
			<li><a href="<?=$doklad['f_path']?>"><?=$doklad['f_name'];?></a></li>
		<?
    		}
    	?>
    </ul>
    <?}?>
    <p>
    <?
    if (isset($model['gallery'])){
    ?>
	<p><b>Фотографии:</b></p>
    <?
    foreach ($model['gallery'] as $g){?>
  		<div class="img_g">
  			<p><a target="_blank" href="/albums/<?=$g['url'];?>"><img src="<?=$g['src'];?>" border="0"></a></p>
	  		<p><b><?=$g['name'];?></b></p>
	  	</div>
    <?}}?>
    </p>
</div>