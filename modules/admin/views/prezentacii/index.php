<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrezentaciiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Презентации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prezentacii-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить презентацию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => function($model){return Html::img('@web/uploads/prezentacii/' . $model->img, ['style'=>'max-height:100px;max-width:100px'] );}
            ],
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function($model){return Html::a($model->file, '/uploads/prezentacii/' . $model->file, ['target'=>'_blank']);}
            ],
            'category.name:text:Категория',
            [
                'attribute' => 'show_yn',
                'value' => function($model){return Yii::$app->list->showme[$model->show_yn];}
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{items}<div style="clear:both;"></div>{pager}',
    ]); ?>
</div>
