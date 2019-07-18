<?
	$this->registerCssFile('/css/item.css');
	$this->registerCssFile('/css/catalog.css');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Состав заказа</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
			<div style="max-width: 900px;" class="all_products">
			<?
				$all_sum = 0;
				if (sizeof($product) > 0){
					foreach ($product as $p){
					$count_s = isset($basket[$p['code']])?$basket[$p['code']]:1;
					$all_sum += $p['RetailPrice']*$count_s;
					$type = $p['quantity_name'];
					if ($type == 'mass'){
						$type_str = 'кг';
					} else if ($type == 'number'){
						$p['volume'] = 1;
						$type_str = 'шт.';
					} else {
						$type_str = 'л';
					}
					$p['volume'] += 0;
			?>
				<div class="product">
					<div style="width: 100px" class="p_img">
						<?if (!empty($p['photo'])){?>
							<img width="100" height="100" src="http://liquimoly.ru/catalogue_images/thumbs/<?=$p['photo']?>">
						<?}else{?>
							<img width="100" height="100" src="/images/nophoto_300x300.gif">
						<?}?>
					</div>
					<div style="max-width: 750px;" class="p_text">
						<a class="p_link" href="/index.php/catalog/item/<?=$p['code_url']?>"><?=$p['name_rus']?></a>
						<div style="width:100%;">
							<div class="p_info">
							<p>Артикул: <b><?=$p['code']?></b><?=(!empty($p['volume'])) ? ', фасовка: <b>'.$p['volume'].' '.$type_str.'</b>' : '';?>, в упаковке: <b><?=$p['quantity_packing'];?> шт.</b><br />
							<?
                            switch( $p['av'] )
                                {
                                case 0: $av = '<span style="color: #F40000">нет на складе'.($p[ 'av_date' ] != '00.00.0000'?' до ' . $p['av_date']:'').'</span>'; break;
                                case 1: $av = '<span style="color: #F7AC11">мало</span>'; break;
                                case 2: $av = '<span style="color: #52A350">много</span>'; break;
                                }

                            if( $p['av'] === null )
                                {
                                $av = '<span style="color: #F7AC11">нет данных</span>';
                                }
							?>
							Наличие на складе: <b><?=$av?></b></p>
							</div>
							<div class="p_order">
	                        	<p>Цена: <b><?=($p['RetailPrice']?$p['RetailPrice'].' руб.':'по запросу')?></b> x
								<?if ($order_info->o_type == 'rough'){?>
								<?=isset($basket[$p['code']])?$basket[$p['code']]:1?> шт. &mdash; <?=$p['RetailPrice']*(isset($basket[$p['code']])?$basket[$p['code']]:1)?> руб.
								<?}else{?> x <?=isset($basket[$p['code']])?$basket[$p['code']]:1?> шт.</p><?}?>
							</div>
							<div id="fasovka_<?=$p['id'];?>"></div>
						</div>
					</div>
				</div>
			<?
				}
				} else {
					$empty = true;
					echo 'Заказ не найден';
				}
				if (!$empty && $order_info->o_type == 'rough'){
			?>
            <div style="clear: both;"></div>
            <div align="right">
            	<hr />
				<p>
					<span class="price">Итого: <b><span id="summa"><?=$all_sum;?></span> руб.</b></span>
				</p>
            </div>
			<?
				}
			?>
			</div>
            </div>
		</div>
	</div>
</div>
<?
	$this->registerJsFile('@web/js/catalog.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/basket.js', ['depends' => 'yii\web\JqueryAsset']);
?>