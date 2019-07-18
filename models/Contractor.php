<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contractor".
 *
 * @property integer $id
 * @property string $Name
 * @property integer $Code
 * @property string $Status
 * @property string $Mail
 * @property string $Unit
 * @property string $ManagersName
 * @property string $ManagersPatronymic
 * @property string $ManagersMail
 * @property string $ManagersPhone
 * @property double $CurrentDebt
 * @property double $ExpiredDebt
 * @property integer $Delay
 * @property string $Prohibition
 */
class Contractor extends \yii\db\ActiveRecord
{
	public static function getClientsbymanager($manager_email){
  		$query = Contractor::find()->select('`contractor`.`name`, `contractor`.`Holding`, `contractor`.`Code`, `contractor`.`Status`, `contractor`.`Mail`, `user`.`id`, `auth_assignment`.`item_name`')
  		->innerJoin('user', '`user`.`username` = `contractor`.`Code`')
  		->innerJoin('auth_assignment', '`auth_assignment`.`user_id` = `user`.`id`')
  		->where(['=','`contractor`.`ManagersMail`',$manager_email]);
  		$clients_array = $query->asArray()->all();

  		return $clients_array;
	}

	public static function getClientsbyholding($holding_code){
  		$query = Contractor::find()->select('`contractor`.`name`, `contractor`.`Holding`, `contractor`.`Code`, `contractor`.`Status`, `contractor`.`Mail`, `user`.`id`, `auth_assignment`.`item_name`')
  		->innerJoin('user', '`user`.`username` = `contractor`.`Code`')
  		->innerJoin('auth_assignment', '`auth_assignment`.`user_id` = `user`.`id`')
  		->where(['=','`contractor`.`Holding`',$holding_code]);
  		$clients_array = $query->asArray()->all();

  		return $clients_array;
	}

	public static function check_manager_access($user_id, $manager_email){
  		$query = Contractor::find()->select('`contractor`.`name`, `contractor`.`Code`, `contractor`.`Status`, `contractor`.`Mail`, `user`.`id`, `auth_assignment`.`item_name`')
  		->innerJoin('user', '`user`.`username` = `contractor`.`Code`')
  		->innerJoin('auth_assignment', '`auth_assignment`.`user_id` = :code')
  		->addParams([':code' => $user_id])
  		->where(['=','`contractor`.`ManagersMail`',$manager_email]);
  		$clients_array = $query->asArray()->all();

  		return sizeof($clients_array);
	}

    public static function getContractorByUserId( $user_id )
        {
        $query = Contractor::find()->select('`contractor`.*')->
            innerJoin('user', '`contractor`.`Code` = `user`.`username`')->
            where(['=','`user`.`id`', $user_id] );
        return $query->asArray()->one();
        }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contractor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Code', 'Status', 'Mail', 'Unit', 'ManagersName', 'ManagersPatronymic', 'ManagersMail', 'ManagersPhone', 'CurrentDebt', 'ExpiredDebt', 'Delay', 'Prohibition'], 'required'],
            [['Code', 'Delay'], 'integer'],
            [['Status', 'Prohibition'], 'string'],
            [['CurrentDebt', 'ExpiredDebt'], 'number'],
            [['Name', 'Mail', 'ManagersMail'], 'string', 'max' => 255],
            [['Unit', 'ManagersName', 'ManagersPatronymic', 'ManagersPhone'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
            'Code' => 'Code',
            'Status' => 'Status',
            'Mail' => 'Mail',
            'Unit' => 'Unit',
            'ManagersName' => 'Managers Name',
            'ManagersPatronymic' => 'Managers Patronymic',
            'ManagersMail' => 'Managers Mail',
            'ManagersPhone' => 'Managers Phone',
            'CurrentDebt' => 'Current Debt',
            'ExpiredDebt' => 'Expired Debt',
            'Delay' => 'Delay',
            'Prohibition' => 'Prohibition',
        ];
    }
}
