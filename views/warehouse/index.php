<?php
use yii\widgets\ListView;
?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list',
    'options' => [
        'tag' => 'div',
        'class' => 'list-wrapper',
        'id' => 'list-wrapper',
    ],
    'layout' => '{items}',
]);?>
<div style="clear: both;"></div>