<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Oilapiclients */

$this->title = 'Добавить Oil API:';
$this->params['breadcrumbs'][] = ['label' => 'Oilapiclients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapiclients-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
