<?
use yii\widgets\ListView;

?>
<style>
    .a_img {
        min-width: 175px;
        text-align: left;
        float: left;
    }
    .a_desc {
        max-width: 475px;
        float: left;
    }
    .d_action {
        float: left;
        max-width: 700px;
        margin-bottom: 25px;
        margin-right: 25px;
    }
    @media screen and (max-width: 641px) {
        .a_desc, .a_img {
            text-align: center;
        }
        .a_img {
            width: 100%;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Акции</h3><!-- · <img src="/images/file_pdf.png" width="24"> <a href="/uploads/akcii/_calendar_.pdf" target="_blank">Календарь акций</a-->
            </div><!-- /.box-header -->
            <div class="box-body">
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
</div>