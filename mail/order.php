<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $contractor array */
/* @var $total integer */
?>
<html>
<body>
<h2><?= Html::a('Информация о заказе № ' . $model->id, yii\helpers\Url::to(['orderhistory/show', 'order_id' => $model->id], true)) ?></h2>
<p>
    ПКК: <?= $contractor['Code'] ?><br>
    Название: <?= $contractor['Name'] ?><br>
    Контактный телефон: <?= $model->phone ?><br>
    Адрес доставки: <?= $model->address_delivery ?><br>
    Желаемая дата доставки: <?= Yii::$app->formatter->asDate($model->shipment, 'dd.MM.yyyy') ?><br>
    Тип оплаты: <?= Yii::$app->list->pay_type[$model->pay_type] ?><br>
    Тип доставки: <?= Yii::$app->list->delivery_type[$model->delivery_tipe] ?><br>
    Комментарии к заказу: <?= $model->desc_order ?><br>
</p>
<p>Итого: <?= $total ?> &#8381;</p>

<?php

if ( $dataProvider->getTotalCount() )
    {
?>
<h2>Основной каталог</h2>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name_rus:text:Наименование',
            ['attribute'=>'quantity','label'=>'Фасовка','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'code','label'=>'Артикул','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'count','label'=>'Количество','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'price','label'=>'Цена','format'=>'text','contentOptions' => ['align' => 'right']],
        ],
        'tableOptions' => ['cellspacing' => '0', 'cellpadding' => '4', 'border' => '1'],
        'layout' => '{items}']);
    }
?>

<?php
if ($promoProvider->getTotalCount())
    {
?>
<h2>Рекламный каталог</h2>
    <?php echo GridView::widget([
        'dataProvider' => $promoProvider,
        'columns' => [
            'name_rus:text:Наименование',
            ['attribute'=>'quantity','label'=>'Фасовка','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'code','label'=>'Артикул','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'count','label'=>'Количество','format'=>'text','contentOptions' => ['align' => 'center']],
            ['attribute'=>'price','label'=>'Цена','format'=>'text','contentOptions' => ['align' => 'right']],
        ],
        'tableOptions' => ['cellspacing' => '0', 'cellpadding' => '4', 'border' => '1'],
        'layout' => '{items}']);
    }
?>
</body>
</html>