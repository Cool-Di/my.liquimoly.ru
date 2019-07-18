<?php

namespace app\controllers;

use Yii;
use app\models\UserList;
use app\models\UserListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserListController implements the CRUD actions for UserList model.
 */
class UserListController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }


    public function actionChange( $id )
        {
        $password        = Yii::$app->security->generateRandomString( 8 );

        $model           = UserList::findOne( $id );
        $model->password = Yii::$app->security->generatePasswordHash( $password, 4 );

        if ( $model->save() )
            {
            return $this->render( 'change', [ 'password' => $password,
                                              'username' => $model->username ] );
            }
        else
            {
            return $this->render( 'error', [ 'username' => $model->username ] );
            }
        }

    /**
     * Lists all UserList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setSort([
        	'attributes' => [
        		'username',
            	'contractorName' => ['asc'=>['`contractor`.`Name`' => SORT_ASC], 'desc'=>['`contractor`.`Name`' => SORT_DESC]]
    	    ]
	    ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserList model.
     * @param integer $id
     * @return mixed
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new UserList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new UserList();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing UserList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing UserList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the UserList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
