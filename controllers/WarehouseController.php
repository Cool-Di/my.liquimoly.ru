<?php

namespace app\controllers;

use app\models\WarehouseSearch;
use yii\web\Controller;
use Yii;

class WarehouseController extends Controller
{
    public function actionIndex()
        {
            $searchModel = new WarehouseSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

}