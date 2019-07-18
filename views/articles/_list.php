<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<li><?=Html::a($model->title, Url::to(['view', 'id' => $model->id]));?></li>