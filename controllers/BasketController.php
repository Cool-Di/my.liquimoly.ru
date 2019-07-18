<?php

namespace app\controllers;

use yii;
use app\models\Products;
use app\models\Orders;
use app\models\Contractor;
use app\models\ExcelUpload;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

class BasketController extends \yii\web\Controller
    {
    public function actionIndex()
        {
        $user_id = Yii::$app->user->getId();

        $excel = new ExcelUpload();

        if ( Yii::$app->request->isPost && isset($_POST['ExcelUpload']) )
            {
            $excel->excelFile = UploadedFile::getInstance($excel, 'excelFile');
            if ($excel->upload())
                {
                $data = \moonland\phpexcel\Excel::widget(['mode' => 'import', 'fileName' => $excel->filename]);
                if ( is_array($data) && isset($data[0]) && is_array($data[0]) )
                    {
                    $product_list = Yii::$app->db->createCommand('SELECT `code` FROM `products`')->queryColumn();
                    $product_list = array_flip($product_list);

                    foreach( $data[0] as $index => $row )
                        {
                        $keys = array_keys($row);
                        if ( isset($row[$keys[0]]) && $row[$keys[1]] && isset( $product_list[$row[$keys[0]]] ) )
                            {
                            Yii::$app->db->createCommand('INSERT INTO `orders_structure` (`user_id`,`order_id`,`code_id`,`count`) VALUES (:user_id,:order_id,:code_id,:count) ON DUPLICATE KEY UPDATE `count` = `count` + :count', [':user_id'=>$user_id,':order_id'=>0,':code_id'=>$row[$keys[0]],':count'=>$row[$keys[1]]])->execute();
                            }
                        }
                    return Yii::$app->response->redirect(['basket'])->send();
                    }
                }
            }

        $basket_content = Yii::$app->db->createCommand('SELECT `code_id`, `count` FROM `orders_structure` WHERE `user_id` = :user_id AND `order_id` = 0 ORDER BY `id`', [ ':user_id' => $user_id ])->queryAll();

        $basket = [];
        foreach ( $basket_content as $item_list )
            {
            $basket[$item_list['code_id']] = $item_list['count'];
            }

        $product = [];
        if ( count( $basket ) > 0 )
            {
            $product  = Products::GetItemByIds( array_keys( $basket ) );
            }

        $model            = new Orders();
        $model->client_id = Yii::$app->user->id;
        $model->date_send = date( 'Y-m-d H:i:s', time() );
        $post = Yii::$app->request->post();

        if ( $model->load( $post ) && count( $product ) )
            {
            $model->shipment = Yii::$app->formatter->asDate($model->shipment, 'yyyy-MM-dd');
            if ( $model->save() )
                {
                $message_item_list = null;
                $total = 0;
                foreach ( $product as $p )
                    {
                    $count = (int) $basket[ $p[ 'code' ] ];
                    Yii::$app->db->createCommand()->update('orders_structure',
                        ['order_id' => $model->id, 'price' => $p[ 'RetailPrice' ] ], [ 'user_id' => $user_id, 'order_id' => 0, 'code_id' => $p[ 'code' ] ] )->execute();

                    $message_item_list[$p['reklama']][] = [
                        'name_rus'  => $p[ 'name_rus' ],
                        'code'      => $p[ 'code' ],
                        'count'     => $count,
                        'price'     => $p['RetailPrice'],
                        'order_id'  => $model->id,
                        'quantity'  => ( $p[ 'value' ] + 0 ) . ' ' . $p['unit']  ]; //$p[ 'quantity_name' ] . ' ' .
                    $total += $p[ 'RetailPrice' ] * $count;
                    }

                // Очистка корзины от артефактов
                Yii::$app->db->createCommand('DELETE FROM `orders_structure` WHERE user_id = :user_id AND order_id = 0 AND `count` = 0', [ ':user_id' => $user_id ]);

                // Отправка письма
                $Contractor = Contractor::find()->select( ['Unit', 'ManagersMail', 'Name', 'Code' ] )
                           ->from( 'contractor' )
                           ->leftJoin('user', 'contractor.Code = user.username')
                           ->where(['=', 'user.id', $model->client_id] )->one();

                $email_to = [];
                $unit       = $Contractor->Unit;
                $email_to[] = $Contractor->ManagersMail;

                $branch = (new \yii\db\Query())->select(['name','email'])->from(['orders_branch'])->all();
                $branch_email = ArrayHelper::map($branch, 'name', 'email');

                if ( isset( $unit ) && isset( $branch_email[ $unit ] ) )
                    {
                    $email_to[] = $branch_email[ $unit ];
                    }

                if ( !count( $email_to ) )
                    {
                    $email_to[] = Yii::$app->params[ 'order_default_email' ];
                    }

                $dataProvider = new yii\data\ArrayDataProvider(['allModels'=>$message_item_list[0],'pagination'=>false]);
                $promoProvider = new yii\data\ArrayDataProvider(['allModels'=>$message_item_list[1],'pagination'=>false]);
                $message = Yii::$app->mailer->compose('order', [
                    'contractor'   => $Contractor,
                    'total'        => $total,
                    'dataProvider' => $dataProvider,
                    'promoProvider'=> $promoProvider,
                    'model'        => $model]);
                $message->setFrom( Yii::$app->params[ 'order_from_email' ] );
                $message->setTo( $email_to )->setSubject( "Новый заказ {$model->id} от {$Contractor->Name}" );
                foreach( $message_item_list as $index => $item_list )
                    {
                    $savePath = Yii::getAlias('@webroot/excel/');
                    $fileName = $model->id . '_'. $index . '.xls';
                    \moonland\phpexcel\Excel::widget( [
                        'mode' => 'export',
                        'format'  => 'Excel5',
                        'models'  => $item_list,
                        'columns' => [ 'code', 'count', 'order_id'],
                        'headers' => [ 'code' => 'Артикул', 'count' => 'Количество', 'order_id' => 'Заказ'],
                        'savePath' => $savePath,
                        'fileName' => $fileName,
                        'asAttachment' => false
                        ] );
                    $message->attach( $savePath . $fileName ,['fileName' => $Contractor->Name . '-' . $model->id . ($index == 1?' - реклама':'') . '.xls']);
                }
                $message->send(  );
                }
                return $this->render( 'success', [ 'id' => $model->id ] );
            }

        // Получение телефона и адреса доставки из предыдущего заказа
        $order_prev = Orders::find()->where( ['client_id' => Yii::$app->user->id] )->orderBy(['id' => SORT_DESC])->limit(1)->one();
        $model->phone = $post['Orders']['phone'] ?  : $order_prev->phone;
        $model->address_delivery = $post['Orders']['address_delivery'] ?  : $order_prev->address_delivery;

        // Получение списка адресов из 1С
        $address = Yii::$app->db->createCommand('SELECT `address` FROM `liquimoly`.`point_of_sales` WHERE `active` = "true" AND `pcc` IN ( SELECT `username` FROM `user` WHERE `id` = :user_id ) ', [ ':user_id' => $user_id ])->queryAll();
        $address = new yii\data\ArrayDataProvider(['allModels' => $address, 'pagination' => false]);

        return $this->render( 'index', [ 'product' => $product,
                                               'basket'  => $basket,
                                               'model'   => $model,
                                               'excel'   => $excel,
                                               'address' => $address ] );
        }
    }
