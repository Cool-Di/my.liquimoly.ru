<?
use yii\helpers\Url;
use app\models\Categories;
use app\models\Products;

if ( Yii::$app->controller->id == 'catalog' && ( $item_id = Yii::$app->request->get( 'item_id' ) ) )
    {
    $item = Products::GetItemById( $item_id );
    $cat  = Categories::getParent( $item[ 'category' ] );
    }

$cat_all_array = Categories::getChildCategoryById( '00000000-0000-0000-0000-000000000000' );
$user          = Yii::$app->user->identity;

$role_alias = Yii::$app->authManager->getRolesByUser( Yii::$app->user->getId() );

$array_url = explode( '/', Url::to( '' ) );

foreach ( $cat_all_array as $cat_item )
    {
    $sel        = $array_url[ 4 ] == $cat_item[ 'alias' ] || isset( $cat[ 0 ][ 'alias' ] ) && $cat[ 0 ][ 'alias' ] == $cat_item[ 'alias' ] ? true : false;
    $cat_menu[] = [ 'label' => $cat_item[ 'name' ],
                    'url' => [ '/catalog/index/' . $cat_item[ 'alias' ] ],
                    'active' => $sel ];
    }
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div style="font-size: 12px;" class="user-panel">
                <p style="margin-bottom: 2px; color: #FFFFFF; font-size: 16px;"><?=$user->getContractorValue('Name');?></p>
                <span style="color: #b8c7ce"><?=$role_alias[key($role_alias)]->description?></span>
        </div>

