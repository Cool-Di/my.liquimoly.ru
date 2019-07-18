<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */

$this->title = 'Текущие акции';
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="row">
   	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?=$model->title;?></h3>
			</div><!-- /.box-header -->
		<div class="box-body">
			<div class="text">
               <p><?=$model->full_desc;?></p>
               <p><img src="/uploads/<?=$model->img?>" width="200"></p>
               <p><?=Html::a('&larr; Обратно', Url::previous());?></p>
			</div>
		</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col -->
</div><!-- /.row -->