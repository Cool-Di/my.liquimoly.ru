<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OilapilinksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование свойства CSS';
$this->params['breadcrumbs'][] = ['label' => 'API подбор масла', 'url' => ['/oilapi/settings?id='.$clid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oilapilinks-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<form action="<?=Url::toRoute('/oilapi/settings_edit?clid='.$clid.'&id='.$id)?>" method="post">
		<p><input name="css_value" type="text" value="<?=$value;?>"></p>
		<p><input name="css_value_id" type="hidden" value="<?=$id?>"></p>
		<p><input type="submit" value="Сохранить"></p>
		<?=Html :: hiddenInput(\Yii :: $app->getRequest()->csrfParam, \Yii :: $app->getRequest()->getCsrfToken(), []);?>
	</form>

</div>
