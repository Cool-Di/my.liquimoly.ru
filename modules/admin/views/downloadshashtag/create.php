<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DownloadsHashTag */

$this->title = 'Добавить хештег';
$this->params['breadcrumbs'][] = ['label' => 'Downloads Hash Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="downloads-hash-tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
