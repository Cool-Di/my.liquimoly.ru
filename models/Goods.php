<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property string $Code
 * @property integer $Available
 * @property string $ReceiptDate
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Available', 'ReceiptDate'], 'required'],
            [['Available'], 'integer'],
            [['ReceiptDate'], 'safe'],
            [['Code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Code' => 'Code',
            'Available' => 'Available',
            'ReceiptDate' => 'Receipt Date',
        ];
    }
}
