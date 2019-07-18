<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "akcii".
 *
 * @property string $akcii_id
 * @property string $akcii_time
 * @property string $akcii_name
 * @property string $akcii_deistvie
 * @property string $akcii_short_desc
 * @property string $akcii_desc
 * @property string $akcii_img
 * @property string $akcii_type
 * @property string $akcii_showme
 */
class Akcii extends \yii\db\ActiveRecord
{
	public $img_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akcii';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['akcii_time', 'akcii_short_desc', 'akcii_desc'], 'required'],
            [['akcii_short_desc', 'akcii_desc'], 'string'],
            [['akcii_time'], 'string', 'max' => 100],
            [['akcii_name', 'akcii_deistvie', 'akcii_img'], 'string', 'max' => 255],
            [['img_file'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['img_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
            [['akcii_showme', 'akcii_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'akcii_id' => 'ID',
            'akcii_time' => 'Время публикации',
            'akcii_name' => 'Название',
            'akcii_deistvie' => 'Даты проведения',
            'akcii_short_desc' => 'Краткое описание',
            'akcii_desc' => 'Подробное описание',
            'akcii_img' => 'Миниатюра',
            'img_file' => 'Миниатюра',
            'akcii_type' => 'Статус',
            'akcii_showme' => 'Показывать на сайте',
        ];
    }
}
