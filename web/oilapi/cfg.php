<?php

$cfg_auth = [
    'UserName' => 'Liquimoly_RUS',
    'Password' => 'Kc2o6FRx5p4T7Dzy',
    //'Password' => 'Yg6z5W2Xfa',
    'LanguageISO3' => 'RUS'
];

$cfg_soap = [
    'url' => 'http://olyslager-customerAPI.lubricantinformation.com/OlyslagerAPI.asmx?WSDL',
    //'url' => 'http://olyslager-customerapi.lubsadvisor.com/OlyslagerAPI.asmx?WSDL',
    'options' => [
        'soap_version' => SOAP_1_2,
        'encoding' => 'UTF-8'
    ]
];

$cfg_json = ['data' => ['clid', 'operation', 'format', 'prefix', 'id', 'text']];

$cfg_request = [
    'search' => [
        'alias' => 'types',
        'method' => 'GetTypeListFromSearch',
        'request_param' => [
            'SearchText' => 'text',
            'CategoryID' => 'id',
            'BuildYear' => 'year'
        ],
        'alt' => 'Поиск'
    ],
    'categories' => [
        'method' => 'GetCategoryList',
        'request_param' => [
        ],
        'alt' => 'Категория'
    ],
    'makes' => [
        'method' => 'GetMakeList',
        'request_param' => [
            'CategoryID' => 'id'
        ],
        'alt' => 'Марка'
    ],
    'models' => [
        'method' => 'GetModelList',
        'request_param' => [
            'MakeID' => 'id'
        ],
        'alt' => 'Модель'
    ],
    'types' => [
        'method' => 'GetTypeList',
        'request_param' => [
            'ModelID' => 'id'
        ],
        'alt' => 'Тип'
    ],
    'recommendations' => [
        'method' => 'TypeID2Recommendation',
        'request_param' => [
            'Type' => 'id'
        ],
        'alt' => 'Выбранные параметры'
    ]
];

return [
    'auth' => $cfg_auth,
    'soap' => $cfg_soap,
    'request' => $cfg_request,
    'json' => $cfg_json
];
