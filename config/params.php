<?php

return [
    'adminEmail'            => 'admin@w7.ru',                       // не используется
    'order_from_email'      => 'info@forum.liquimoly.ru',           // Адрес исходящей почты
    'order_default_email'   => 'zakaz@liquimoly.ru',                // Адрес для заказов по-умолчанию
    'feedback_email'        => 'info@liquimoly.ru',                 // Адрес для обратной связи
    'feedback_email_copy'   => 'l.chernyadyev@liquimoly.ru',        // Адрес для копии письма обратной связи
    'contractor_path'       => '/home/1cimport/contractors.xml',    // Путь к файлу с данными контрагентов (из 1С)
    'default_branch'        => 'Москва',                            // Филиал по-умолчанию
    'disable_backorder'     => true,                                // Отключить кнопку "Купить" для отсутствующего товара
    'soap_username'         => 'WebAdmin2',
    'soap_password'         => 'hDod5bhz',
    'soap_wsdl'             => 'OrderStatus.wsdl'
];
