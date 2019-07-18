<?php

namespace app\controllers;


use yii\web\Controller;

class PriceListController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}