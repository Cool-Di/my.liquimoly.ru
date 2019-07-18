<?php
use yii\widgets\ListView;
$this->title = 'Расписание вебинаров';
?>
<style>
	@media screen and (max-width: 641px) {
    	.td_hide {
    		display: none;
    	}
	}
</style>
<div class="row">
	<div class="col-md-12">
    	<div class="box">
        	<div class="box-body">
				<table class="table table-bordered">
					<tr>
                        <th>Год</th>
						<th>Месяц</th>
						<th>Тема</th>
						<th class="td_hide">Дата</th>
						<th class="td_hide">Ссылка на вход</th>
						<th class="td_hide">Запись видео</th>
					</tr>
					<?= ListView::widget([
					'dataProvider' => $dataProvider,
					'itemView' => '_list',
					'options' => [
				        'tag' => 'div',
				        'class' => 'list-wrapper',
				        'id' => 'list-wrapper',
				    ],
				    'layout' => '{items}<div style="clear:both;"></div>{pager}',
					]);?>
				</table>
			</div><!-- /.box-body -->
        </div><!-- /.box -->
	</div><!-- /.col -->
</div><!-- /.row -->