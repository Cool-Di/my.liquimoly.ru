<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PrezentaciiCat */

$this->title = 'Создать категорию презентации';
$this->params['breadcrumbs'][] = ['label' => 'Prezentacii Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prezentacii-cat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
