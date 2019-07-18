<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Oilapilinks */

$this->title = 'Create Oilapilinks';
$this->params['breadcrumbs'][] = ['label' => 'Oilapilinks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapilinks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
