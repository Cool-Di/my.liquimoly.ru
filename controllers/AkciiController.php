<?php

namespace app\controllers;

use Yii;
use app\models\Akcii;
use app\models\AkciiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AkciiController implements the CRUD actions for Akcii model.
 */
class AkciiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Akcii models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AkciiSearch();
        $dataProvider = $searchModel->search(['AkciiSearch' => ['akcii_type' => 0, 'akcii_showme' => 1]]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Akcii models.
     * @return mixed
     */
    public function actionArchiv()
    {
        $searchModel = new AkciiSearch();
        $dataProvider = $searchModel->search(['AkciiSearch' => ['akcii_type' => 1, 'akcii_showme' => 1]]);

        return $this->render('archiv', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Akcii model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionView_arch($id)
    {
        return $this->render('view_arch', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Akcii model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Akcii the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Akcii::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
