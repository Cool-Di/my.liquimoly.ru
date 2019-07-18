<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = $model->akcii_name;
$this->params['breadcrumbs'][] = [ 'label' => 'Акции', 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
   	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?=$model->akcii_name;?></h3>
			</div><!-- /.box-header -->
		<div class="box-body">
			<div class="text">
               <p><?=$model->akcii_desc;?></p>
               <p><?=Html::a('&larr; Обратно', Url::previous());?></p>
			</div>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col -->
</div><!-- /.row -->