<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

Url::remember();
?>
<div class="user-list-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'username', 'headerOptions' => ['width' => '100']],
            ['attribute' => 'contractorName' ],
            ['label' => 'Действие', 'format' => 'raw', 'value' => function ($model){return '<a alt="Изменить пароль" title="Изменить пароль" onclick="if (!confirm(\'Изменить пароль?\')) return false;" href="'.Url::to(['user-list/change', 'id' => $model->id]).'"><i class="glyphicon glyphicon-retweet"></i></a>
            	&nbsp;&nbsp;&nbsp;<a title="Доступ к API подбора масла" alt="Доступ к API подбора масла" href="oilapiclients?OilapiclientsSearch%5Buserpkk%5D='.$model->username.'"><i class="glyphicon glyphicon-globe"></i></a>
            	&nbsp;&nbsp;&nbsp;<a title="Роли" alt="Роли" href="permit/user/view?id='.$model->id.'"><i class="glyphicon glyphicon-wrench"></i></a>';}]
        ],
    ]); ?>
</div>
