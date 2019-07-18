<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_desc
 * @property string $full_desc
 * @property string $img
 */
class Articles extends \yii\db\ActiveRecord
{
	public $img_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_desc', 'full_desc'], 'required'],
            [['short_desc', 'full_desc', 'img'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['img_file'], 'file', 'skipOnEmpty' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'short_desc' => 'Краткое описание',
            'full_desc' => 'Текст статьи',
            'img_file' => 'Файл с изображение',
            'img' => 'Превью',
        ];
    }
}
