<?php
namespace app\components;


use yii\base\Widget;
use Yii;
use yii\helpers\ArrayHelper;

class HelloWidget extends Widget
{
    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $role_alias = Yii::$app->authManager->getRolesByUser( Yii::$app->user->getId() );

        $name = Yii::$app->user->identity->getUserName();
        $unit = $msk_unit = 'Москва';

        if( in_array(key( $role_alias ),['holding_user','urlico_user']) )
            {
            $contractor_name = Yii::$app->user->identity->getContractorValue('Name');
            $contractor_unit = Yii::$app->user->identity->getContractorValue('Unit');
            if (!empty($contractor_name))
                {
                $name = $contractor_name;
                }
            if (!empty($contractor_unit))
                {
                $unit = $contractor_unit;
                }
            }

        $branch = (new \yii\db\Query())->select(['name','timezone'])->from(['orders_branch'])->all();
        $branch_timezone = ArrayHelper::map($branch, 'name', 'timezone');

        $DateTime = new \DateTime("now", new \DateTimeZone( $branch_timezone[$unit] ) );
        $time = $DateTime->format('d.m.Y / H:i');

        $DateTime = new \DateTime("now", new \DateTimeZone( $branch_timezone[$msk_unit] ) );
        $msk_time = $DateTime->format('d.m.Y / H:i');
        //$msk_time = Yii::$app->formatter->asDatetime('now');

        $lastlogin = Yii::$app->db->createCommand('SELECT DATE_FORMAT(`lastlogin`,"%d.%m.%Y / %H:%i") FROM `user` WHERE `id` = :id')->bindValue(':id',Yii::$app->user->id)->queryScalar();

        return $this->render('hello',
                                [
                                'name'      => $name,
                                'unit'      => $unit,
                                'msk_unit'  => $msk_unit,
                                'time'      => $time,
                                'msk_time'  => $msk_time,
                                'lastlogin' => $lastlogin
                                ]);
    }
}