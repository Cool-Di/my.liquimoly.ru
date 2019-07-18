<?php

namespace app\controllers;
use app\models\Meropriyatiya;
use yii\data\ArrayDataProvider;

require_once( \Yii::getAlias( '@webroot/albums/gallery_get.php' ) );

class MeropriyatiyaController extends \yii\web\Controller
{
    public function actionIndex()
    {
		$meropriyatiya = Meropriyatiya::find();
		$meropriyatiya->joinWith(['dokladyall']);

		$meropriyatiya_array = $meropriyatiya->asArray()->all();
		foreach ($meropriyatiya_array as $key => $m){
			if (!empty($m['images_path']))
				$meropriyatiya_array[$key]['gallery'] = get_gallery($m['images_path']);
		}

		$all_meropriyatiya = new ArrayDataProvider([
		    'allModels' => $meropriyatiya_array,
		    'pagination' => [
		    	'pageSize' => 5
		    ],
		    'key' => 'id',
		]);

        return $this->render('index', ['meropriyatiya' => $all_meropriyatiya]);
    }

}
