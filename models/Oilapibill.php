<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oilapi_bill".
 *
 * @property integer $id
 * @property integer $clid
 * @property integer $count
 * @property string $datetime
 */
class Oilapibill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oilapi_bill';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clid', 'count', 'datetime'], 'required'],
            [['clid', 'count'], 'integer'],
            [['datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clid' => 'Clid',
            'count' => 'Count',
            'datetime' => 'Datetime',
        ];
    }
}
