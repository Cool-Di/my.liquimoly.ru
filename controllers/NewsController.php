<?php

namespace app\controllers;
use app\models\News;
use app\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class NewsController extends Controller
    {
    public function actionIndex()
        {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);
        }

    /**
     * Показать новость
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
        {
        return $this->render('view', [
            'model' => $this->findModel($id),
            ]);
        }
    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
        {
        if ( ( $model = News::findOne( $id ) ) !== null )
            {
            return $model;
            }
        else
            {
            throw new NotFoundHttpException( 'Страница не найдена.' );
            }
        }
    }