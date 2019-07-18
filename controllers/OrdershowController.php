<?php

namespace app\controllers;

use yii;
use app\models\Products;
use app\models\Orders;
use app\models\OrdersStructure;
use yii\data\ArrayDataProvider;

class OrdershowController extends \yii\web\Controller
{
    public function actionIndex($user_id)
    {
    	$orders_array = Orders::find()->
    	select(['`o`.*', 'count' => 'SUM(`s`.`count`)', 'summa' => 'SUM(`s`.`price`)*(`s`.`count`)'])->
    	from('orders AS `o`')->
    	leftJoin(OrdersStructure::tableName().' s', '`s`.`order_id` = `o`.`id`')->Where(['=', 'client_id', $user_id])->groupBy(['`o`.`id`'])->
    	orderBy(['`o`.date_send'=>SORT_DESC,'`o`.id'=>SORT_DESC])->
    	asArray()->all();

		$dataProvider = new ArrayDataProvider([
		    'allModels' => $orders_array,
		    'sort' => [
        		'attributes' => ['id', 'count', 'shipment', 'summa'],
		    ],
		    'pagination' => [
		        'pageSize' => 10,
		    ]
		]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionShow($order_id){
    	$model = new Orders();
		$order_info = Orders::findOne(['=', 'order_id', $order_id]);
		$order_s = OrdersStructure::find()->Where(['=', 'order_id', $order_id])->asArray()->all();

		foreach ($order_s as $i){
			$basket[$i['code_id']] = $i['count'];
			$item_ids[] = $i['code_id'];
		}

 		$product = Products::GetItemByIds($item_ids);

		return $this->render('order_show', ['product' => $product, 'basket' => $basket, 'order_info' => $order_info, 'model' => $model]);
    }
}