<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $shipment
 * @property string $pay_type
 * @property string $delivery_tipe
 * @property string $phone
 * @property string $desc_order
 * @property string $date_send
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipment', 'pay_type', 'delivery_tipe', 'phone'], 'required', 'message'=>'Заполните поле: {attribute}'],
            [['address_delivery'], 'required', 'when' => function($model){if ($model->delivery_tipe == 'self')return false;}, 'whenClient' => "function (attribute, value){return $('.field-orders-address_delivery').css('display')!='none'}"],
            [['id', 'client_id'], 'integer'],
            [['shipment', 'date_send'], 'safe'],
            [['pay_type', 'delivery_tipe', 'o_type'], 'string'],
            [['phone', 'desc_order', 'address_delivery'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'client_id'         => 'Client ID',
            'shipment'          => 'Желательная дата отгрузки',
            'pay_type'          => 'Способ оплаты',
            'delivery_tipe'     => 'Способ получения товара',
            'phone'             => 'Контактный телефон',
            'address_delivery'  => 'Адрес доставки',
            'desc_order'        => 'Комментарии к заказу',
            'date_send'         => 'Date Send',
            'o_type'            => 'Статус заказа'
        ];
    }
}
