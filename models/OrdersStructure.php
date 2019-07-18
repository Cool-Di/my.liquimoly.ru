<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_structure".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $code_id
 * @property integer $count
 * @property double $price
 */
class OrdersStructure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_id', 'count'], 'required'],
            [['user_id', 'order_id', 'code_id', 'count'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'code_id' => 'Code ID',
            'count' => 'Count',
            'price' => 'Price',
        ];
    }
}
