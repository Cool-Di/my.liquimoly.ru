<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Akcii */

$this->title = $model->akcii_name;
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="akcii-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->akcii_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->akcii_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'akcii_id',
            'akcii_time',
            'akcii_name',
            'akcii_deistvie',
            'akcii_short_desc:html',
            'akcii_desc:html',
            [ 'attribute' => 'akcii_img', 'format' => 'raw', 'value' => Html::img('@web/uploads/akcii/' . $model->akcii_img, ['style'=>'max-width:150px'] ) ],
            [ 'attribute' => 'akcii_type', 'value' => Yii::$app->list->akcii_type[ $model->akcii_type ] ],
            [ 'attribute' => 'akcii_showme', 'value' => Yii::$app->list->showme[ $model->akcii_showme ] ]
        ],
    ]) ?>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->akcii_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->akcii_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
