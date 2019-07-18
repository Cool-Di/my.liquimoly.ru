<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\News;
use app\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class NewsController extends Controller
    {

    public function actionIndex()
        {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        return $this->render( 'index', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, ] );
        }

    public function actionView( $id )
        {
        return $this->render( 'view', [ 'model' => $this->findModel( $id ), ] );
        }

    public function actionDelete( $id )
        {
        $this->findModel( $id )->delete();

        return $this->redirect( [ 'index' ] );
        }

    public function actionCreate()
        {
        $model = new News();
        $model->scenario = 'insert';

        if ( $model->load( Yii::$app->request->post() ) )
            {
            $model->news_time = date( 'Y-m-d H:i:s', strtotime( $model->news_time ) );
            $file = UploadedFile::getInstance( $model, 'img_file' );
            $model->img_file = $file;

            $filename = md5( mktime() ) . '.' . $file->extension;
            if ( $file->saveAs( Yii::getAlias('@webroot') . '/uploads/news/' . $filename ) )
                {
                $model->news_img = $filename;
                }

            if ( $model->save() )
                {
                return $this->redirect( [ 'view', 'id' => $model->news_id ] );
                }
            else
                {
                return $this->render( 'create', [ 'model' => $model, ] );
                }
            }
        else
            {
            return $this->render( 'create', [ 'model' => $model, ] );
            }
        }

    public function actionUpdate( $id )
        {
        $model = $this->findModel( $id );
        $model->scenario = 'update';
        if ( $model->load( Yii::$app->request->post() ) )
            {
            $model->news_time = date( 'Y-m-d H:i:s', strtotime( $model->news_time ) );
            $file = UploadedFile::getInstance( $model, 'img_file' );
            if ( $file )
                {
                $model->img_file = $file;

                $filename = md5( mktime() ) . '.' . $file->extension;
                if ( $file->saveAs( Yii::getAlias('@webroot') . '/uploads/news/' . $filename ) )
                    {
                    $model->news_img = $filename;
                    }
                }

            if ( $model->save() )
                {
                return $this->redirect( [ 'view', 'id' => $model->news_id ] );
                }
            else
                {
                return $this->render( 'create', [ 'model' => $model, ] );
                }
            }
        else
            {
            return $this->render( 'create', [ 'model' => $model, ] );
            }
        }

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