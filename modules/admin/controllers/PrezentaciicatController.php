<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\PrezentaciiCat;
use app\models\PrezentaciiCatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrezentaciicatController implements the CRUD actions for PrezentaciiCat model.
 */
class PrezentaciicatController extends Controller
    {
    /**
     * @inheritdoc
     */
    public function behaviors()
        {
        return [ 'verbs' => [ 'class' => VerbFilter::className(),
                              'actions' => [ 'delete' => [ 'POST' ], ], ], ];
        }

    /**
     * Lists all PrezentaciiCat models.
     * @return mixed
     */
    public function actionIndex()
        {
        $searchModel  = new PrezentaciiCatSearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        return $this->render( 'index', [ 'searchModel' => $searchModel,
                                         'dataProvider' => $dataProvider, ] );
        }

    /**
     * Displays a single PrezentaciiCat model.
     * @param integer $id
     * @return mixed
     */
    public function actionView( $id )
        {
        return $this->render( 'view', [ 'model' => $this->findModel( $id ), ] );
        }

    /**
     * Creates a new PrezentaciiCat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
        {
        $model = new PrezentaciiCat();

        if ( $model->load( Yii::$app->request->post() ) && $model->save() )
            {
            return $this->redirect( [ 'view',
                                      'id' => $model->id ] );
            }
        else
            {
            return $this->render( 'create', [ 'model' => $model, ] );
            }
        }

    /**
     * Updates an existing PrezentaciiCat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id )
        {
        $model = $this->findModel( $id );

        if ( $model->load( Yii::$app->request->post() ) && $model->save() )
            {
            return $this->redirect( [ 'view',
                                      'id' => $model->id ] );
            }
        else
            {
            return $this->render( 'update', [ 'model' => $model, ] );
            }
        }

    /**
     * Deletes an existing PrezentaciiCat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete( $id )
        {
        $this->findModel( $id )->delete();

        return $this->redirect( [ 'index' ] );
        }

    /**
     * Finds the PrezentaciiCat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrezentaciiCat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
        {
        if ( ( $model = PrezentaciiCat::findOne( $id ) ) !== null )
            {
            return $model;
            }
        else
            {
            throw new NotFoundHttpException( 'The requested page does not exist.' );
            }
        }
    }
