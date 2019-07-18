<?
use app\models\Products;
use app\models\Basket;
use yii\helpers\Url;
use yii\helpers\Html;

function clearName( $name )
    {
    $name = preg_replace( '@([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2}\.?[0-9]{0,2})\.?@', '', $name );
    $name = preg_replace( '!\s++!u', ' ', $name );
    $name = trim( $name );
    return $name;
    }

$this->registerCssFile( '/css/item.css' );
$this->registerCssFile( '/js/dropit/dropit.css' );
$this->registerCssFile( '/js/ui/jquery-ui.min.css' );

$basket = Basket::GetBasket();
?>
    <div class="information_basket">
        <div class="i_exit"><a onclick="$('.information_basket').hide();" href="javascript://">X</a></div>
        <div class="i_td">Товар: <span id="i_code"></span> в количестве: <span id="i_count"></span> шт. добавлен в <a
                href="/index.php/basket/">корзину</a>!
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="product">
<?
$url = '/index.php/catalog/index';
foreach ( $category as $b )
    {
    if ( !( $b[ 'selected' ] ) )
        {
        $url .= '/' . $b[ 'alias' ];
?>
            <a href="<?= $url ?>"><?= clearName( $b[ 'name' ] ) ?></a> /
<?
        }
    else
        {
        $sel_name = clearName( $b[ 'name' ] );
?>
            <b><?= $sel_name ?></b>
<?
        }
    }
switch ( $item[ 'av' ] )
    {
    case 0:
        $av = '<span style="color: #F40000">нет на складе'.($item[ 'av_date' ] != '00.00.0000'?' до ' . $item['av_date']:'').'</span>';
        break;
    case 1:
        $av = '<span style="color: #F7AC11">мало</span>';
        break;
    case 2:
        $av = '<span style="color: #52A350">много</span>';
        break;
    }
if ( $item[ 'av' ] === null )
    {
    $av = '<span style="color: #F7AC11">нет данных</span>';
    }
?>
                        <h1 class="item_h1"><?= $item[ 'name_rus' ] ?></h1>
                        <div class="item_img">
                            <? if ( !empty( $item[ 'photo' ] ) )
                                { ?>
                                    <img src="http://liquimoly.ru/catalogue_images/thumbs/<?= $item[ 'photo' ] ?>">
                                <? }
                            else
                                { ?>
                                    <img src="/images/nophoto_300x300.gif">
                                <? } ?>
                            <div class="i_card" align="left">
                                <p>На складе: <?= $av ?><br/>
                                    Цена:
                                    <b><?= !empty( $item[ 'RetailPrice' ] ) ? $item[ 'RetailPrice' ] . ' руб.' : 'по запросу'; ?></b>
<?php
if (\Yii::$app->user->can('basket') && !($item['av'] === '0' && Yii::$app->params['disable_backorder'] == true ))
{
?>                                    <input id="count_<?= $item[ 'code' ] ?>" class="p_count" name="count" type="number"
                                           value="<?= isset( $basket[ $item[ 'code' ] ] ) ? $basket[ $item[ 'code' ] ] : 1 ?>">шт.
                                    <input
                                        onclick="AddBasket('<?= $item[ 'code' ] ?>', $( document.getElementById('count_<?= $item[ 'code' ] ?>')).val());"
                                        type="submit" value="В корзину"><?php
    }
?></p>
                            </div>
                        </div>
                        <div class="item_desc">
                            <div align="right">Арт.: <b><?= $item[ 'code' ]; ?></b>, цена:
                                <b><?=($item['RetailPrice']?$item['RetailPrice'].' руб.':'по запросу')?></b><br/>Наличие
                                на складе: <?= $av ?></div>
<?
                            $quantity_list = Products::get_quantity( $item[ 'name_ger' ] );
                            if ( sizeof( $quantity_list ) > 1 )
                                {
                                ?>
                                    <ul class="dropit_div">
                                        <li>
                                            <a class="dropit_a item_volume_show_toggle"
                                               href="#"><?= $item[ 'quantity_name' ] . ' ' . ( $item[ 'value' ] + 0 ) . ' ' . $item[ 'unit' ] ?></a>
                                            <ul>
                                                <?
                                                foreach ( $quantity_list as $quantity )
                                                    {
                                                    ?>
                                                        <li>
                                                            <a href="/index.php/catalog/item/<?= $quantity[ 'code' ] ?>">Арт. <?= ( !empty( $quantity[ 'code' ] ) ? $quantity[ 'code' ] : $item[ 'code' ] ) . ': ' . $quantity[ 'value_unit' ] ?></a>
                                                        </li>
                                                        <?
                                                    }
                                                ?>
                                            </ul>
                                        </li>
                                    </ul>
                                    <?
                                }
                            else
                                {
?>
                                    <div
                                        align="right"><?= $item[ 'quantity_name' ] . ' ' . ( $item[ 'value' ] + 0 ) . ' ' . $item[ 'unit' ]; ?></div>
<?
                                }

                            $properties = preg_replace( "@\n@ui", '<br />', $item[ 'properties' ] );
                            $primenenit = $item[ 'product_usage' ] . '<br />' . $item[ 'application' ];
                            if ( $primenenit == '<br />' )
                                {
                                $primenenit = '';
                                }
                            $documentation = '';
                            if ( !empty( $item[ 'rst_refusal_text' ] ) )
                                {
                                $documentation .= $item['rst_refusal_file'] ?
                                    Html::a($item[ 'rst_refusal_text' ],$item['rst_refusal_file'],['rel'=>'nofollow']):
                                    $item[ 'rst_refusal_text' ];
                                $documentation .= '<br>';
                                }
                            if (!empty( $item['sez_data'] ))
                                {
                                $documentation .= $item['sez_file'] ?
                                    Html::a($item['sez_data'],$item['sez_file'],['rel'=>'nofollow']):
                                    $item['sez_data'];
                                $documentation .= '<br>';
                                }
                            if ( !empty( $item[ 'description_file' ] ) )
                                {
                                $documentation .= '<a href="http://liquimoly.ru/catalogue_files' . $item[ 'description_file' ] . '">Техническое описание</a><br />';
                                }
                            if ( !empty( $item[ 'passport_file' ] ) )
                                {
                                $documentation .= '<a href="http://liquimoly.ru/catalogue_files' . $item[ 'passport_file' ] . '">Паспорт безопасности</a><br />';
                                }
                            if ( !empty( $item[ 'photo' ] ) )
                                {
                                $documentation .= '<a href="http://liquimoly.ru/catalogue_files/Foto/' . $item[ 'photo' ] . '">Скачать изображение в высоком качестве</a><br />';
                                }

                            $peer_list = Products::get_peer_list( $item[ 'name_ger' ], $item[ 'value' ], $item[ 'code' ] );
