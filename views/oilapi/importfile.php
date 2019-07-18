<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OilapilinksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Импорт из XLS файла';
$this->params['breadcrumbs'][] = ['label' => 'API подбор масла', 'url' => ['/oilapi']];
$this->params['breadcrumbs'][] = $this->title;

$get_data = Yii::$app->request->get();
$ok = $get_data['ok'];
?>
<div class="oilapilinks-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?if ($ok){?><p><b>Ипорт успешно завершен!</b></p><?}?>
	<form enctype="multipart/form-data" action="<?=Url::toRoute('oilapi/importfile?id='.$id)?>" method="post">
		<p><input type="file" name="file_data"></p>
		<p><input type="submit" value="Импортировать"></p>
		<?=Html :: hiddenInput(\Yii :: $app->getRequest()->csrfParam, \Yii :: $app->getRequest()->getCsrfToken(), []);?>
	</form>

</div>
