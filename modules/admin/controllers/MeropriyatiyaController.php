<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\MeropriyatiyaDoklady;
use app\models\Meropriyatiya;
use app\models\MeropriyatiyaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\FileHelper;

/**
 * MeropriyatiyaController implements the CRUD actions for Meropriyatiya model.
 */
class MeropriyatiyaController extends Controller
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

	public function actionImageupload(){
		$f_name = Yii::$app->request->post('f_name');
		if (!empty($f_name)){
			$m_id = Yii::$app->request->post('m_id');
			$imageFile = UploadedFile::getInstanceByName('file');

		    $directory =  Yii::getAlias('@webroot/uploads/meropriyatiya/');
    		if (!is_dir($directory)) {
        		mkdir($directory);
		    }

    		if ($imageFile){
	    	    $uid = uniqid(time(), true);
	        	$fileName = $uid . '.' . $imageFile->extension;
		        $filePath = $directory . '/' . $fileName;
		        if ($imageFile->saveAs($filePath)){
	    	        $path = '/uploads' . '/meropriyatiya/' . $fileName;
					Yii::$app->db->createCommand()->batchInsert('meropriyatiya_doklady', ['p_id', 'f_name', 'f_path'], [[$m_id, $f_name, $path]])->execute();
					$f_id = Yii::$app->db->getLastInsertID();
		            return Json::encode([
    		            'files' => [[
    		            	'id' => $f_id,
	    	                'name' => $fileName,
	        	            'f_name' => $f_name,
	            	        'size' => $imageFile->size,
	                	    "url" => $path,
		                    "deleteUrl" => 'imagedelete?name=' . $fileName.'&form_id='.$form_id,
		                    "deleteType" => "POST"
	    	            ]]
	        	    ]);
		        }
    		}
      	} else return '';
		return '';
	}

	/*
	public function actionImageupload()
	{
		$form_id = Yii::$app->request->post('Meropriyatiya')['form_id'];

    	$imageFile = UploadedFile::getInstanceByName('Meropriyatiya[image]');
	    $directory = \Yii::getAlias('@web/uploads') . '/' . $form_id . '/';
	    $directory = 'C:/Server/www/yii.local.ru/web'.$directory;
    	if (!is_dir($directory)) {
        	mkdir($directory);
	    }

    	if ($imageFile) {
	        $uid = uniqid(time(), true);
	        $fileName = $uid . '.' . $imageFile->extension;
	        $filePath = $directory . $fileName;
	        if ($imageFile->saveAs($filePath)) {
	            $path = '/uploads/' . $form_id . '/' . $fileName;
	            return Json::encode([
    	            'files' => [[
	                    'name' => $fileName,
	                    'size' => $imageFile->size,
	                    "url" => $path,
	                    "deleteUrl" => 'imagedelete?name=' . $fileName.'&form_id='.$form_id,
	                    "deleteType" => "POST"
	                ]]
	            ]);
	        }
    	}

		return '';
	}
    */
	public function actionImagedelete()
	{
		$record_id = Yii::$app->request->post('id');
		$file_id = Yii::$app->request->post('f_id');

		$files = MeropriyatiyaDoklady::find()->where(['id' => $file_id])->one();
        $directory = Yii::getAlias( '@webroot/' ) . $files->f_path;
	    if (is_file($directory)){
			if (unlink($directory)){
				Yii::$app->db->createCommand()->delete('meropriyatiya_doklady', ['id' => $file_id])->execute();
				return Json::encode(['message' => 'ok', 'f_del_id' => $file_id]);
			} else return 'error';
	    } else return 'error';
	}

    /**
     * Lists all Meropriyatiya models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeropriyatiyaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Meropriyatiya model.
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
     * Creates a new Meropriyatiya model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Meropriyatiya();
        $model->scenario = 'insert';

        if ($model->load(Yii::$app->request->post())) {
	        $file = UploadedFile::getInstance($model, 'banner');
            $model->img_file = $file;

            $filename = md5(rand(0, 10000)) . '.' . $file->extension;
            if ($file->saveAs(  Yii::getAlias('@webroot/uploads/meropriyatiya/') . $filename)) {
				$model->banner = $filename;
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
     * Updates an existing Meropriyatiya model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

	    $files = MeropriyatiyaDoklady::find()->where(['p_id' => $model->id])->all();
	    $model->files_array = $files;

        if ($model->load(Yii::$app->request->post())) {
	        $file = UploadedFile::getInstance($model, 'banner');
	        if ($file){
	            $model->img_file = $file;

    	        $filename = md5(rand(0, 10000)) . '.' . $file->extension;
        	    if ($file->saveAs(  Yii::getAlias('@webroot/uploads/meropriyatiya/') . $filename)) {
					$model->banner = $filename;
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
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Meropriyatiya model.
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
     * Finds the Meropriyatiya model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Meropriyatiya the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meropriyatiya::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
