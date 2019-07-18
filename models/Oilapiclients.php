<?php

namespace app\models;

use Yii;
use app\models\UserList;

/**
 * This is the model class for table "oilapi_clients".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $name
 * @property string $host
 * @property string $ip
 * @property integer $limit
 * @property string $begin_time
 * @property string $update_time
 * @property integer $update_css
 * @property string $json_css
 */
class Oilapiclients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oilapi_clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'limit', 'host'], 'required'],
            [['user_id', 'limit'], 'integer'],
            [['begin_time', 'update_time'], 'safe'],
            [['userpkk'], 'string'],
            [['token', 'name', 'host'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 15],
            [['token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID клиента',
            'token' => 'Token',
            'name' => 'Имя сайта',
            'host' => 'Хост',
            'ip' => 'Ip',
            'limit' => 'Текщий лимит',
            'begin_time' => 'Begin Time',
            'update_time' => 'Update Time',
            'userpkk' => 'ПКК'
        ];
    }

    public function getUserlist()
    {
        return $this->hasOne(UserList::className(),['id'=>'user_id']);
    }

    public function getUserpkk()
    {
        return $this->userlist->username;
    }
}
