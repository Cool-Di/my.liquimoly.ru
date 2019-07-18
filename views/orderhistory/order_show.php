<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

$this->registerCssFile('/css/item.css');
$this->registerCssFile('/css/catalog.css');

$this->title = 'Информация о заказе № ' . $order_info->id;
$this->params['breadcrumbs'][] = ['label'=>'Список заказов','url'=>['index'] ];
$this->params['breadcrumbs'][] = $this->title;

if ( strlen( $is_saved ) ) // Показываем сообщение
    {
?>
<div class="col-sm-4 col-md-2">
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-check"></i> <?= $is_saved ?>
    </div>
</div>
<?
    }
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?=$title?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
<?
if ( $is_order_user )
    {

?>
            <p><b>ПКК:</b> <?=$contractor_array['Code']?></p>
            <p><b>Название:</b> <?=$contractor_array['Name']?></p>
<?
    }
?>
            <p><b>Контактный телефон:</b> <?=$order_info->phone?></p>
            <p><b>Адрес доставки:</b> <?=$order_info->address_delivery?></p>
            <p><b>Желаемая дата доставки:</b> <?=Yii::$app->formatter->asDate( $order_info->shipment, 'dd.MM.yyyy')?></p>
            <p><b>Тип оплаты:</b> <?=Yii::$app->list->pay_type[$order_info->pay_type]?></p>
            <p><b>Тип доставки:</b> <?=Yii::$app->list->delivery_type[$order_info->delivery_tipe]?></p>
            <p><b>Комментарии к заказу:</b> <?=$order_info->desc_order?></p>
<?
if ( $is_order_user )
    {
    if ( count( $product ) )
        {
?>
                <p style="text-transform: uppercase;"><?=Html::a('Скачать в формате Excel (продукция)',['download','order_id' => $order_info->id])?></p>
<?
        }
    if ( count( $reklama ) )
        {
?>

                <p style="text-transform: uppercase;"><?=Html::a('Скачать в формате Excel (реклама)',['download','order_id' => $order_info->id,'reklama' => 1])?></p>
<?
        }
?>
                    <? $form = ActiveForm::begin(); ?>
                    <?= $form->field( $order_info, 'o_type' )->dropDownList(['wait'=>'Ожидает подтверждения', 'cancel'=>'Отменен', 'confirm'=>'Подтверждено, готовится', 'wait_pay'=>'Ожидает оплаты', 'send'=>'Отправлен', 'success'=>'Закрыт']);?>
                    <?= Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-primary' ] ) ?>
                    <? ActiveForm::end(); ?>
                        <br>
<?
    }
?>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Состав заказа. Итого: <?=$total?> &#8381;</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
			<div style="max-width: 900px;" class="all_products">
<?
$all_sum = 0;
if ( count( $product ) )
    {
?>
        <div class="box-header with-border">
            <h3 class="box-title">Основной каталог</h3>
        </div>
<?
    foreach ( $product as $p )
        {
        $count_s = isset( $basket[ $p[ 'code' ] ] ) ? $basket[ $p[ 'code' ] ] : 1;
        $all_sum += $p[ 'RetailPrice' ] * $count_s;
        $type    = $p[ 'quantity_name' ];
        if ( $type == 'mass' ) // todo с этим, наверное, что-то нужно делать
            {
            $type_str = 'кг';
            }
        else if ( $type == 'number' )
            {
            $p[ 'volume' ] = 1;
            $type_str      = 'шт.';
            }
        else
            {
            $type_str = 'л';
            }
        $p[ 'volume' ] += 0;
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
						<a class="p_link" href="/index.php/catalog/item/<?=str_replace('/','_',$p['code'])?>"><?=$p['name_rus']?></a>
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

                                <?
                                if ($order_info->o_type == 'rough')
                                    {
                                    ?>
                                        <p>Цена: <b><?=($p['RetailPrice']?$p['RetailPrice'].' руб.':'по запросу')?></b>
                                            <input id="count_<?=$p['code']?>" art_id="<?=$p['code']?>" class="p_count" name="count" type="number" value="<?=isset($basket[$p['code']])?$basket[$p['code']]:1?>">шт.</p>
                                        <?
                                    }
                                else
                                    {
                                    ?>
                                        <p>Цена: <b><?=($orders_price[$p[ 'code' ]]?0+$orders_price[$p[ 'code' ]].' руб.':'по запросу')?></b>
                                            x <?=isset($basket[$p['code']])?$basket[$p['code']]:1?> шт.</p>
                                        <?
                                    }
                                ?>
							</div>
							<div id="fasovka_<?=$p['id'];?>"></div>
						</div>
					</div>
				</div>
<?
        }
    }
if( count( $reklama ) )
    {
?>
        <div style="clear:both;"></div>
        <div class="box-header with-border" style="margin-top:25px;">
            <h3 class="box-title">Рекламный каталог</h3>
        </div>
<?
    foreach ( $reklama as $p )
        {
        $count_s = isset( $basket[ $p[ 'code' ] ] ) ? $basket[ $p[ 'code' ] ] : 1;
        $all_sum += $p[ 'RetailPrice' ] * $count_s;
        $type    = $p[ 'quantity_name' ];
        if ( $type == 'mass' )
            {
            $type_str = 'кг';
            }
        else if ( $type == 'number' )
            {
            $p[ 'volume' ] = 1;
            $type_str      = 'шт.';
            }
        else
            {
            $type_str = 'л';
            }
        $p[ 'volume' ] += 0;
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
                    <a class="p_link" href="/index.php/catalog/item/<?=str_replace('/','_',$p['code'])?>"><?=$p['name_rus']?></a>
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
                            <?
                            if ($order_info->o_type == 'rough')
                                {
                                ?>
                                    <p>Цена: <b><?=($p['RetailPrice']?$p['RetailPrice'].' руб.':'по запросу')?></b>
                                        <input id="count_<?=$p['code']?>" art_id="<?=$p['code']?>" class="p_count" name="count" type="number" value="<?=isset($basket[$p['code']])?$basket[$p['code']]:1?>">шт.</p>
                                    <?
                                }
                            else
                                {
                                ?>
                                    <p>Цена: <b><?=($orders_price[$p[ 'code' ]]?0+$orders_price[$p[ 'code' ]].' руб.':'по запросу')?></b>
                                        x <?=isset($basket[$p['code']])?$basket[$p['code']]:1?> шт.</p>
                                    <?
                                }
                            ?>
                        </div>
                        <div id="fasovka_<?=$p['id'];?>"></div>
                    </div>
                </div>
            </div>
            <?
        }
    }
if (!count($product) && !count( $reklama ) )
    {
    $empty = true;
    echo 'Заказ не найден';
    }

if (!$empty && $order_info->o_type == 'rough')
    {
?>
            <div style="clear: both;"></div>
            <div align="right">
            	<hr />
				<p>
					<input id="btn_update" onclick="UpdateOrder(<?=$order_info->id;?>)" type="submit" value="Пересчитать заказ"><span class="price">Итого: <b><span id="summa"><?=$all_sum;?></span> руб.</b></span>
				</p>
            </div>

			<?= $this->render('_form', ['model' => $model]) ?>

<?
    }
?>
			</div>
            </div>
		</div>
	</div>
</div>
Итого: <?=$total?> &#8381;
<?
$this->registerJsFile('@web/js/catalog.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/basket.js', ['depends' => 'yii\web\JqueryAsset']);
?>