<?= dmstr\widgets\Menu::widget(
    [
        'options' => ['class' => 'sidebar-menu'],
        'items' => [
            ['label' => 'Управление yii', 'visible' => YII_DEBUG && Yii::$app->user->can('root'), 'options' => ['class' => 'header']],
            ['label' => 'Gii', 'visible' => YII_DEBUG && Yii::$app->user->can('root'), 'icon' => 'file-code-o', 'url' => ['/gii']],
            ['label' => 'Отладка', 'visible' =>  YII_DEBUG && Yii::$app->user->can('root'), 'icon' => 'dashboard', 'url' => ['/debug']],
            ['label' => 'Навигация', 'options' => ['class' => 'header']],
            ['label' => 'Каталоги · Реклама', 'icon' => 'car', 'url' => ['/catalog'], 'items' => $cat_menu],
            ['label' => 'Акции · Новости', 'icon' => 'gift', 'url' => ['/akcii'], 'visible' => Yii::$app->user->can('akcii'), 'items' => [
                ['label' => 'Новости', 'url' => ['/news/index']],
                ['label' => 'Текущие акции', 'url' => ['/akcii/index']],
                ['label' => 'Архив акций', 'url' => ['/akcii/archiv']],
            ]],
            ['label' => 'Обучение', 'icon' => 'book', 'url' => ['/obuchenie'], 'items' => [
                ['label' => 'Презентации', 'visible' => Yii::$app->user->can('prezentacii'), 'url' => ['/prezentacii']],
                ['label' => 'Обучающее видео', 'url' => 'http://liquimoly.ru/video/tech/'],
                ['label' => 'Расписание вебинаров', 'visible' => Yii::$app->user->can('vebinar'), 'url' => ['/vebinar/index']],
                ['label' => 'Архив вебинаров', 'url' => 'http://liquimoly.ru/video/webinar/'],
                ['label' => 'Учебники', 'url' => 'http://liquimoly.ru/learn.html'],
            ]],
            ['label' => 'Реклама', 'icon' => 'television', 'url' => ['/obuchenie'], 'items' => [
                /*['label' => 'Оформление точек (новинки)', 'url' => '/albums/?dir=%D0%9E%D1%84%D0%BE%D1%80%D0%BC%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5/%D0%90%D0%BA%D1%86%D0%B8%D1%8F%20%22%D0%9A%D1%80%D0%B0%D1%81%D0%BE%D1%82%D0%B0%20%D1%81%D0%BD%D0%B0%D1%80%D1%83%D0%B6%D0%B8%22'],*/
                ['label' => 'Оформление', 'url' => '/albums/?dir=%D0%9E%D1%84%D0%BE%D1%80%D0%BC%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5'],
                /*['label' => 'Мероприятия', 'url' => ['/meropriyatiya']],*/
                ['label' => 'Материалы для загрузки', 'url' => ['/downloads']],
            ]],
            ['label' => 'Корзина', 'icon' => 'shopping-cart', 'visible' => Yii::$app->user->can('basket'), 'url' => ['/basket']],

            ['label' => 'API подбор масла', 'icon' => 'tint', 'visible' => Yii::$app->user->can('oilapi'), 'url' => ['/oilapi']],

            ['label' => 'Мои клиенты', 'icon' => 'user', 'visible' => Yii::$app->user->can('clients'), 'url' => ['/clients/index']],
            ['label' => key($role_alias)=='order_user'?'Список заказов':'Мои заказы', 'icon' => 'history', 'visible' => Yii::$app->user->can('orderhistory'), 'url' => ['/orderhistory/index']],
            ['label' => 'Контакты', 'icon' => 'phone', 'visible' => Yii::$app->user->can('feedback'), 'url' => ['/feedback']],
            ['label' => 'Склады', 'icon' => 'map', 'visible' => Yii::$app->user->can('warehouse'), 'url' => ['/warehouse']],
            ['label' => 'Прайс-листы', 'icon' => 'rub', 'visible' => true, 'url' => ['/price-list']],
            ['label' => 'Панель управления', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->can('admin')],
            ['label' => 'Акции · Новости', 'icon' => 'gift', 'visible' => Yii::$app->user->can('admin/news')||Yii::$app->user->can('admin/akcii'), 'items' => [
                ['label' => 'Список новостей', 'url' => ['/admin/news/index'], 'visible' => Yii::$app->user->can('admin/news')],
                ['label' => 'Список акций', 'url' => ['/admin/akcii/index'], 'visible' => Yii::$app->user->can('admin/akcii')],
            ]],
            ['label' => 'Презентации', 'icon' => 'object-group', 'visible' => Yii::$app->user->can('admin/prezentacii'), 'items' => [
                ['label' => 'Категории презентаций', 'url' => ['/admin/prezentaciicat']],
                ['label' => 'Список презентаций', 'url' => ['/admin/prezentacii']],
            ]],
            ['label' => 'Вебинары', 'icon' => 'youtube-play', 'visible' => Yii::$app->user->can('admin/vebinar'), 'items' => [
                ['label' => 'Расписание вебинаров', 'url' => ['/admin/vebinar/index']],
            ]],
            ['label' => 'Загрузки', 'icon' => 'download', 'visible' => Yii::$app->user->can('admin/downloadshashtag')||Yii::$app->user->can('admin/downloads'), 'items' => [
                ['label' => 'Хештеги', 'url' => ['/admin/downloadshashtag/index']],
                ['label' => 'Файлы', 'url' => ['/admin/downloads/index']],
            ]],
            ['label' => 'Мероприятия', 'icon' => 'glass', 'visible' => Yii::$app->user->can('admin/meropriyatiya'), 'items' => [
                ['label' => 'Список мероприятий', 'url' => ['/admin/meropriyatiya/index']],
            ]],
            ['label' => 'Учетные записи', 'icon' => 'key', 'visible' => Yii::$app->user->can('root'), 'items' => [
                ['label' => 'Пользователи', 'icon' => 'user', 'url' => ['/user-list']],
                ['label' => 'Роли пользователей', 'icon' => 'users', 'url' => ['/permit/access/role']],
                ['label' => 'Права доступа', 'icon' => 'shield', 'url' => ['/permit/access/permission']],
            ]],
            ['label' => 'Склады', 'icon' => 'map', 'visible' => Yii::$app->user->can('admin/warehouse'), 'url' => ['/admin/warehouse'] ],
            ['label' => 'Филиалы', 'icon' => 'envelope-open', 'visible' => Yii::$app->user->can('admin/ordersbranch'), 'url' => ['/admin/ordersbranch'] ],
            ['label' => 'Войти', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
            ['label' => 'Выйти', 'icon' => 'sign-out', 'url' => ['/site/logout'], 'visible' => !Yii::$app->user->isGuest],
        ],
    ]
) ?>

    </section>

</aside>
