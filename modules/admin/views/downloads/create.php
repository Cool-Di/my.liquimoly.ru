<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Downloads */

$this->title = 'Создать загрузку';
$this->params['breadcrumbs'][] = ['label' => 'Загрузки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="downloads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
