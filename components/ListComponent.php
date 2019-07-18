<?

namespace app\components;

use yii\base\Object;

class ListComponent extends Object
    {
    public $month_name = [ '1'  => 'Январь',
                           '2'  => 'Февраль',
                           '3'  => 'Март',
                           '4'  => 'Апрель',
                           '5'  => 'Май',
                           '6'  => 'Июнь',
                           '7'  => 'Июль',
                           '8'  => 'Август',
                           '9'  => 'Сентябрь',
                           '10' => 'Октябрь',
                           '11' => 'Ноябрь',
                           '12' => 'Декабрь', ];

    public $akcii_type = [ 0 => 'Активная',
                           1 => 'Архив', ];

    public $showme = [ 0 => 'Нет',
                       1 => 'Да', ];

//    public $order_email = [ 'Питер'        => 'spb@liquimoly.ru',
//                            'Воронеж'      => 'vrn@liquimoly.ru',
//                            'Екатеринбург' => 'ekb@liquimoly.ru',
//                            'Новосибирск'  => 'zakaz.nsk@liquimoly.ru',
//                            'Краснодар'    => 'krasnodar@liquimoly.ru',
//                            'Красноярск'   => 'zakaz.krsk@liquimoly.ru',
//                            'Самара'       => 'samara@liquimoly.ru',
//                            'Москва'       => 'zakaz@liquimoly.ru', ];

//    public $unit_timezone = [   'Питер'        => 'Europe/Moscow',
//                                'Воронеж'      => 'Europe/Moscow',
//                                'Екатеринбург' => 'Asia/Yekaterinburg',
//                                'Новосибирск'  => 'Asia/Novosibirsk',
//                                'Краснодар'    => 'Europe/Moscow',
//                                'Красноярск'   => 'Asia/Krasnoyarsk',
//                                'Самара'       => 'Europe/Samara',
//                                'Москва'       => 'Europe/Moscow' ];

//    public $unit_timezone = [   'Питер'        => '+3',
//                                'Воронеж'      => '+3',
//                                'Екатеринбург' => '+5',
//                                'Новосибирск'  => '+7',
//                                'Краснодар'    => '+3',
//                                'Красноярск'   => '+7',
//                                'Самара'       => '+4',
//                                'Москва'       => '+3', ];

    public $delivery_type = ['self'         => 'самовывоз',
                             'delivery_boy' => 'доставка',];

    public $pay_type = [ 'simple'  => 'упрощенная',
                         'no_cash' => 'безналичная',
                         'cash'    => 'наличная с ПКО (чек)',
                         'promo'   => 'рекламная продукция', ];

    public $o_type = [ 'rough'         => 'Черновик',
                       'wait'          => 'Ожидает подтверждения',
                       'cancel'        => 'Отменен',
                       'confirm'       => 'Подтверждено, готовится',
                       'wait_pay'      => 'Ожидает оплаты',
                       'send'          => 'Отправлен',
                       'success'       => 'Закрыт',
                       '1c_processing' => 'В обработке (а.с.)',
                       '1c_reserved'   => 'Товар зарезервирован (а.с.)',
                       '1c_assembly'   => 'Товар в наборе (а.с.)',
                       '1c_shipped'    => 'Товар отгружен (а.с.)',];
    }