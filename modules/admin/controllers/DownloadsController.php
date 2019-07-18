<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Downloads;
use app\models\DownloadsFileTag;
use app\models\DownloadsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DownloadsController implements the CRUD actions for Downloads model.
 */
class DownloadsController extends Controller
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
     * Lists all Downloads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DownloadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Downloads model.
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
     * Creates a new Downloads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Downloads();
        $model->scenario = 'insert';

        if ($model->load(Yii::$app->request->post())) {
	        $file_img = UploadedFile::getInstance($model, 'f_img');
            $model->f_img = $file_img;

            $filename_img = md5(rand(0, 10000)) . '.' . $file_img->extension;
            if ($file_img->saveAs( Yii::getAlias('@webroot/downloads/img/') . $filename_img)) {
				$model->img = $filename_img;
    		}

	        $file_upload = UploadedFile::getInstance($model, 'f_patch');
            $model->f_patch = $file_upload;

            $filename_upload = md5(rand(0, 10000)) . '.' . $file_upload->extension;
            if ($file_upload->saveAs( Yii::getAlias('@webroot/downloads/') . $filename_upload)) {
				$model->patch = $filename_upload;
    		}

	        if ($model->save()){
				$post_data = Yii::$app->request->post();
				$hashtag_ids = $post_data['Downloads']['hashtag_ids'];
				foreach ($hashtag_ids as $h_id){
					$insert_id[] = [$model->id, $h_id];
				}
				if (sizeof($insert_id) > 0){
					$command = Yii::$app->db->createCommand("delete from `downloads_file_tag` where `file_id` = ".$model->id);
					$command->execute();
					Yii::$app->db->createCommand()->batchInsert('downloads_file_tag', ['file_id', 'tag_id'], $insert_id)->execute();
				}
	            return $this->redirect(['view', 'id' => $model->id]);
	        } else
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
     * Updates an existing Downloads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {

	        $file_i = UploadedFile::getInstance($model, 'f_img');
   	        if ($file_i){
	            $model->f_img = $file_i;

	            $filename = md5(rand(0, 10000)) . '.' . $file_i->extension;
	            if ($file_i->saveAs(Yii::getAlias('@webroot/downloads/img/') . $filename)) {
					$model->img = $filename;
	    		}
    		}

	        $file_pdf = UploadedFile::getInstance($model, 'f_patch');
   	        if ($file_pdf){
	            $model->f_patch = $file_pdf;

    	        $filename_pdf = md5(rand(0, 10000)) . '.' . $file_pdf->extension;
        	    if ($file_pdf->saveAs(Yii::getAlias('@webroot/downloads/') . $filename_pdf)) {
					$model->patch = $filename_pdf;
	    		}
	    	}

			$post_data = Yii::$app->request->post();
			$hashtag_ids = $post_data['Downloads']['hashtag_ids'];
			foreach ($hashtag_ids as $h_id){
				$insert_id[] = [$model->id, $h_id];
			}

			if (sizeof($insert_id) > 0){
				$command = Yii::$app->db->createCommand("delete from `downloads_file_tag` where `file_id` = ".$model->id);
				$command->execute();
				Yii::$app->db->createCommand()->batchInsert('downloads_file_tag', ['file_id', 'tag_id'], $insert_id)->execute();
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
     * Deletes an existing Downloads model.
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
     * Finds the Downloads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Downloads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Downloads::findOne($id)) !== null) {
	    	$model_tags = DownloadsFileTag::find()->andWhere(['file_id' => $model->id])->asArray()->all();
	    	$model->hashtag_ids = $model_tags;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
