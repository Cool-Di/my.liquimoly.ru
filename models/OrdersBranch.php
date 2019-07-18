<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_branch".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $timezone
 * @property string $username
 */
class OrdersBranch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'timezone', 'username'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['email', 'username'], 'string', 'max' => 255],
            [['timezone'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'timezone' => 'Timezone',
            'username' => 'Username',
        ];
    }
}
