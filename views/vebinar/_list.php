<?php
use yii\helpers\Html;
?>
<tr>
    <td><?= $model->year ?></td>
    <td><?= Yii::$app->list->month_name[ $model->mouth ] ?></td>
    <td><?= $model->title ?></td>
    <td class="td_hide"><?= $model->date ?></td>
    <td class="td_hide"><?= Html::a( $model->link, $model->link, [ 'target' => '_blank' ] ) ?></td>
    <td class="td_hide"><?= $model->video_link ? Html::a( Html::img( '/images/YouTube-icon-full_color.png', [ 'width' => '35px' ] ), $model->video_link, [ 'target' => '_blank' ] ) : '' ?></td>
</tr>