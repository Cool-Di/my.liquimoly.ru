<div class="col-lg-4">
    <div class="box">
        <table class="table">
            <thead>
            <tr>
                <th>
                    <?php echo $model->name;?>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php echo $model->description;?>
                </td>
            </tr>
            <tr>
                <td style="margin: auto;text-align: center">
                    <?php echo $model->map;?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php echo (($index+1)%3==0?'<div class="clearfix visible-lg-block"></div>':'');?>