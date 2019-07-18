<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prezentacii_cat".
 *
 * @property integer $id
 * @property string $name
 */
class PrezentaciiCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prezentacii_cat';
    }
    /**
     * @inheritdoc
     */
    public function getItemsallcategory()
    {
        return $this->hasMany(Prezentacii::className(), ['cat_id' => 'id']);
    }

    public function getItemsAllVisibleCategory()
        {
        return $this->hasMany(Prezentacii::className(),['cat_id' => 'id'])->andFilterWhere(['show_yn' => 1]);
        }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }
}
