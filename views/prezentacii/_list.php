<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;


/** @var app\models\PrezentaciiCat $model */
if ( sizeof( $model->itemsAllVisibleCategory ) > 0 )
    {
    ?>
        <h4><?= $model->name; ?></h4>
        <?
    $dataProvider = new ArrayDataProvider( [ 'key' => 'id',
                                             'allModels' => $model->itemsAllVisibleCategory,
                                             'sort' => [ 'attributes' => [ 'id',
                                                                           'title' ], ], ] );
    ?>
    <?= ListView::widget( [ 'dataProvider' => $dataProvider,
                            'itemView' => '_sub_list',
                            'layout' => '{items}<div style="clear:both;"></div>{pager}', ] ); ?>
    <? } ?>