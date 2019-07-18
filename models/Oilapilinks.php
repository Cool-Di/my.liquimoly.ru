<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oilapi_links".
 *
 * @property integer $id
 * @property integer $id_client
 * @property string $r_code
 * @property string $link
 */
class Oilapilinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oilapi_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_client', 'r_code'], 'required'],
            [['id_client', 'status'], 'integer'],
            [['r_code'], 'string', 'max' => 45],
            [['link'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_client' => 'Id Client',
            'r_code' => 'Артикул',
            'link' => 'Ссылка',
            'status' => 'Статус'
        ];
    }
}
