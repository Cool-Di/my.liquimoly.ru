<?
use yii\helpers\Url;
use yii\helpers\Html;

/** @var string $username */
/** @var string $password */
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Пароль изменен для ПКК: <?=$username?></h3>
            </div>
            <div class="box-body"><p>Новый пароль:</p> <code><?=$password?></code></div>
        </div>
    </div>
</div>
<?=Html::a( 'Вернуться', Url::previous() );?>