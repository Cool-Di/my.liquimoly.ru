<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property integer $u_id
 * @property string $date
 * @property string $title
 * @property string $type
 * @property string $question
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'email_phone', 'question'], 'required'],
            [['u_id'], 'integer'],
            [['date'], 'safe'],
            [['type', 'question'], 'string'],
            [['title', 'email_phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'u_id' => 'User ID',
            'email_phone' => 'Средство связи',
            'date' => 'Date',
            'title' => 'Заголовок',
            'type' => 'Кому адресовать',
            'question' => 'Ваш вопрос',
        ];
    }
}
