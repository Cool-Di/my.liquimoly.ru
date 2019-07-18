<?php
namespace app\components;


use yii\base\Widget;
use yii;
use yii\helpers\ArrayHelper;

class DebetWidget extends Widget
{
    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $debet = -\Yii::$app->user->identity->getContractorValue('CurrentDebt');
        $next_payment_amount = \Yii::$app->user->identity->getContractorValue('NextPaymentAmount');
        $next_payment_date = \Yii::$app->user->identity->getContractorValue('NextPaymentDate');

        if ( empty($next_payment_amount) )
            {
            $next_payment_amount = null;
            }

        if ( $next_payment_date == '0000-00-00' )
            {
            $next_payment_date = null;
            }
//        else
//            {
//            $tmp = explode('-', $next_payment_date);
//            krsort($tmp);
//            $next_payment_date = implode('-', $tmp);
//            }

        $time = 'неизвестно';
        $filename = \Yii::$app->params[ 'contractor_path' ];
        if (file_exists($filename))
            {
            $branch = (new \yii\db\Query())->select(['name','timezone'])->from(['orders_branch'])->all();
            $branch_timezone = ArrayHelper::map($branch, 'name', 'timezone');

            $DateTime = new \DateTime( );
            $DateTime->setTimestamp( filemtime($filename) );
            $DateTime->setTimezone( new \DateTimeZone($branch_timezone[Yii::$app->params['default_branch']]) );
            $time = $DateTime->format('d.m.Y / H:i');
            }

        return $this->render('debet', [ 'debet' => $debet, 'time' => $time, 'next_payment_amount' => $next_payment_amount, 'next_payment_date' => $next_payment_date ] );
    }
}