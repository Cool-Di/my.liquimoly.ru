<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form ActiveForm */
$this->registerCssFile('/css/order.css');
?>
<div class="views-order-_form">

    <?php $form = ActiveForm::begin(); ?>

		<?
		if ($model->shipment == '0000-00-00' || empty($model->shipment)) $model->shipment = date('d.m.Y', time());
		else $model->shipment = date('d.m.Y', strtotime($model->shipment));
		echo $form->field($model, 'shipment')->widget(DateTimePicker::classname(), [
		'options' => ['placeholder' => 'Enter event time ...'],
		'type' => DateTimePicker::TYPE_INPUT,
		'pluginOptions' => [
			'autoclose' => true,
			'minView' => 2,
			'format' => 'dd.mm.yyyy'
		]
		]);
		?>

        <?= $form->field($model, 'pay_type')->dropDownList(Yii::$app->list->pay_type, ['id' => 'pay_type_sel', 'onchange' => 'hide_promo_alert();' ] ) ?>
        <?= $form->field($model, 'delivery_tipe')->dropDownList( Yii::$app->list->delivery_type , ['id' => 'delivery_sel', 'onchange'=>'set_delivery_type();']) ?>

<?
if ( $address->getTotalCount() )
    {
?>
    <?= $form->field($model, 'address_delivery',['template'=> "{label}<span data-toggle=\"modal\" data-target=\"#delivery_list\" style=\"margin-left: 20px;border-bottom: 1px dashed grey;font-size: 12px;cursor: pointer\">Выбрать адрес из списка</span>\n{input}\n{hint}\n{error}"])->textArea(['rows' => '6'])?>
<?
    }
else
    {
?>
    <?= $form->field($model, 'address_delivery')->textArea(['rows' => '6'])?>
<?
    }
?>

        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'desc_order')->textArea(['rows' => '6']) ?>

        <div class="form-group">
            <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><!-- views-order-_form -->
<div id="delivery_list" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="font-size:28px;">&times;</button>
                <h4 class="modal-title">Выберите адрес доставки</h4>
            </div>
            <div class="modal-body">
<?php echo GridView::widget([
                            'dataProvider' => $address,
                            'columns' => ['address'],
                            'layout' => '{items}',
                            'showHeader' => false,
                            'rowOptions' => ['class' => 'address_item','style'=>'cursor: pointer;']
                            ]);  ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<?
	$this->registerJsFile('@web/js/order_form.js', ['depends' => 'yii\web\JqueryAsset']);
?>
