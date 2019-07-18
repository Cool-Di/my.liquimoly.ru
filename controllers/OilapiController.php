<?php

namespace app\controllers;

use yii;
use app\models\Oilapiclients;
use app\models\OilapiclientsSearch;
use app\models\Oilapilinks;
use app\models\OilapilinksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Contractor;
use app\models\Oilapibill;
use yii\data\SqlDataProvider;

class OilapiController extends \yii\web\Controller
{

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

    public function actionImportfile($id){
		$post_data = Yii::$app->request->post();
        if (!empty($post_data)) {

			$file = \yii\web\UploadedFile::getInstanceByName('file_data');

	    	if (!empty($file)){
				$file_tmp_name = $file->tempName;
				$data = \moonland\phpexcel\Excel::import($file_tmp_name);
				foreach ($data[0] as $line){
					$art = (int)$line['Артикул'];
					$url = $line['Ссылка'];
					$status = (int)$line['Статус'];
					$items[] = "($id, $art, '$url', $status)";
				}

				$sql = "INSERT INTO `oilapi_links` (`id_client`, `r_code`, `link`, `status`) VALUES ".implode(',', $items)." ON DUPLICATE KEY UPDATE
				`id_client` = VALUES(`id_client`), `r_code` = VALUES(`r_code`), `link` = VALUES(`link`), `status` = VALUES(`status`)";

				Yii::$app->db->createCommand($sql)->execute();
	   		}

            return $this->redirect(['/oilapi/importfile?id='.$id.'&ok=1']);
        } else {
            return $this->render('importfile', ['id' => $id]);
        }
    }

    public function actionImport($id)
    {
        $s_params = Yii::$app->request->queryParams;
        $s_params['OilapilinksSearch']['id_client'] = $id;

        $searchModel = new OilapilinksSearch();
        $dataProvider = $searchModel->search($s_params);

        return $this->render('import', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    public function actionSettings_edit($clid, $id)
    {
		$post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
        	$id = $post_data['css_value_id'];
        	$value = $post_data['css_value'];

			$sql = "UPDATE `oilapi_css_value` SET `css_value` = '$value' WHERE `id` = $id AND `id_client` = $clid";
			Yii::$app->db->createCommand($sql)->execute();

            return $this->redirect(['/oilapi/settings?id='.$clid]);
        } else {
			$value = Yii::$app->db->createCommand('SELECT `css_value` FROM `oilapi_css_value`
					WHERE `id`= :id', [':id' => $id])->queryOne();
            return $this->render('settings_edit', ['id' => $id, 'clid' => $clid, 'value' => $value['css_value']]);
        }
    }

    public function actionSettings($id)
    {
   		$user_id = Yii::$app->user->getId();
		$api_data = Oilapiclients::find()->where(['id' => $id, 'user_id' => $user_id])->asArray()->one();

		$totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM `oilapi_css_value` AS `ocv`
					INNER JOIN `oilapi_css_property`AS `ocp` ON (`ocp`.`type` = `ocv`.`type_property`)
					INNER JOIN `oilapi_css_class` AS `occ` ON (`occ`.`type` = `ocv`.`type_class`)
					WHERE `id_client`= :id', [':id' => $id])->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT `ocv`.`id`, `class_name`, `property_name`, `css_value` FROM `oilapi_css_value` AS `ocv`
					INNER JOIN `oilapi_css_property`AS `ocp` ON (`ocp`.`type` = `ocv`.`type_property`)
					INNER JOIN `oilapi_css_class` AS `occ` ON (`occ`.`type` = `ocv`.`type_class`)
					WHERE `id_client`= :id',
            'params' => [':id' => $id],
			'totalCount' => $totalCount,
			'sort' => [
                'attributes' => [
                    'class_name' => [
                        'asc' => ['class_name' => SORT_ASC],
                        'desc' => ['class_name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Класс',
                    ],
                    'property_name' => [
                        'asc' => ['property_name' => SORT_ASC],
                        'desc' => ['property_name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Свойство',
                    ],
                    'css_value' => [
                        'asc' => ['css_value' => SORT_ASC],
                        'desc' => ['css_value' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Значение',
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
     	]);

        return $this->render('settings', ['dataProvider' => $dataProvider, 'api_data' => $api_data, 'clid' => $id]);
    }

    public function actionIndex()
    {
   		$user_id = Yii::$app->user->getId();

        $s_params = Yii::$app->request->queryParams;
        $s_params['OilapiclientsSearch']['user_id'] = $user_id;

        $searchModel = new OilapiclientsSearch();
        $dataProvider = $searchModel->search($s_params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Oilapilinks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id=null)
    {
        $model = new Oilapilinks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'id' => $id
            ]);
        }
    }

    /**
     * Updates an existing Oilapilinks model.
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
     * Deletes an existing Oilapilinks model.
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
     * Finds the Oilapilinks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Oilapilinks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Oilapilinks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
