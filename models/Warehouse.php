<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "warehouse".
 *
 * @property integer $id
 * @property string $unit
 * @property string $name
 * @property string $description
 * @property string $map
 * @property integer $order_num
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit', 'name', 'description', 'map', 'order_num'], 'required'],
            [['description', 'map'], 'string'],
            [['order_num'], 'integer'],
            [['unit', 'name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit' => 'Филиал',
            'name' => 'Название',
            'description' => 'Описание',
            'map' => 'Код для вставки карты',
            'order_num' => 'Порядковый номер',
        ];
    }
}
