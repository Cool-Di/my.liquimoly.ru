<?php

namespace app\controllers;
use app\models\VebinarList;
use app\models\VebinarlistSearch;
use Yii;

class VebinarController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new VebinarlistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
