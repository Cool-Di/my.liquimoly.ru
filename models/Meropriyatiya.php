<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meropriyatiya".
 *
 * @property integer $id
 * @property string $name
 * @property string $banner
 * @property string $desc_txt
 */
class Meropriyatiya extends \yii\db\ActiveRecord
{
	public $files_array;
	public $img_file;
	//public $image;
    /**
     * @inheritdoc
     */
    public function getdokladyall(){
		return $this->hasMany(MeropriyatiyaDoklady::className(), ['p_id' => 'id']);
	}

    public static function tableName()
    {
        return 'meropriyatiya';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'images_path', 'desc_txt'], 'required'],
            [['desc_txt'], 'string'],
            [['name', 'banner'], 'string', 'max' => 255],
            [['img_file'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['img_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название мероприятия',
            'banner' => 'Начальная картинка',
            'desc_txt' => 'Описание мероприятия',
            'images_path' => 'Папка с фотографиями'
        ];
    }
}
