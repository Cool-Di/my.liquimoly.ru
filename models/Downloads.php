<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "downloads".
 *
 * @property integer $id
 * @property string $name
 * @property string $patch
 * @property string $short_desc
 * @property string $img
 */
class Downloads extends \yii\db\ActiveRecord
{
	public $hashtag_ids;
	public $f_patch;
	public $f_img;

    public function getFiletagall(){
		return $this->hasMany(DownloadsFileTag::className(), ['file_id' => 'id']);
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'downloads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_desc',], 'required'],
            [['name', 'patch', 'short_desc', 'img'], 'string', 'max' => 255],
            [['f_img'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['f_img'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
            [['f_patch'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['f_patch'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя загрузки',
            'patch' => 'Путь к файлу',
            'short_desc' => 'Краткое описание',
            'img' => 'Картинки',
        ];
    }
}
