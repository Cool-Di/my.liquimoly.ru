<?
$this->registerCssFile('/css/order.css');

//use yii\widgets\ListView;
use yii\grid\GridView;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Заказы</h3>
            </div>
            <div class="box-body">
            <div class="table-responsive">
            <?
	            $dataProvider->pagination->pageSize = 10;
            ?>
		    <?= GridView::widget([
		        'dataProvider' => $dataProvider,
				'columns' => [
	    	        [
						'label' => 'Номер заказа',
						'attribute' => 'id',
						'format' => 'raw',
						'value' => function ($model) {
							return '<a href="/index.php/ordershow/show/'.$model['id'].'">'.$model['id'].'</a>';
						}
	    	        ],
	    	        [
						'label' => 'Количество позиций',
						'attribute' => 'count',
	    	        ],
	    	        [
						'label' => 'Желаемая дата отгрузки',
	    	    	    'attribute' => 'shipment',
	    	   		],
	    	        [
						'label' => 'Тип оплаты',
	    	    	    'attribute' => 'pay_type',
	    	    	    'value' => function ($model) {
	    	    	    	return Yii::$app->list->pay_type[$model['pay_type']];
	    	    	    }
	    	   		],
	    	        [
						'label' => 'Тип доставки',
	    	    	    'attribute' => 'delivery_tipe',
	    	    	    'value' => function ($model) {
	    	    	    	return Yii::$app->list->delivery_type[$model['delivery_tipe']];
	    	    	    }
	    	   		],
	    	        [
						'label' => 'Телефон',
	    	    	    'attribute' => 'phone',
	    	   		],
	    	        [
						'label' => 'Сумма заказа',
	    	    	    'attribute' => 'summa',
	    	    	    'value' => function ($model) {
	    	    	    	if (!empty($model['summa']))
		    	    	    	return $model['summa'].' руб.';
		    	    	    else return null;
	    	    	    }
	    	   		],
	    	        [
						'label' => 'Текущий статус',
	    	    	    'attribute' => 'wait',
	    	    	    'value' => function ($model) {
	    	    	    	return Yii::$app->list->o_type[$model['o_type']];
	    	    	    }
	    	   		]
		        ],
		        'layout' => '{items}<div style="clear:both;"></div>{pager}',
		    ]); ?>
            </div>
            </div>
		</div>
	</div>
</div>