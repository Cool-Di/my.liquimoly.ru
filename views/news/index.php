<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
Url::remember();

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['attribute' => 'news_img', 'format' => 'html', 'value' => function ($data) {
                            return Html::img(Yii::getAlias('@web') . '/uploads/news/' . $data->news_img, ['width' => '150px']);
                        }, 'contentOptions' => ['width' => '1%']],
                        ['attribute' => 'news_name', 'format' => 'raw', 'value' => function ($data) {
                            return "<p style='color:#999;margin:0;'>" . date('d-m-Y / H:i', strtotime($data->news_time)) . "</p>" . Html::a($data->news_name, Url::to(['view', 'id' => $data->news_id]), ['style' => 'font-size:16px;']) . "<p>{$data->news_short_desc}</p>";
                        }],
                    ],
                    'tableOptions' => ['class' => 'table'],
                    'layout' => '{items}<div style="clear:both;"></div>{pager}',
                    'showHeader' => false
                ]); ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>