<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prices".
 *
 * @property string $Code
 * @property string $RetailPrice
 * @property string $BranchPrice
 */
class Prices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'RetailPrice', 'BranchPrice'], 'required'],
            [['Code', 'RetailPrice', 'BranchPrice'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Code' => 'Code',
            'RetailPrice' => 'Retail Price',
            'BranchPrice' => 'Branch Price',
        ];
    }
}
