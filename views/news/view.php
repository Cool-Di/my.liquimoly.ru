<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->news_name;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=$model->news_name;?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="text">
                    <p><?=$model->news_desc;?></p>
                    <p><?=Html::a('&larr; Обратно', Url::previous());?></p>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->