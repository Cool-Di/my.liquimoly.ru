<?php
use yii\helpers\Html;
use app\models\Basket;

/* @var $this \yii\web\View */
/* @var $content string */
$b_count = Basket::CountBasket();
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img src="/images/logo.png"></span><span class="logo-lg"><img src="/images/logo.png"> ' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <form action="/index.php/search/index" method="post">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <div class="input-group input-group-sm" style="padding: 10px 20px">
                            <input type="text" name="s_query" class="form-control" placeholder="Поиск по каталогу">
                            <span class="input-group-btn" style="width: auto;"><button class="btn btn-info btn-flat" type="submit">Найти</button></span>
                        </div>
                    </form>
                </li>
				<?if (Yii::$app->user->can('basket')){?>
                <li class="dropdown tasks-menu">
                    <a href="/index.php/basket/" class="dropdown-toggle">
                        <i class="fa fa-shopping-cart"></i>
                        <span id="basket_count" class="label label-danger"><?=$b_count?></span>
                    </a>
                </li>
				<?}?>
                <li class="dropdown tasks-menu">
                    <a href="/index.php/site/logout" class="dropdown-toggle">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
