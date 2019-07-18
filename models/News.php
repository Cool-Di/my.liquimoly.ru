<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news".
 *
 * @property integer $news_id
 * @property string $news_time
 * @property string $news_name
 * @property string $news_short_desc
 * @property string $news_desc
 * @property string $news_img
 * @property integer $news_showme
 */
class News extends ActiveRecord
{
    public $img_file;

    public static function tableName()
    {
        return 'news';
    }

    public function rules()
    {
        return [
            [['news_time', 'news_name', 'news_img'], 'required'],
            [['news_time'], 'safe'],
            [['news_desc'], 'string'],
            [['news_showme'], 'integer'],
            [['news_name', 'news_img'], 'string', 'max' => 255],
            [['news_short_desc'], 'string', 'max' => 1024],
            [['img_file'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['img_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'news_id'         => 'ID',
            'news_time'       => 'Дата публикации',
            'news_name'       => 'Заголовок',
            'news_short_desc' => 'Краткое описание',
            'news_desc'       => 'Текст новости',
            'news_img'        => 'Миниатюра',
            'img_file'        => 'Миниатюра',
            'news_showme'     => 'Показывать на сайте',
        ];
    }
}