?>
                            <div style="clear: both;"></div>
                            <p><?= $item[ 'description' ] ?></p>
                            <p><?= $item[ 'access' ] ?></p>
                            <div id="tabs">
                                <ul>
                                    <? if ( !empty( $properties ) )
                                        { ?>
                                            <li><a href="#tabs-1">Свойства</a></li><? } ?>
                                    <? if ( !empty( $primenenit ) )
                                        { ?>
                                            <li><a href="#tabs-2">Применение</a></li><? } ?>
                                    <? if ( sizeof( $quantity_list ) > 0 )
                                        { ?>
                                            <li><a href="#tabs-3">Фасовка</a></li><? } ?>
                                    <? if ( !empty( $documentation ) )
                                        { ?>
                                            <li><a href="#tabs-4">Документация</a></li><? } ?>
                                    <li><a href="#tabs-5">Логистика</a></li>
                                    <? if ( count($peer_list) ) {?>
                                    <li><a href="#tabs-6">Аналоги</a></li>
                                    <? } ?>
                                </ul>
                                <div id="tabs-1">
                                    <?= $properties; ?>
                                </div>
                                <div id="tabs-2">
                                    <?= $primenenit; ?>
                                </div>
                                <div id="tabs-3">
                                    <?
                                    foreach ( $quantity_list as $quantity )
                                        {?>
                                            Артикул:
                                            <b><?= Html::a( ( !empty( $quantity[ 'code' ] ) ? $quantity[ 'code' ] : $item[ 'code' ] ), Url::toRoute( [ 'catalog/item/' . ( !empty( $quantity[ 'code' ] ) ? $quantity[ 'code' ] : $item[ 'code' ] ) ] ) ); ?></b>,  <?=$quantity[ 'name' ]?>: <b><?=$quantity[ 'value_unit' ]?></b><br/>
                                    <?
                                        }
                                    ?>
                                </div>
                                <div id="tabs-4">
                                    <?= $documentation; ?>
                                </div>
                                <div id="tabs-5">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><b>Артикул</b></td>
                                            <td>
                                                <b style="text-transform: capitalize;"><?= $item[ 'quantity_name' ] ?></b>
                                            </td>
                                            <td><b>Вес брутто</b></td>
                                            <td><b>Вес нетто</b></td>
                                            <td><b>Кол. в упаковке</b></td>
                                            <td><b>Кол. на поддоне</b></td>
                                        </tr>
                                        <tr>
                                            <td><?=$item[ 'code' ] ?></td>
                                            <td><?=!empty( $item[ 'value'            ] ) ? ( $item[ 'value' ] + 0 . ' ' . $item[ 'unit' ] ) : '&mdash;'?></td>
                                            <td><?=!empty( $item[ 'gross_weight'     ] ) ? ( $item[ 'gross_weight' ] + 0 ) . ' кг' : '&mdash;'?></td>
                                            <td><?=!empty( $item[ 'net_weight'       ] ) ? ( $item[ 'net_weight' ] + 0 ) . ' кг' : '&mdash;'?></td>
                                            <td><?=!empty( $item[ 'quantity_packing' ] ) ? ( $item[ 'quantity_packing' ] . ' шт.' ) : '&mdash;'?></td>
                                            <td><?=!empty( $item[ 'quantity_pallet'  ] ) ? $item[ 'quantity_pallet' ] . ' шт.' : '&mdash;'?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="tabs-6">
                                    <?
                                    foreach ( $peer_list as $peer_code )
                                        {
                                        ?>
                                            Артикул: <b><?=Html::a( $peer_code['code'], Url::toRoute(['catalog/item/'.$peer_code['code_url']]) );?></b><br/>
                                        <?
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>

<?
$script = <<< JS
  $( function() {
    $( "#tabs" ).tabs();
    $('.dropit_div').dropit({beforeShow: function(){
            $( ".dropit_a" ).removeClass("item_volume_show_toggle");
            $( ".dropit_a" ).addClass( "item_volume_show_toggle_up" );
        }, beforeHide: function(){
            $( ".dropit_a" ).removeClass("item_volume_show_toggle_up");
            $( ".dropit_a" ).addClass( "item_volume_show_toggle" );
        }});
  } );
JS;
$this->registerJsFile( '@web/js/dropit/dropit.js', [ 'depends' => 'yii\web\JqueryAsset' ] );
$this->registerJsFile( '@web/js/ui/jquery-ui.min.js', [ 'depends' => 'yii\web\JqueryAsset' ] );
$this->registerJsFile( '@web/js/basket.js', [ 'depends' => 'yii\web\JqueryAsset' ] );
$this->registerJs( $script, yii\web\View::POS_END );