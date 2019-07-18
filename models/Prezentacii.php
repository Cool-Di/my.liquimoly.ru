<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prezentacii".
 *
 * @property integer $id
 * @property string $title
 * @property string $img
 * @property string $file
 * @property integer $cat_id
 * @property integer $show_yn
 */
class Prezentacii extends \yii\db\ActiveRecord
{
	public $img_file;
	public $prezent_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prezentacii';
    }
    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return $this->hasOne(PrezentaciiCat::className(), ['id' => 'cat_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'cat_id', 'show_yn'], 'required'],
            [['cat_id', 'show_yn'], 'integer'],
            [['title', 'img', 'file'], 'string', 'max' => 255],
            [['img_file'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['img_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
            [['prezent_file'], 'file', 'skipOnEmpty' => false, 'on' => 'insert'],
            [['prezent_file'], 'file', 'skipOnEmpty' => true, 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'img' => 'Миниатюра',
            'img_file' => 'Миниатюра',
            'file' => 'Файл презентации',
            'prezent_file'=> 'Файл презентации',
            'cat_id' => 'Категория',
            'show_yn' => 'Показывать на сайте',
        ];
    }
}
