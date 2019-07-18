<?php

namespace app\controllers;
use app\models\Categories;
use app\models\Products;

class CatalogController extends \yii\web\Controller
{
	public function actionItem($item_id){
		$item = Products::GetItemById($item_id);
		$cat = Categories::getParent($item['category']);
		return $this->render('item', ['item' => $item, 'category' => $cat]);
	}

    public function actionIndex($category_0, $category_1='', $category_2='', $category_3='', $category_4='')
    {
    	$query = Categories::find();
    	$lvl = 0;

    	if (!empty($category_0)){
	    	$category_sel = $category_0;
	    	$cat_bread[0]['alias'] = $category_0;
	    	$query->orWhere(['=', 'alias', $category_0]);
	    	$lvl++;
	    }
    	if (!empty($category_1)){
	    	$category_sel = $category_1;
	    	$cat_bread[1]['alias'] = $category_1;
	    	$query->orWhere(['=', 'alias', $category_1]);
	    	$lvl++;
	    }
    	if (!empty($category_2)){
	    	$category_sel = $category_2;
	    	$cat_bread[2]['alias'] = $category_2;
	    	$query->orWhere(['=', 'alias', $category_2]);
	    	$lvl++;
	    }
    	if (!empty($category_3)){
	    	$category_sel = $category_3;
	    	$cat_bread[3]['alias'] = $category_3;
	    	$query->orWhere(['=', 'alias', $category_3]);
	    	$lvl++;
	    }
    	if (!empty($category_4)){
	    	$category_sel = $category_4;
	    	$cat_bread[4]['alias'] = $category_4;
	    	$query->orWhere(['=', 'alias', $category_4]);
	    	$lvl++;
	    }

	    $cat_bread_name = $query->asArray()->all();

	    foreach($cat_bread_name as $b_name){
        	if ($category_0 == $b_name['alias']){
            	$cat_bread[0]['name'] = $b_name['name'];
            	if ($category_sel == $b_name['alias'])
	            	$cat_bread[0]['selected'] = true;
	            else $cat_bread[0]['selected'] = false;
        	}
        	if ($category_1 == $b_name['alias']){
            	$cat_bread[1]['name'] = $b_name['name'];
            	if ($category_sel == $b_name['alias'])
	            	$cat_bread[1]['selected'] = true;
	            else $cat_bread[1]['selected'] = false;
        	}
        	if ($category_2 == $b_name['alias']){
            	$cat_bread[2]['name'] = $b_name['name'];
            	if ($category_sel == $b_name['alias'])
	            	$cat_bread[2]['selected'] = true;
	            else $cat_bread[2]['selected'] = false;
        	}
        	if ($category_3 == $b_name['alias']){
            	$cat_bread[3]['name'] = $b_name['name'];
            	if ($category_sel == $b_name['alias'])
	            	$cat_bread[3]['selected'] = true;
	            else $cat_bread[3]['selected'] = false;
        	}
        	if ($category_4 == $b_name['alias']){
            	$cat_bread[4]['name'] = $b_name['name'];
            	if ($category_sel == $b_name['alias'])
	            	$cat_bread[4]['selected'] = true;
	            else $cat_bread[4]['selected'] = false;
        	}
	    }

		$cat_id = Categories::getIdByAlias($category_sel);
		$children_id = Categories::getAllChildrenById($category_sel);

		$categories_view_list[] = $cat_id;

        if ( sizeof( $children_id ) > 0 && $lvl > 2 )
            {
            $categories_view_list = array_merge( $categories_view_list, $children_id );
            }

        $product = Products::getNomGroupByIDs( $categories_view_list );

        $cat = Categories::getChildCategoryByAlias($category_sel);

        foreach( $cat as &$item )
            {
            if ( $item['image'] == '' )
                {
                $item['image'] = '/images/nophoto_300x300.gif';
                }
            else
                {
                $item['image'] = '/images/catalog_icon/' . $item['image'];
                }
            }

        return $this->render('index', ['category_name' => $category_sel, 'category' => $cat, 'cat_bread' => $cat_bread, 'product' => $product]);
    }
}
