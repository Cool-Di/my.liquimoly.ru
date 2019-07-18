<?php
namespace app\models;

use Yii;
use yii\sphinx\Query;
use app\models\Products;

class Search extends \yii\db\ActiveRecord
{
	public static function query($s_query){
        $query = new Query();
   	    $rows = $query->from('yiiSearch')
       	    ->match($s_query)
           	->options([
               	'field_weights' => [
                   	'p_code' => 50,
                    'p_name' => 25,
   	            ],
       	    ])->all();
		$ids = [];
        foreach ($rows as $i){
        	$ids[] = $i['p_code'];
        }

        //$p = Products::GetNomItemByIdsFasovka($ids); // Ищем основные артикулы // По ходу теряем результаты на этом месте
        //if (sizeof($p) == 0)
        $p = Products::GetItemByIdsFasovka($ids); // Ищем любые артикулы

        return $p;
	}
}
?>