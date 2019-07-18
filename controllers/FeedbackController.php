<?php

namespace app\controllers;

use app\models\Warehouse;
use Yii;
use app\models\Feedback;
use yii\helpers\HtmlPurifier;

class FeedbackController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Feedback();
        $model->u_id = Yii::$app->user->id;
        $model->date = date('Y-m-d H:i', time());

        $warehouse = Warehouse::find()->where(['unit'=>Yii::$app->user->identity->getContractorValue('Unit')])->limit(1)->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $mail_body = 'ПКК клиента: ' . Yii::$app->user->identity->getUserName() . PHP_EOL;
            $mail_body .= 'Средство связи: ' . $model->email_phone . PHP_EOL . PHP_EOL;
            $mail_body .= $model->question;
            $mail_body = nl2br( $mail_body );

            $message = Yii::$app->mailer->compose();
            $message->setFrom(Yii::$app->params['order_from_email']);
            $message->setTo(Yii::$app->params['feedback_email']);
            if (!empty(Yii::$app->params['feedback_email_copy'])) {
                $message->setBcc(Yii::$app->params['feedback_email_copy']);
            }
            $message->setSubject('Новое обращение с партнерской зоны: ' . $model->title);
            $message->setTextBody(strip_tags($mail_body));
            $message->setHtmlBody(HTMLPurifier::process($mail_body));
            $message->send();

            return $this->redirect(['index', 'id' => $model->id, 'warehouse' => $warehouse]);
        } else {
            return $this->render('index', [
                'model' => $model,
                'warehouse' => $warehouse
            ]);
        }
    }
}
