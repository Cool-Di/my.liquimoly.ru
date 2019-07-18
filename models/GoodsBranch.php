<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_branch".
 *
 * @property string $Branch
 * @property string $Code
 * @property integer $Available
 * @property string $ReceiptDate
 */
class GoodsBranch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Branch'], 'string'],
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
            'Branch' => 'Branch',
            'Code' => 'Code',
            'Available' => 'Available',
            'ReceiptDate' => 'Receipt Date',
        ];
    }
}
