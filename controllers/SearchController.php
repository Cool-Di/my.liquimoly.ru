<?
namespace app\controllers;
use yii;
use app\models\Search;
use app\models\Basket;

class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$s_query = Yii::$app->request->post('s_query');

		if (!empty($s_query)){
    	    $products = Search::query($s_query);
	     }
	     $basket = Basket::GetBasket();

        return $this->render('index', ['product' => $products, 'query' => $s_query, 'basket' => $basket]);
    }

}
