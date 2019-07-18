<?php

namespace app\controllers;

use Yii;
use app\models\Oilapiclients;
use app\models\OilapiclientsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UserList;
use app\models\Oilapibill;

/**
 * OilapiclientsController implements the CRUD actions for Oilapiclients model.
 */
class OilapiclientsController extends Controller
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

    public function actionUpdate_bill($id)
    {
        $model = $this->findModel($id);
		$post_data = Yii::$app->request->post();

        if (isset($post_data['clid'])) {
        	$limit = (int)$post_data['bill_limit'];

            $model_bill = new Oilapibill;
            $model_bill->clid = (int)$post_data['clid'];
            $model_bill->count = $limit;
            $model_bill->datetime = date("Y-m-d H:i:s");
            if ($model->limit + $limit < 0)
            $model->limit = 0; else
            $model->limit += $limit;

			$model_bill->save();
			$model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update_bill', [
                'model' => $model,
            ]);
        }
	}
    /**
     * Lists all Oilapiclients models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OilapiclientsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Oilapiclients model.
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
     * Creates a new Oilapiclients model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Oilapiclients();
		$load = Yii::$app->request->post();
		$pkk = $load['Oilapiclients']['userpkk'];
		unset($load['Oilapiclients']['userpkk']);

        if ($model->load($load)) {
        	$user_list = UserList::findOne(['username' => $pkk]);
        	$model->user_id = $user_list->id;
        	$model->limit = 0;
        	$model->token = md5($pkk.time().rand(0,10000));
			$model->begin_time = date("Y-m-d H:i:s");
			$model->update_time = date("Y-m-d H:i:s");

		  	if ($model->save()){
		  		$clid = $model->id;

				$sql = "INSERT INTO `oilapi_css_value` (`id_client`, `type_class`, `type_property`, `css_value`) VALUES
				($clid, 1, 1, 'relative'),
				($clid, 1, 3, '900px'),
				($clid, 1, 5, '900px'),
				($clid, 1, 6, 'normal 18px OpenSans'),
				($clid, 1, 7, '#000000'),
				($clid, 1, 8, '1px solid #eeeeee'),
				($clid, 1, 9, '15px'),
				($clid, 1, 10, '0'),
				($clid, 1, 11, 'auto'),
				($clid, 2, 1, 'absolute'),
				($clid, 2, 2, 'hidden'),
				($clid, 2, 4, 'auto'),
				($clid, 2, 26, '0'),
				($clid, 2, 27, '0'),
				($clid, 2, 13, '0'),
				($clid, 2, 14, '0'),
				($clid, 2, 15, '#F0F0F0'),
				($clid, 2, 16, '0.5'),
				($clid, 3, 3, '100%'),
				($clid, 3, 4, '100%'),
				($clid, 3, 17, 'url(''http://my.liquimoly.ru/oilapi/spinner.gif'') no-repeat center center transparent'),
				($clid, 4, 3, '100%'),
				($clid, 4, 6, 'normal 16px OpenSans'),
				($clid, 4, 9, '0'),
				($clid, 4, 10, '0 0 5px 0'),
				($clid, 5, 3, '100%'),
				($clid, 5, 10, '0 0 5px 0'),
				($clid, 6, 18, 'none'),
				($clid, 6, 9, '0'),
				($clid, 6, 10, '5px 0 0 0'),
				($clid, 7, 9, '1px'),
				($clid, 7, 10, '1px 0'),
				($clid, 7, 19, '1px dashed #2b2b2b'),
				($clid, 7, 20, 'pointer'),
				($clid, 7, 6, 'normal 12px OpenSans'),
				($clid, 8, 18, 'none'),
				($clid, 8, 9, '0'),
				($clid, 8, 10, '0'),
				($clid, 9, 21, 'block'),
				($clid, 9, 20, 'pointer'),
				($clid, 9, 15, '#4F5155'),
				($clid, 9, 22, '20px'),
				($clid, 9, 7, '#FFFFFF'),
				($clid, 9, 9, '1px 4px'),
				($clid, 9, 10, '0'),
				($clid, 10, 9, '0'),
				($clid, 10, 10, '0'),
				($clid, 11, 9, '2px 0 0 0'),
				($clid, 12, 9, '10px 20px 0'),
				($clid, 13, 18, 'none'),
				($clid, 13, 9, '0'),
				($clid, 13, 10, '0'),
				($clid, 13, 23, 'left'),
				($clid, 13, 3, '50%'),
				($clid, 14, 24, 'bold'),
				($clid, 15, 9, '10px'),
				($clid, 15, 10, '5px 0'),
				($clid, 15, 19, '0px dashed #4b5455'),
				($clid, 16, 9, '0 0 15px 0'),
				($clid, 16, 8, '0px solid #4b5455'),
				($clid, 17, 3, '15%'),
				($clid, 17, 4, '15%'),
				($clid, 17, 8, '0'),
				($clid, 17, 23, 'left'),
				($clid, 17, 10, '0 5px 5px 0'),
				($clid, 18, 21, 'block'),
				($clid, 18, 10, '5px 0 5px 0'),
				($clid, 19, 25, 'right'),
				($clid, 20, 3, '100%'),
				($clid, 20, 28, 'both'),
				($clid, 21, 7, '#ff0000'),
				($clid, 22, 10, '0'),
				($clid, 22, 7, '#ff0000'),
				($clid, 23, 21, 'block'),
				($clid, 23, 24, 'normal');";
                Yii::$app->db->createCommand($sql)->execute();

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
     * Updates an existing Oilapiclients model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Oilapiclients model.
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
     * Finds the Oilapiclients model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Oilapiclients the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Oilapiclients::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
