<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Akcii;
use app\models\AkciiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AkciiController implements the CRUD actions for Acii model.
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Akcii model.
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
     * Creates a new Akcii model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Akcii();
        $model->scenario = 'insert';

        if ($model->load(Yii::$app->request->post())) {
			$model->akcii_time = date('Y-m-d H:i:00', strtotime($model->akcii_time));
	        $file = UploadedFile::getInstance($model, 'img_file');
            $model->img_file = $file;

            $filename = md5(rand(0, 10000)) . '.' . $file->extension;
            if ($file->saveAs( Yii::getAlias('@webroot/uploads/akcii/') . $filename)) {
				$model->akcii_img = $filename;
    		}

	        if ($model->save())
	            return $this->redirect(['view', 'id' => $model->akcii_id]);
	        else
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Akcii model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post())) {
			$model->akcii_time = date('Y-m-d H:i:00', strtotime($model->akcii_time));
	        $file = UploadedFile::getInstance($model, 'img_file');
	        if ($file){
	            $model->img_file = $file;

    	        $filename = md5(rand(0, 10000)) . '.' . $file->extension;
        	    if ($file->saveAs( Yii::getAlias('@webroot/uploads/akcii/') . $filename)) {
					$model->akcii_img = $filename;
	    		}
	    	}

	        if ($model->save())
	            return $this->redirect(['view', 'id' => $model->akcii_id]);
	        else
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Akcii model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Akcii model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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
