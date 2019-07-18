<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $model app\models\VebinarList */

$this->title = 'Добавить вебинар';
$this->params['breadcrumbs'][] = ['label' => 'Список вебинаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vebinar-list-create">

    <!--h1><?= Html::encode($this->title) ?></h1-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
