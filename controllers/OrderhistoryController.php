<?php

namespace app\controllers;

use app\models\Contractor;
use app\models\OrdersBranch;
use yii;
use app\models\Products;
use app\models\Orders;
use app\models\OrdersStructure;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class OrderhistoryController extends \yii\web\Controller
{
	public function actionCancel($order_id){
		Yii::$app->db->createCommand('UPDATE '.Orders::tableName().' SET `o_type`=\'cancel\' WHERE id=:id')->bindValue(':id', $order_id)->execute();
		Yii::$app->response->redirect('/index.php/orderhistory/index');
	}

    public function actionCopy($order_id){
    Yii::$app->db->createCommand("
          INSERT INTO orders (`client_id`, `pay_type`, `delivery_tipe`, `phone`, `address_delivery`, `desc_order`, `date_send`, `o_type`)
		  SELECT `client_id`, `pay_type`, `delivery_tipe`, `phone`, `address_delivery`, `desc_order`, NOW(), 'rough'
		  FROM orders
		  WHERE `id` = :order_id")->bindValue(':order_id', $order_id)->execute();
    $id = Yii::$app->db->getLastInsertID();

    Yii::$app->db->createCommand("
          INSERT INTO orders_structure (`order_id`, `code_id`, `count`, `price`) 
          SELECT :id, `code_id`, `count`, `prices`.`RetailPrice` 
          FROM orders_structure INNER JOIN `prices` ON `prices`.`Code` = `orders_structure`.`code_id`
          WHERE `order_id` = :order_id")->bindValues([':order_id' => $order_id, ':id' => $id])->execute();

    Yii::$app->response->redirect('/index.php/orderhistory/index');
    }

    public function actionCopybasket($order_id)
        {
        Yii::$app->db->createCommand( 'INSERT INTO `orders_structure` (`user_id`, `code_id`, `count`) SELECT :user_id, `code_id`, `count` FROM `orders_structure` WHERE `order_id` = :order_id', [ ':user_id' => Yii::$app->user->getId(), ':order_id' => $order_id ] )->execute();

        Yii::$app->response->redirect('/index.php/basket');
        }

    public function actionIndex()
        {

        $query = Orders::find();
        $query->select( [ '`o`.*', 'count' => '`s`.`count`', 'summa' => '`s`.`summa`', 'username', 'name' => '`contractor`.`Name`', 'date' => 'DATE_FORMAT(`shipment`,"%d.%m.%Y")', 'unit' => '`contractor`.`Unit`', 'date_send' => 'DATE_FORMAT(CONVERT_TZ(`date_send`,\'+00:00\',\'+3:00\'),"%d.%m.%Y %H:%i")' ] );
        $query->from( ['o' => 'orders'] );
        $query->leftJoin( 'orders_summary s', '`s`.`order_id` = `o`.`id`' );
        $query->leftJoin( 'user', 'user.id = `o`.`client_id`' );

        $user_role = key( Yii::$app->authManager->getRolesByUser( Yii::$app->user->getId() ) );

        $is_show_other = in_array( $user_role, ['order_user','root']);
        $contractor_info = [];

        if( !$is_show_other )
            {
            $query->leftJoin( 'contractor', 'contractor.Code = `user`.`username`' );
            $query->where( [ '=', 'client_id', Yii::$app->user->id ] );
            $contractor_info = Contractor::getContractorByUserId( Yii::$app->user->id );
            }
        else
            {
            $username = Yii::$app->user->identity->username;

            if ( $user_role == 'root' )
                {
                $unit_list_from_db = (new \yii\db\Query())->select(['name'])->from('orders_branch')->all();
                }
            else
                {
                $unit_list_from_db = (new \yii\db\Query())->select(['name'])->from('orders_branch')->where(['username' => $username])->all();
                }

            $unit_list = [];
            foreach( $unit_list_from_db as $row )
                {
                $unit_list[] = $row['name'];
                }

            $unit_list_query = '"'.implode('","',$unit_list).'"';

            $query->innerJoin( 'contractor', 'contractor.Code = `user`.`username` AND contractor.Unit IN ('.$unit_list_query.')' );
            }

        $query->groupBy( [ '`o`.`id`' ] );
        $query->orderBy( [ '`o`.date_send' => SORT_DESC, '`o`.id' => SORT_DESC ] );

        $orders_array = $query->asArray()->all();

        $dataProvider = new ArrayDataProvider( [ 'allModels' => $orders_array,
//                                                 'sort' => [ 'attributes' => [ 'id', 'count', 'shipment', 'summa' ], ],
                                                 'pagination' => [ 'pageSize' => 20, ] ] );

        return $this->render( 'index', [ 'dataProvider' => $dataProvider, 'is_show_other' => $is_show_other, 'contractor_info' => $contractor_info ] );
        }

    public function actionShow( $order_id )
        {
        $model      = new Orders();
        $order_info = Orders::findOne( $order_id ); //['=', 'order_id', $order_id]
        $is_saved = '';

        $order_s    = OrdersStructure::find()->Where( [ '=', 'order_id', $order_id ] )->asArray()->all();
        $basket = $item_ids = $orders_price = [];
        $total  = 0;
        foreach ( $order_s as $i )
            {
            $basket[ $i[ 'code_id' ] ]      = $i[ 'count' ];
            $item_ids[]                     = $i[ 'code_id' ];
            $total                          += $i[ 'price' ] * $i[ 'count' ];
            $orders_price[ $i[ 'code_id' ] ] = $i[ 'price' ];
            }

        $product = Products::GetItemByIds( $item_ids, 0 );
        $reklama = Products::GetItemByIds( $item_ids, 1 );

        $Contractor = Contractor::find();
        $Contractor->select(['Unit', 'ManagersMail','Code','Name'])->from('contractor')->leftJoin('user','contractor.Code = user.username')->where(['=','user.id',$order_info->getAttribute('client_id')]);
        $Contractor_array = $Contractor->asArray()->one();

        if ( $order_info->load( Yii::$app->request->post() ) )
            {
            if ( $order_info->o_type == 'rough' )
                {
                $order_info->shipment = Yii::$app->formatter->asDate($order_info->shipment, 'yyyy-MM-dd');
                $order_info->o_type = 'wait';
                $order_info->save();

                $message = null;

                // формирование списков товаров

                foreach( [$product,$reklama] as $is_reklama => $product_list )
                    {
                    foreach( $product_list as $p )
                        {
                        $message[$is_reklama][] = [
                            'name_rus' => $p['name_rus'],
                            'code'     => $p['code'],
                            'count'    => (int)$basket[$p['code']],
                            'price'    => $p[ 'RetailPrice' ]
                            ];
                        }
                    }

                // формирование адресатов
                $email_to = [];
                $unit       = $Contractor_array['Unit'];
                $email_to[] = $Contractor_array['ManagersMail'];

                $branch = (new \yii\db\Query())->select(['name','email'])->from(['orders_branch'])->all();
                $branch_email = ArrayHelper::map($branch, 'name', 'email');

                if ( isset( $branch_email[ $unit ] ) )
                    {
                    $email_to[] = $branch_email[ $unit ];
                    }

                if ( !count( $email_to ) ) // в случае чего отправляем на ящик по-умолчанию
                    {
                    $email_to[] = Yii::$app->params[ 'order_default_email' ];
                    }

                // оформление списков товаров
                $dataProvider = new yii\data\ArrayDataProvider(['allModels'=>$message[0],'pagination'=>false]);
                $promoProvider = new yii\data\ArrayDataProvider(['allModels'=>$message[1],'pagination'=>false]);

                //  составление и отправка письма
                Yii::$app->mailer->compose('order', [
                    'contractor'   => $Contractor_array,
                    'total'        => $total,
                    'dataProvider' => $dataProvider,
                    'promoProvider'=> $promoProvider,
                    'model'        => $order_info ])
                    ->setFrom( Yii::$app->params[ 'order_from_email' ] )
                    ->setTo( $email_to )
                    ->setSubject( "Новый заказ {$order_info->id} от {$Contractor_array['Name']}" )
                    ->send(  );

                // перевод на страницу с информацией об успешности заказа
                return $this->render( 'success', [ 'id' => $order_info->id ] );
                }
            else
                {
                $is_saved = 'Статус обновлён';
                $order_info->save();
                }
            }

        $is_order_user = key( Yii::$app->authManager->getRolesByUser( Yii::$app->user->getId() ) ) == 'order_user' ? true : false;

        return $this->render( 'order_show',
                                [ 'product'          => $product,
                                  'reklama'          => $reklama,
                                  'basket'           => $basket,
                                  'order_info'       => $order_info,
                                  'model'            => $model,
                                  'is_saved'         => $is_saved,
                                  'is_order_user'    => $is_order_user,
                                  'contractor_array' => $Contractor_array,
                                  'total'            => $total,
                                  'orders_price'     => $orders_price] );
        }

    public function actionDownload( $order_id, $reklama = 0 )
        {
        $order_s = OrdersStructure::find()->select(['`o`.`id`','code_id','count','order_id'])->from( OrdersStructure::tableName() . ' as `o`' )->leftJoin('products', 'products.code = `o`.`code_id`')->where([ '=', 'order_id', $order_id ])->andWhere(['=','products.reklama',$reklama])->groupBy( '`o`.`id`' )->asArray()->all();

        $contractor_name = Contractor::find()->select( [ 'Name' ] )->from( 'contractor' )->leftJoin('user', 'contractor.Code = user.username')->leftJoin('orders', 'orders.client_id = user.id')->where(['=', 'orders.id', $order_id ] )->one()->Name;

        //todo защитить от скачивания чужого заказа

        \moonland\phpexcel\Excel::export( [ 'format'  => 'Excel5',
                                            'models'  => $order_s,
                                            'columns' => [ 'code_id',
                                                           'count',
                                                           'order_id'],
                                            'headers' => [ 'code_id' => 'Артикул',
                                                           'count' => 'Количество',
                                                            'order_id' => 'Заказ'],
                                            'fileName' => $contractor_name . '-' . $order_id . ($reklama?' - реклама':'') . '.xls' ] );
        }
}