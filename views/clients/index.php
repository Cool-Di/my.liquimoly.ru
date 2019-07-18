<?php
	use yii\grid\GridView;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Клиенты</h3>
            </div>
            <div class="box-body">
			<?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
			        'columns' => [
					    [
				    		'attribute' => 'Code',
        	    			'filter' => '<input class="form-control" name="filtercode" value="'. $searchModel['Code'] .'" type="text">',
					       	'value' => 'Code',
					       	'label' => 'ПКК'
			            ],
					    [
				    		'attribute' => 'name',
        	    			'filter' => '<input class="form-control" name="filternamel" value="'. $searchModel['name'] .'" type="text">',
							'format' => 'raw',
							'value' => function ($model) {
								if ($model['Holding'] > 0) {
								return $model['name'];
								} else
								return '<a href="/index.php/clients/index?filterholidng='.$model['Code'].'">'.$model['name'].'</a>';
							},
					       	'label' => 'Название'
			            ],
	        			[
				    		"attribute" => "email",
        	    			'filter' => '<input class="form-control" name="filteremail" value="'. $searchModel['email'] .'" type="text">',
        	    			'value' => 'email',
				        ],
	        			[
				    		"attribute" => "item_name",
							'value' => function ($model) {
                            	return ['holding_user' => 'Холдинг', 'urlico_user' => 'Юр. лицо'][$model['item_name']];
							},
					       	'label' => 'Тип клиента'
				        ],
				        [
							'label' => 'Действие',
							'format' => 'raw',
							'value' => function ($model) {
								$str = '<a alt="Заказы клиента" title="Заказы клиента" href="/index.php/ordershow/'.$model['id'].'"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
								$str .= ' &middot; <a alt="Изменить пароль" title="Изменить пароль" onclick="if (!confirm(\'Изменить пароль?\')) return false;" href="/index.php/clients/changepasswd/'.$model['id'].'"><i class="fa fa-key" aria-hidden="true"></i></a>';
                        		return $str;
							}
				        ]
	        		],
                    'tableOptions' => ['class'=>'table table-bordered'],
                    'layout' => '{items}<div style="clear:both;"></div>{pager}',
                ]);
			?>
            </div>
		</div>
	</div>
</div>