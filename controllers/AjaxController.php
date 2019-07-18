<?php

namespace app\controllers;

use yii;
use yii\web\Response;
use app\models\Products;
use app\models\Basket;

class AjaxController extends \yii\web\Controller
    {
    public function actionUpdateorder()
        {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user_id = Yii::$app->user->getId();
        if ( !$user_id )
            {
            return ['addbasket' => 'error' ];
            }

        $data_array = Yii::$app->request->post( 'data' );
        $order_id   = Yii::$app->request->post( 'order_id' );

        $update_item_del = [];

        foreach ( $data_array as $code => $count )
            {
            if ( $count > 0 )
                {
                Yii::$app->db->createCommand()->update( 'orders_structure', [ 'count' => $count ], [ 'AND',
                                                                                                     [ 'code_id' => $code ],
                                                                                                     [ 'order_id' => $order_id ],
                                                                                                     [ 'user_id' => $user_id ]] )->execute();
                }
            else
                {
                $update_item_del[] = $code;
                }
            }

        Yii::$app->db->createCommand()->delete( 'orders_structure', [ 'AND',
                                                                      [ 'code_id' => $update_item_del ],
                                                                      [ 'order_id' => $order_id ],
                                                                      [ 'user_id' => $user_id ] ] )->execute();

        return [ 'updateorder' => 'ok' ];
        }

    public function actionUpdatebasket()
        {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user_id = Yii::$app->user->getId();
        if ( !$user_id )
            {
            return ['addbasket' => 'error' ];
            }
        $data = Yii::$app->request->post( 'data' );

        foreach ( $data as $code => $count )
            {
            if ( $count > 0 )
                {
                Yii::$app->db->createCommand('INSERT INTO `orders_structure` (`user_id`, `code_id`, `count`) VALUES (:user_id, :code, :count) ON DUPLICATE KEY UPDATE `count` = :count ', [':user_id' => $user_id, ':code' => $code, ':count' => $count ])->execute();
                }
            else
                {
                Yii::$app->db->createCommand('DELETE FROM `orders_structure` WHERE `user_id` = :user_id AND `code_id` = :code AND `order_id` = 0', [ ':user_id' => $user_id, ':code' => $code ] )->execute();
                }
            }

        return [ 'addbasket' => 'ok',
                 'b_count' => Basket::CountBasket() ];
        }

    public function actionAddbasket()
        {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user_id = Yii::$app->user->getId();
        if ( !$user_id )
            {
            return ['addbasket' => 'error' ];
            }
        $code = Yii::$app->request->post( 'code' );
        $count = Yii::$app->request->post( 'count' );

        if ( $count )
            {
            Yii::$app->db->createCommand('INSERT INTO orders_structure (`user_id`, `code_id`, `count`) VALUES (:user_id, :code, :count) ON DUPLICATE KEY UPDATE `count` = :count ', [':user_id' => $user_id, ':code' => $code, ':count' => $count ])->execute();
            }
        else
            {
            Yii::$app->db->createCommand('DELETE FROM `orders_structure` WHERE `user_id` = :user_id AND `code_id` = :code AND `order_id` = 0', [ ':user_id' => $user_id, ':code' => $code ])->execute();
            }

        return [ 'addbasket' => 'ok', 'b_count' => Basket::CountBasket() ];
        }

    // Получить все возможные фасовки заданного товара
    public function actionGetfasovki()
        {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $nom  = Yii::$app->request->post( 'nom' );
        $code = Yii::$app->request->post( 'code' );

        $product = Products::getProductsByOsnNom( $nom, $code );
        $basket  = Basket::GetBasket();
        foreach ( $product as $key => $p )
            {
            if ( isset( $basket[ $p[ 'code' ] ] ) )
                {
                $product[ $key ][ 'in_basket' ] = $basket[ $p[ 'code' ] ];
                }
            }

        return [ 'products' => $product, 'unit' => Yii::$app->params['disable_backorder'] ];
        }

    public function actionIndex()
        {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [ 'error' => 'no_action' ];
        }
    }