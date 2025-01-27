<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Meropriyatiya */

$this->title = 'Update Meropriyatiya: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Meropriyatiyas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="meropriyatiya-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
