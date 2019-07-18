<?
$this->registerCssFile( '/css/item.css' );
$this->registerCssFile( '/css/catalog.css' );
?>
<div class="information_basket">
    <div class="i_exit"><a onclick="$('.information_basket').hide();" href="javascript://">X</a></div>
	<div class="i_td">Товар: <span id="i_code"></span> в количестве: <span id="i_count"></span> шт. добавлен в <a href="/index.php/basket/">корзину</a>!</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Корзина товаров <span data-toggle="modal" data-target="#excel_upload" style="margin-left: 20px;border-bottom: 1px dashed grey;font-size: 14px;cursor: pointer">Подгрузить из Excel-файла</span></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
			<div style="max-width: 900px;" class="all_products">
<?
$all_sum = 0;
$reklama = false;
if ( sizeof( $product ) > 0 )
    {
    foreach ( $product as $p )
        {
        $reklama = $reklama || (boolean) $p[ 'reklama' ];
        $count_s = isset( $basket[ $p[ 'code' ] ] ) ? $basket[ $p[ 'code' ] ] : 1;
        $all_sum += $p[ 'RetailPrice' ] * $count_s;

        $type_str = 'л';
        switch ( $p[ 'quantity_name' ] )
            {
            case 'mass':
                $type_str = 'кг';
                break;
            case 'number':
                $type_str      = 'шт.';
                $p[ 'volume' ] = 1;
                break;
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
						<a class="p_link" href="/index.php/catalog/item/<?=$p['code_url']?>"><?=$p['name_rus']?></a>
						<div style="width:100%;">
							<div class="p_info">
							<p>Артикул: <b><?=$p['code']?></b><?=!empty($p['volume']) ? ', фасовка: <b>'.$p['volume'].' '.$type_str.'</b>' : ''?>, в упаковке: <b><?=$p['quantity_packing'];?> шт.</b><br />
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
	                        	<p>Цена: <b><?=($p['RetailPrice']?$p['RetailPrice'].' руб.':'по запросу')?></b>
	                        	<input id="count_<?=$p['code']?>" art_id="<?=$p['code']?>" class="p_count" name="count" type="number" value="<?=isset($basket[$p['code']])?$basket[$p['code']]:1?>">шт.</p>
							</div>
							<div id="fasovka_<?=$p['id'];?>"></div>
						</div>
					</div>
				</div>
<?
        }
    }
else
    {
    $empty = true;
    echo 'Корзина пуста';
    }
if ( !$empty )
    {
?>
            <div style="clear: both;"></div>
            <div align="right">
            	<hr style="margin-top: 5px;">
				<p>
					<input id="btn_update" onclick="UpdateBasket()" type="submit" value="Пересчитать корзину"><span class="price">Итого: <b><span id="summa"><?=$all_sum;?></span> руб.</b></span>
				</p>
            </div>
<?
    if ( $reklama )
        {
?>
            <div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" style="text-decoration: none;">×</a>Рекламная продукция оплачивается из собственных средств клиента</div>
<?
        }
?>


    <?= $this->render('_form', ['model' => $model, 'address' => $address]) ?>

<?
    }
?>
			</div>
            </div>
		</div>
	</div>
</div>
<?
$this->registerJsFile( '@web/js/catalog.js', [ 'depends' => 'yii\web\JqueryAsset' ] );
$this->registerJsFile( '@web/js/basket.js', [ 'depends' => 'yii\web\JqueryAsset' ] );

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div id="excel_upload" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="font-size:28px;">&times;</button>
                <h4 class="modal-title">Выберите Excel-файл для загрузки</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                <p><?= $form->field($excel, 'excelFile')->fileInput() ?></p>
                <p>&nbsp;</p>
                <p><?= Html::submitButton('Загрузить в корзину', ['class' => 'btn btn-primary']) ?></p>
                <?php ActiveForm::end() ?>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button-->
                <a href="/downloads/lm_example.xlsx">Скачать пример excel-файла</a>
            </div>
        </div>
    </div>
</div>
