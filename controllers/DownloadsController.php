<?php

namespace app\controllers;

use app\models\DownloadsHashTag;
use app\models\Downloads;
use yii\data\ArrayDataProvider;
use yii;

class DownloadsController extends \yii\web\Controller
    {
    public function actionIndex()
        {
        $model_hash = DownloadsHashTag::find();
        //$model = $model->joinWith(['filetagall']);

        $all_hash_tags = new ArrayDataProvider( [ 'allModels' => $model_hash->asArray()->all(),
                                                  'pagination' => false,
                                                  'key' => 'id', ] );

        $model_files = Downloads::find();
        $hash_tag    = Yii::$app->request->get( 'hash_id' );
        $model_files = $model_files->joinWith( [ 'filetagall' ] );

        if ( !empty( $hash_tag ) )
            {
            $model_files = $model_files->andWhere( [ 'tag_id' => $hash_tag ] );
            }

        $all_files = new ArrayDataProvider( [ 'allModels' => $model_files->asArray()->all(),
                                              'pagination' => [ 'pageSize' => 5 ],
                                              'key' => 'id', ] );

        return $this->render( 'index', [ 'hash_tags_DataProvider' => $all_hash_tags,
                                         'all_files' => $all_files ] );
        }

    }
