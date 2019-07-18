<?
use yii\helpers\Url;
use yii\helpers\Html;

/** @var string $username */
?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">ПКК: <?=$username?></h3>
                </div>
                <div class="error">Ошибка смена пароля</div>
            </div>
        </div>
    </div>
<?=Html::a( 'Вернуться', Url::previous() );?>