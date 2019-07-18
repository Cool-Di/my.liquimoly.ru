<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use SoapClient;

class SyncOrdersController extends Controller
    {
    public function actionSync()
        {
        $this->stdout( 'SYNC START' . PHP_EOL );

        $id_list = Yii::$app->db->createCommand("SELECT `id` FROM `orders` WHERE  `date_send` >= NOW() - INTERVAL 1 WEEK" )->queryColumn();
        $id_list_string = implode(',', $id_list);

        $this->stdout( 'total: ' . count($id_list) . PHP_EOL );
        $this->stdout( $id_list_string . PHP_EOL );

        $wsdl = Yii::getAlias('@app/config/1c/' . Yii::$app->params['soap_wsdl']);
        $client = new SoapClient($wsdl,[
            'login'         => Yii::$app->params['soap_username'],
            'password'      => Yii::$app->params['soap_password'],
            'soap_version'  => SOAP_1_2,
            'cache_wsdl'    => WSDL_CACHE_MEMORY
            ]);
        $request = new \stdClass();
        $request->OrderNumberList = $id_list_string;
        $response = $client->ReturnOrderStatus($request);

        if ( !isset( $response->return->orders ) )
            {
            $this->stdout( 'empty result list' . PHP_EOL );
            return Controller::EXIT_CODE_ERROR;
            }

        $status_match = [
            'В обработке'           => '1c_processing',
            'Товар зарезервирован'  => '1c_reserved',
            'Товар в наборе'        => '1c_assembly',
            'Товар отгружен'        => '1c_shipped'
            ];

        foreach( $response->return->orders as $index => $value )
            {
            if (!isset($status_match[ $value->status ]))
                {
                $this->stdout( 'Unknown: ' . $value->status . PHP_EOL );
                }
            if ( isset( $value->order_number ) && isset( $value->status ) && isset($status_match[ $value->status ] ) )
                {
                $row_count = Yii::$app->db->createCommand()->update('orders',
                    ['o_type' => $status_match[ $value->status ] ],
                    ['id'=>$value->order_number]
                    )->execute();
                $this->stdout( $value->order_number . ' => ' . $status_match[ $value->status ] . ' => ' . $row_count . PHP_EOL );
                }
            }

        $this->stdout( 'EXIT_CODE_NORMAL' . PHP_EOL );

        return Controller::EXIT_CODE_NORMAL;
        }
    }