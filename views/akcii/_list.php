<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="d_action">
	<div class="a_img">
        <?=Html::img('/uploads/akcii/' . $model->akcii_img, ['style'=>'max-width:150px','border'=>0] )?>
	</div>
	<div class="a_desc">
	  <h3><?=Html::a($model->akcii_name, Url::to(['view', 'id' => $model->akcii_id]));?></h3>
	  <p><b>Период действия:</b> <?=$model->akcii_deistvie?><br>
	  <?=$model->akcii_short_desc?>
	  <p><?=Html::a('Подробности акции &rarr;', Url::to(['view', 'id' => $model->akcii_id]));?></p>
	</div>
</div>
<?if (($index+1)/2 == ceil(($index+1)/2)){?>
<div style="clear:both;"></div>
<?}?>
