<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form ActiveForm */
$this->registerCssFile('/css/order.css');
?>
<div class="views-order-_form">

    <?php $form = ActiveForm::begin(); ?>

		<?
		if ($model->shipment == '0000-00-00' || empty($model->shipment))
		    {
		    $model->shipment = date('d.m.Y', time());
            }
		else
            {
            $model->shipment = date('d.m.Y', strtotime($model->shipment));
            }
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
        <?= $form->field($model, 'pay_type')->dropDownList(Yii::$app->list->pay_type) ?>
        <?= $form->field($model, 'delivery_tipe')->dropDownList( Yii::$app->list->delivery_type, ['id' => 'delivery_sel', 'onchange'=>'set_delivery_type();']) ?>
        <?= $form->field($model, 'address_delivery')->textArea(['rows' => '6'])?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'desc_order')->textArea(['rows' => '6']) ?>

        <div class="form-group">
            <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- views-order-_form -->
<?
	$this->registerJsFile('@web/js/order_form.js', ['depends' => 'yii\web\JqueryAsset']);
?>
