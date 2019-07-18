<?php

namespace app\models;

use Yii;
use yii\base\Object;

/**
 * This is the model class for table "goods".
 *
 * @property string $Code
 * @property integer $Available
 * @property string $ReceiptDate
 */
class Basket extends Object
    {
    public static function CountBasket()
        {
        $count = Yii::$app->db->createCommand('SELECT SUM(`count`) AS `count` FROM `orders_structure` WHERE user_id = :user_id AND `order_id` = 0', ['user_id' => Yii::$app->user->getId()])->queryScalar();

        return $count;
        }

    public static function GetBasket()
        {
        $basket_content = Yii::$app->db->createCommand('SELECT `code_id`, `count` FROM `orders_structure` WHERE `user_id` = :user_id AND `order_id` = 0 ORDER BY `id`', ['user_id' => Yii::$app->user->getId()])->queryAll();

        $basket = [];
        foreach ($basket_content as $val)
            {
            $basket[$val['code_id']] = $val['count'];
            }

        return $basket;
        }
    }