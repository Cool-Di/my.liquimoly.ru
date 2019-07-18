<?
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12">
        <div class="box" style="margin-bottom: 0;">
            <div class="box-header">
                <h3 class="box-title">Новости</h3>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['attribute' => 'news_time', 'format' => [ 'datetime' ], 'contentOptions'=>['style'=>'width: 1%;white-space: nowrap;'] ],
                        ['attribute' => 'news_name', 'format' => 'raw','value'=>function($data){return Html::a($data->news_name, Url::to(['news/view', 'id' => $data->news_id]));}],
                    ],
                    'tableOptions' => ['class'=>'table'],
                    'layout' => '{items}',
                    'showHeader' => false
                ]);?>
            </div>
        </div>
    </div>
</div>