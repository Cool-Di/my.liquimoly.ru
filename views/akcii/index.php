<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акции';
$this->params['breadcrumbs'][] = [ 'label' => $this->title, 'url' => [ 'index' ] ];

Url::remember();
?>
<style>
	.a_img {
		min-width: 175px;
		text-align: left;
		float: left;
	}
    .a_desc {
		max-width: 475px;
		float: left;
    }
    .d_action {
		float: left;
		max-width: 700px;
		margin-bottom: 25px;
		margin-right: 25px;
    }
    @media screen and (max-width: 641px) {
    	.a_desc, .a_img {
        	text-align: center;
         }
        .a_img {
         	width: 100%;
		}
 	}
</style>
<div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Текущие акции</h3><!-- · <img src="/images/file_pdf.png" width="24"> <a href="/uploads/akcii/_calendar_.pdf" target="_blank">Календарь акций</a-->
            </div><!-- /.box-header -->
            <div class="box-body">
            <?
	            $dataProvider->pagination->pageSize = 10;
            ?>
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'itemView' => '_list',
				'options' => [
			        'tag' => 'div',
			        'class' => 'list-wrapper',
			        'id' => 'list-wrapper',
			    ],
			    'layout' => '{items}<div style="clear:both;"></div>{pager}',
			]);?>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div><!-- /.col -->
</div>