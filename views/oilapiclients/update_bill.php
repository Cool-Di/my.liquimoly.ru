<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Oilapiclients */

$this->title = 'Управление лимитами: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Oilapiclients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'управление лимитами';
?>

<div class="oilapiclients-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form_bill', [
        'model' => $model,
    ]) ?>
</div>
