<?php

namespace app\models;

use developeruz\db_rbac\interfaces\UserRbacInterface;
use yii\base\NotSupportedException;
use yii;

class User extends \yii\db\ActiveRecord implements UserRbacInterface, \yii\web\IdentityInterface
    {
    public function getBranch()
        {
        $branch = $this->getContractorValue('Unit');
        if (is_null($branch))
            {
            $branch = Yii::$app->params['default_branch'];
            }

        return $branch;
        }

    public function getContractorValue($value)
        {
        $code = $this->getUserName();
        $contractor_array = Contractor::findOne(['code' => $code]);

        return $contractor_array[$value];
        }

    /**
     * @inheritdoc
     */
    public function getUserName()
        {
        return $this->username;
        }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
        {
        return static::findOne(['id' => $id]);
        }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
        {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
        {
        return static::find()->where(['or',
            ['username' => $username],
            ['old_email' => $username]])->andWhere(['status' => 'work'])->one();
        //return static::findOne(['username' => $username]);
        }

    /**
     * @inheritdoc
     */
    public function getId()
        {
        return $this->getPrimaryKey();
        }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
        {
        return $this->authKey;
        }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
        {
        return $this->getAuthKey() === $authKey;
        }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
        {
        $password_result = Yii::$app->security->validatePassword($password, $this->password);
        if (!$password_result && $this->old_hash != '')
            {
            $password_result = Yii::$app->security->compareString($this->old_hash, md5($password));
            }
        return $password_result;
        }
    }
