<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vebinar_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property string $link
 * @property string $video_link
 */
class VebinarList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vebinar_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['mouth','year'], 'integer'],
            [['title', 'date', 'link', 'video_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Год',
            'title' => 'Заголовок',
            'date' => 'Дата',
            'link' => 'Ссылка на вебинар',
            'mouth' => 'Месяц',
            'video_link' => 'Ссылка на видео',
        ];
    }
}
