<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrezentaciiCatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории презентаций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prezentacii-cat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{items}<div style="clear:both;"></div>{pager}',
    ]); ?>
</div>
