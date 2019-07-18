<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_2015".
 *
 * @property integer $id
 * @property string $guid
 * @property string $parent_guid
 * @property string $name
 * @property string $html_body
 * @property integer $lvl
 * @property integer $sortby
 * @property integer $to_del
 * @property string $alias
 */
class Categories extends \yii\db\ActiveRecord
{
	// Получить всех потомков, заданной категории
	public static function getChildCategoryByAlias($alias){
		$parent_id = self::getIdByAlias($alias);
//		$cat_all = Categories::find()->select('`c`.*, COUNT(`p`.`id`) AS `items_count`, COUNT(`ch_c`.`id`) AS `ch_cat_items`')->from('`categories_2015` AS `c`')->
//		LeftJoin('`products` AS `p`', '`p`.`category` = `c`.`guid` AND `p`.is_visible = 1 AND `p`.show_product = 1')->
//		LeftJoin('`categories_2015` AS `ch_c`', '`ch_c`.`parent_guid` = `c`.`guid`')->
//		where(['=', '`c`.parent_guid', $parent_id])->
//		andWhere(['<>', '`c`.alias', ''])->
//        andWhere(['`c`.visible'=>1])->
//		groupBy('`c`.`guid`')->
//		Having('items_count > 0 OR ch_cat_items > 0')->
//		orderBy(['`c`.name'=>SORT_ASC]);
        $cat_all = Categories::find()->select('`c`.*')->from('`categories_2015` AS `c`')->
            where(['=', '`c`.parent_guid', $parent_id])->
            andWhere(['<>', '`c`.alias', ''])->
            andWhere(['`c`.visible'=>1])->
            andWhere(['>','`total`',0])->
            orderBy(['`c`.name'=>SORT_ASC]);
		$cat_all_array = $cat_all->asArray()->all();

		return $cat_all_array;
	}

	/* Используется для вывода меню */
	public static function getChildCategoryById($parent_id){
//		$cat_all = Categories::find()->select('`c`.*, COUNT(`p`.`id`) AS `items_count`, COUNT(`ch_c`.`id`) AS `ch_cat_items`')->from('`categories_2015` AS `c`')->
//		LeftJoin('`products` AS `p`', '`p`.`category` = `c`.`guid` AND `p`.is_visible = 1 AND `p`.show_product = 1')->
//		LeftJoin('`categories_2015` AS `ch_c`', '`ch_c`.`parent_guid` = `c`.`guid`')->
//		where(['=', '`c`.parent_guid', $parent_id])->
//		andWhere(['<>', '`c`.alias', ''])->
//        andWhere(['`c`.visible'=>1])->
//		groupBy('`c`.`guid`')->
//		Having('items_count > 0 OR ch_cat_items > 0')->
//		orderBy(['`c`.name'=>SORT_ASC]);
        $cat_all = Categories::find()->select('`c`.*')->from('`categories_2015` AS `c`')->
            where(['=', '`c`.parent_guid', $parent_id])->
            andWhere(['<>', '`c`.alias', ''])->
            andWhere(['`c`.visible'=>1])->
            andWhere(['>','`total`',0])->
            orderBy(['`c`.sortby'=>SORT_ASC,'`c`.name'=>SORT_ASC]);
		$cat_all_array = $cat_all->asArray()->all();

		return $cat_all_array;
	}

	public static function getParent($guid){
		$guid_array = self::getAllParentById($guid);

		$cat_all = Categories::find()->where(['guid' => $guid_array])->andWhere(['<>', 'alias', ''])->orderBy(['name'=>SORT_ASC])->orderBy(['lvl'=>SORT_ASC]);
		$cat_all_array = $cat_all->asArray()->all();

		return $cat_all_array;
	}

	private function getAllChildrenFromArrat($gid, $all_cat_array, &$result){
		foreach ($all_cat_array as $cat){
			if ($cat['parent_guid'] == $gid){
            	$result[] = $cat['guid'];
            	self::getAllChildrenFromArrat($cat['guid'], $all_cat_array, $result);
			}
		}
		return $result;
	}

	private function getAllParentFromArrat($gid, $all_cat_array, &$result){
		foreach ($all_cat_array as $cat){
			if ($cat['guid'] == $gid){
            	$result[] = $cat['parent_guid'];
            	self::getAllParentFromArrat($cat['parent_guid'], $all_cat_array, $result);
			}
		}
		return $result;
	}

	public static function getAllParentById($gid){
		$all_cat_array = Categories::find()->Where(['<>', 'alias', ''])->asArray()->all();
		$all_children_id[] = $gid;
		return self::getAllParentFromArrat($gid, $all_cat_array, $all_children_id);
	}

	public static function getAllChildrenById($alias){
		$all_cat_array = Categories::find()->Where(['<>', 'alias', ''])->asArray()->all();
		$gid = self::getIdByAlias($alias);
		$all_children_id = [];
		return self::getAllChildrenFromArrat($gid, $all_cat_array, $all_children_id);
	}

	public static function getIdByAlias($alias){
    	$category = Categories::findOne(['alias' => $alias]);
    	return $category->guid;
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_2015';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'html_body', 'lvl', 'sortby', 'to_del', 'alias'], 'required'],
            [['id', 'lvl', 'sortby', 'to_del', 'visible', 'total'], 'integer'],
            [['html_body'], 'string'],
            [['guid', 'parent_guid', 'name', 'alias', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'parent_guid' => 'Parent Guid',
            'name' => 'Name',
            'html_body' => 'Html Body',
            'lvl' => 'Lvl',
            'sortby' => 'Sortby',
            'to_del' => 'To Del',
            'alias' => 'Alias',
            'visible' => 'Visible',
            'total' => 'Total'
        ];
    }
}
