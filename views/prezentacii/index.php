<?php
	use yii\helpers\Html;
	use yii\widgets\ListView;
	use yii\helpers\Url;
?>
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-body">
					<style>
						.pr {
							width: 220px;
							text-align: center;
							float: left;
							margin-right: 15px;
							margin-bottom: 15px;
							height: 200px;
						}
						h4 {
							clear: both;
							margin-bottom: 25px;
						}
					</style>
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
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->