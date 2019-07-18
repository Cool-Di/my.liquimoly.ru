<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Akcii */

$this->title = 'Редактировать акцию: ' . $model->akcii_id;
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->akcii_id, 'url' => ['view', 'id' => $model->akcii_id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="akcii-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
