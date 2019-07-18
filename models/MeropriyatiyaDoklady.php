<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meropriyatiya_doklady".
 *
 * @property integer $id
 * @property integer $p_id
 * @property string $f_name
 * @property string $f_path
 */
class MeropriyatiyaDoklady extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meropriyatiya_doklady';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_id', 'f_name', 'f_path'], 'required'],
            [['p_id'], 'integer'],
            [['f_name', 'f_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'p_id' => 'P ID',
            'f_name' => 'F Name',
            'f_path' => 'F Path',
        ];
    }
}
