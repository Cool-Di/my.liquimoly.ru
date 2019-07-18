<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Prezentacii;
use app\models\PrezentaciiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * PrezentaciiController implements the CRUD actions for Prezentacii model.
 */
class PrezentaciiController extends Controller
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
     * Lists all Prezentacii models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrezentaciiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prezentacii model.
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
     * Creates a new Prezentacii model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Prezentacii();

        $model->scenario = 'insert';

        if ($model->load(Yii::$app->request->post())) {
	        $file = UploadedFile::getInstance($model, 'img_file');
            $model->img_file = $file;

            $filename = md5(rand(0, 10000)) . '.' . $file->extension;
            if ($file->saveAs(  Yii::getAlias('@webroot/uploads/prezentacii/') . $filename)) {
				$model->img = $filename;
    		}

	        $file_pdf = UploadedFile::getInstance($model, 'prezent_file');
            $model->prezent_file = $file_pdf;

            $filename_pdf = md5(rand(0, 10000)) . '.' . $file_pdf->extension;
            if ($file_pdf->saveAs(  Yii::getAlias('@webroot/uploads/prezentacii/') . $filename_pdf)) {
				$model->file = $filename_pdf;
    		}

	        if ($model->save())
	            return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Prezentacii model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
	        $file_i = UploadedFile::getInstance($model, 'img_file');
   	        if ($file_i){
	            $model->img_file = $file_i;

	            $filename = md5(rand(0, 10000)) . '.' . $file_i->extension;
	            if ($file_i->saveAs(  Yii::getAlias('@webroot/uploads/prezentacii/') . $filename)) {
					$model->img = $filename;
	    		}
    		}

	        $file_pdf = UploadedFile::getInstance($model, 'prezent_file');
   	        if ($file_pdf){
	            $model->prezent_file = $file_pdf;

    	        $filename_pdf = md5(rand(0, 10000)) . '.' . $file_pdf->extension;
        	    if ($file_pdf->saveAs(  Yii::getAlias('@webroot/uploads/prezentacii/') . $filename_pdf)) {
					$model->file = $filename_pdf;
	    		}
	    	}

	        if ($model->save())
	            return $this->redirect(['view', 'id' => $model->id]);
	        else
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Prezentacii model.
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
     * Finds the Prezentacii model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prezentacii the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prezentacii::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
