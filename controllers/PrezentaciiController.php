<?php

namespace app\controllers;
use app\models\PrezentaciiSearch;
use app\models\PrezentaciiCatSearch;
use Yii;

class PrezentaciiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new PrezentaciiCatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
