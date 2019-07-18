<?php
namespace app\components;


use app\models\Akcii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class AkciiWidget extends Widget
{
    public $limit;

    public function init()
    {
        parent::init();
        if ($this->limit === null) {
            $this->limit = 2;
        }
    }

    public function run()
    {
        $dataProvider = new ActiveDataProvider(['query'=>Akcii::findBySql('SELECT * FROM akcii WHERE akcii_type = 0 AND akcii_showme = 1 ORDER BY akcii_id DESC LIMIT ' . $this->limit)]);

        return $this->render('akcii',['dataProvider'=>$dataProvider]);
    }
}