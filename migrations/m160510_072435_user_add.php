<?php

use yii\db\Migration;

class m160510_072435_user_add extends Migration
    {
    public $user_name   = 'admin';
    public $email       = 'admin@w7.ru';
    public $password    = 'admin';

    public function up()
        {
        $password = Yii::$app->security->generatePasswordHash( $this->password );
        $auth_key = Yii::$app->security->generateRandomString();
        $token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->insert( "{{%user}}",
            [
                'username'  => $this->user_name,
                'email'     => $this->email,
                'password'  => $password,
                'auth_key'  => $auth_key,
                'token'     => $token
            ] );
        }

    public function down()
        {
        $this->delete( "{{%user}}", [ 'username' => $this->user_name ] );
        }
    }
