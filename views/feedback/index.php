<?php
/* @var $this yii\web\View */
$id = Yii::$app->request->get('id');
$user = Yii::$app->user->identity;

$personal_manager_fio = $user->getContractorValue('ManagersSurname') . ' ' . $user->getContractorValue('ManagersName') . ' ' . $user->getContractorValue('ManagersPatronymic');
$personal_manager_phone = $user->getContractorValue('ManagersPhone');
$personal_manager_email = $user->getContractorValue('ManagersMail');
?>
<div class="row">
    <div class="col-md-8">
		<div class="box">
	        <div class="box-body">
	        	<?if (!empty($id)){?><p><b>Ваше сообщение успешно отправлено! Обращению присвоен номер: <?=$id?></b></p><?}?>
			    <?= $this->render('_form', [
        			'model' => $model,
		    	]) ?>
		    </div>
	    </div>
    </div>
	<div class="col-md-4">
		<div class="box">
			<div class="box-body">
<?php
if ( isset( $warehouse ) )
    {
?>
        <p><b>Персональный менеджер</b><br />
            <?=$personal_manager_fio;?><br />
            <?=$personal_manager_phone;?><br />
            <a href="mailto:<?=$personal_manager_email;?>"><?=$personal_manager_email;?></a><br />
        </p>
<?php
    }
?>
			<p><b>Общие вопросы по работе партнерской зоны:</b><br />
			Чернядьев Леонид <a href="mailto:l.chernyadyev@liquimoly.ru">l.chernyadyev@liquimoly.ru</a></p>
			<p><b>Вопросы по PR:</b><br />
			Вахитов Эльдар <a href="mailto:eldar@liquimoly.ru">eldar@liquimoly.ru</a></p>
			<p><b>Вопросы по рекламной поддержке:</b><br />
			Подгаец Марси <a href="mailto:marsy@liquimoly.ru">marsy@liquimoly.ru</a></p>
			<p><b>Впоросы по тех. поддержке:</b><br />
			Исаченков Алексей <a href="mailto:a.isachenkov@liquimoly.ru">a.isachenkov@liquimoly.ru</a></p>
			<p><b>Юридические вопросы:</b><br />
			Бояркова Евгения <a href="mailto:law@liquimoly.ru">law@liquimoly.ru</a></p>
			<p><b>Вопросы по оплате и документооборту</b><br />
			Клепикова Елена <a href="mailto:e.klepikova@liquimoly.ru">e.klepikova@liquimoly.ru</a></p>
<?php
if ( isset( $warehouse ) )
    {
?>
            <p><b><?php echo $warehouse->name;?></b></p>
            <?php echo $warehouse->description;?>
            <?php echo $warehouse->map;?>
<?
    }
?>
			</div>
		</div>
	</div>
</div>