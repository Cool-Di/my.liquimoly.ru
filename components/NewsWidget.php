<?php
namespace app\components;


use app\models\News;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class NewsWidget extends Widget
{
    public $limit;

    public function init()
    {
        parent::init();
        if ($this->limit === null) {
            $this->limit = 5;
        }
    }

    public function run()
    {
        $dataProvider = new ActiveDataProvider(['query'=>News::findBySql('SELECT * FROM news WHERE news_showme = 1 ORDER BY news_id DESC LIMIT ' . $this->limit)]);

        return $this->render('news',['dataProvider'=>$dataProvider]);
    }
}