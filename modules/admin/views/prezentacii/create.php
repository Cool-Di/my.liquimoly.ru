<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Prezentacii */

$this->title = 'Добавить презентацию';
$this->params['breadcrumbs'][] = ['label' => 'Prezentaciis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prezentacii-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
