<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $token
 * @property string $email
 * @property string $temp_password
 * @property string $status
 */
class UserList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password','required'],
            ['password', 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'ПКК',
            'password' => 'Пароль',
            'contractorName' => 'Название'
        ];
    }

    public function getContractor()
    {
        return $this->hasOne(Contractor::className(),['Code'=>'username']);
    }

    public function getContractorName()
    {
        return $this->contractor->Name;
    }

//    public function beforeSave($insert)
//    {
//        if (parent::beforeSave($insert)) {
//            $this->password = Yii::$app->security->generatePasswordHash($this->password, 4);
//
//            return true;
//        }
//        return false;
//    }
}
