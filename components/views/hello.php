<table class="table">
    <thead><tr><th colspan="2">Здравствуйте, <?=$name;?></th></tr></thead>
    <tbody>
    <tr><td style="width: 1%;"><?=$unit?></td><td><?=$time;?></td></tr>

<?
if($unit != $msk_unit)
    {
?>
    <tr><td><?=$msk_unit?></td><td><?=$msk_time?></td></tr>
<?
    }

if ($lastlogin != '00.00.0000 / 00:00')
    {
?>
    <tr><td>Последний&nbsp;вход</td><td><?=$lastlogin?></td></tr>
<?
    }
?>
    </tbody>
</table>