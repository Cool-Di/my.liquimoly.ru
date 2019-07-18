<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Akcii */

$this->title = 'Добавить акцию';
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="akcii-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
