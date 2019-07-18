<table class="table">
    <thead><tr><th colspan="2">Финансовая информация</th></tr></thead>
    <tbody>
    <tr><td style="width: 1%;">Дебет</td><td><?=number_format( $debet, 2, ',', ' ');?> руб.</td></tr>
<?php
if ( !is_null( $next_payment_amount ) )
    {
?>
    <tr><td>Сумма&nbsp;платежа</td><td><?=number_format( $next_payment_amount, 2, ',', ' ');?> руб.</td></tr>
<?
    }
?>
<?php
if ( !is_null( $next_payment_date ) )
    {
?>
    <tr><td>Дата&nbsp;очередного&nbsp;платежа</td><td><?=date('d.m.Y',strtotime($next_payment_date));?></td></tr>
<?
    }
    ?>
    <tr><td>Дата обновления</td><td><?=$time?></td></tr>
    </tbody>
</table>