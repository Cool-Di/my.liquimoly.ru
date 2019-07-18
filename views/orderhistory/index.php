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
if ( count( $contractor_info ) )
    {
?>
            <b>Текущая задолженность</b>: <?=$contractor_info['CurrentDebt']?> руб.
<?
    }
?>
		    <?= GridView::widget([
		        'dataProvider' => $dataProvider,
				'columns' => [
	    	        [
						'label' => 'Номер заказа',
						'attribute' => 'id',
						'format' => 'raw',
						'value' => function ($model) {
							return '<a href="/index.php/orderhistory/show/'.$model['id'].'">'.$model['id'].'</a>';
						}
	    	        ],
	    	        [
						'label' => 'Желаемая дата отгрузки',
	    	    	    'attribute' => 'date',
	    	   		],
                    [
                        'label' => 'Название',
                        'attribute' => 'name',
                        'visible' => $is_show_other
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
                        'label' => 'Подразделение',
                        'attribute' => 'unit',
                        'visible' => $is_show_other
                    ],
	    	        [
						'label' => 'Сумма заказа',
	    	    	    'attribute' => 'summa',
	    	    	    'value' => function ($model) {
	    	    	    	if (!empty($model['summa']))
		    	    	    	return number_format( $model[ 'summa' ], 0, '.' ,' ' ).' руб.';
		    	    	    else return null;
	    	    	    }
	    	   		],
                    [
                        'label' => 'Дата заказа',
                        'attribute' => 'date_send'
                    ],
	    	        [
						'label' => 'Текущий статус',
	    	    	    'attribute' => 'wait',
	    	    	    'value' => function ($model) {
	    	    	    	return Yii::$app->list->o_type[$model['o_type']];
	    	    	    }
	    	   		],
	    	   		[
						'label' => 'Действие',
						'format' => 'raw',
						'value' => function ($model) {
							$str = '<a alt="Повторить заказ" title="Повторить заказ" onclick="if (!confirm(\'Повторить заказ?\')) return false;" href="/index.php/orderhistory/copy/'.$model['id'].'"><i class="fa fa-clone" aria-hidden="true"></i></a>';
							$str .= ' &middot; <a alt="Скопировать в корзину" title="Скопировать в корзину" onclick="if (!confirm(\'Скопировать в корзину?\')) return false;" href="/index.php/orderhistory/copybasket/'.$model['id'].'"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
							if (in_array($model['o_type'], ['wait']))
								$str .= ' &middot; <a alt="Отменить заказ" title="Отменить заказ" onclick="if (!confirm(\'Подтвердить отмену заказа?\')) return false;" href="/index.php/orderhistory/cancel/'.$model['id'].'"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
							return $str;
						},
                        'visible' => !$is_show_other
					]
		        ],
		        'layout' => '{items}<div style="clear:both;"></div>{pager}',
                'rowOptions' => function($model){
		            if ($model['o_type'] == 'wait')
                        {
                        return ['class'=>'bg-warning'];
                        }
                    },
                'tableOptions' => [
                    'class' => 'table table-bordered'
                ],
		    ]); ?>
            </div>
            </div>
		</div>
	</div>
</div>