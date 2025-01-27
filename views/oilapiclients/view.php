<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Oilapiclients */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Oilapiclients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapiclients-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'token',
            'name',
            'host',
            'ip',
            'limit',
            'begin_time',
            'update_time',
        ],
    ]) ?>

</div>
