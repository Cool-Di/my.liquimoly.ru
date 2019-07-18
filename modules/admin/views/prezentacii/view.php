<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prezentacii */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Prezentaciis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prezentacii-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
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
            'id',
            'title',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => Html::img('@web/uploads/prezentacii/' . $model->img, ['style'=>'max-height:100px;max-width:100px'] )
            ],
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => Html::a($model->file, '/uploads/prezentacii/' . $model->file, ['target'=>'_blank'])
            ],
            'category.name:text:Категория',
            [
                'attribute' => 'show_yn',
                'value' => Yii::$app->list->showme[$model->show_yn]
            ]
        ],
    ]) ?>

</div>
