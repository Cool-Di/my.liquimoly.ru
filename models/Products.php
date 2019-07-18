<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $guid
 * @property string $code
 * @property string $nom_group
 * @property string $similar
 * @property string $category
 * @property integer $cat_order
 * @property integer $top_products
 * @property string $brand_exclusive
 * @property integer $is_new
 * @property integer $pro_line
 * @property integer $summer_line
 * @property integer $turbo_force
 * @property integer $winter_line
 * @property integer $show_product
 * @property integer $osn_nom
 * @property string $data_create
 * @property string $data_edit
 * @property string $name_rus
 * @property string $name_ger
 * @property string $description
 * @property string $properties
 * @property string $application
 * @property string $access
 * @property string $specification
 * @property string $specification_file
 * @property string $bar_code
 * @property string $customs_code
 * @property double $volume
 * @property double $gross_weight
 * @property double $net_weight
 * @property string $quantity_packing
 * @property string $quantity_pallet
 * @property string $rst_refusal_text
 * @property string $rst_refusal_file
 * @property string $sez_data
 * @property string $sez_number
 * @property string $sez_file
 * @property string $photo
 * @property string $description_file
 * @property string $book_file
 * @property string $passport_file
 * @property string $label_file
 * @property string $price
 * @property integer $price_status
 * @property string $short_description
 * @property string $product_usage
 * @property integer $available
 * @property integer $old_code
 * @property string $quantity_name
 * @property integer $is_visible
 */
class Products extends \yii\db\ActiveRecord
{
    public static function getCode( $code )
        {
        if ( is_array( $code ) )
            {
            foreach ( $code as &$item )
                {
                $item = str_replace( '_', '/', $item );
                }
            }
        else
            {
            $code = str_replace( '_', '/', $code );
            }

        return $code;
        }

    // Получить список аналогов
    public static function get_peer_list( $nom_group, $volume, $code )
        {
        $sql = "SELECT `code`, REPLACE(`code`,'/','_') as `code_url` FROM `products` WHERE `nom_group` = :nom_group AND volume = CAST(:volume AS DECIMAL(8,3)) AND code <> :code AND ( show_product = 1 OR is_visible = 1 )";
        $result = Yii::$app->db->createCommand($sql, [':nom_group' => $nom_group, ':volume' => $volume, ':code' => self::getCode($code)])->queryAll();
        $peer_list = [];
        foreach ( $result as $row )
            {
            $peer_list[] = ['code' => $row['code'], 'code_url' => $row['code_url']];
            }
        return $peer_list;
        }

    // Получить список фасовок
    public static function get_quantity( $nom_group )
        {
        $branch = Yii::$app->user->identity->getBranch();
        // todo Этот запрос можно упростить
        $quantity_query = "SELECT `p`.code, REPLACE(`p`.code,'/','_') as `code_url`,  CASE quantity_name WHEN 'mass' THEN net_weight WHEN 'volume' THEN volume WHEN 'number' THEN 1 END as 'value', CASE quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number'
	    THEN 'шт.' END as 'unit', CASE quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number' THEN 'кол.' END as 'quantity_name',
	    `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`
	    FROM products AS `p`
   		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND  goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
	    WHERE nom_group = :nom_group AND `main_product` = 1 AND ( show_product = 1 OR is_visible = 1 ) GROUP BY CONCAT(value, quantity_name) ORDER BY value ASC";
        $quantity_query = Yii::$app->db->createCommand( $quantity_query, [ ':nom_group' => $nom_group, ':branch' => $branch ] )->queryAll();
        $quantity_list  = [];

        foreach ( $quantity_query as $row )
            {
            $quantity_list[] = [ 'code'       => $row[ 'code' ],
                                 'value_unit' => ( $row[ 'value' ] + 0 ) . ' ' . $row[ 'unit' ],
                                 'name'       => $row[ 'quantity_name' ] ];
            }

        return $quantity_list;
        }

    // todo Скрыть товары с признаком show_product = 0 или is_visible = 0
    // p_osn_nom это основной артикул в ном.группе
    // у "одиночных" артикулов он часто ошибочно равен 0 и name_ger у них так же пуст
    public static function GetItemByIdsFasovka( $item_ids )
        {
        $branch = Yii::$app->user->identity->getBranch();

        if ( sizeof( $item_ids ) == 0 )
            {
            return [];
            }

        $in_item_ids = "'" . implode( "','", self::getCode( $item_ids ) ) . "'";

/*        $query = "SELECT
			p.*, tp.test AS test_id, old.id AS old_id, `p_osn_nom`.`code` AS `osn_nom_code`, CASE p.quantity_name WHEN 'mass' THEN p.net_weight WHEN 'volume' THEN p.volume WHEN 'number' THEN 1 END as 'value',
			CASE p.quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number' THEN 'шт.' END as 'unit', CASE p.quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number'
			THEN 'кол.' END as 'quantity_name', `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`,
			(SELECT COUNT(*) FROM `products` AS `pf` WHERE `pf`.`nom_group` = `p`.`nom_group` AND `pf`.`code` NOT IN ($in_item_ids) GROUP BY `pf`.`nom_group`) AS `fasovok`, REPLACE(`p`.code,'/','_') as `code_url`, REPLACE(`p_osn_nom`.`code`,'/','_') as `osn_nom_code_url`
		FROM
			`products` p
		LEFT JOIN `tests_products` tp ON tp.product = p.code
		LEFT JOIN `id_art_old` old ON old.real_code = p.code
  		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
		INNER JOIN `products` AS `p_osn_nom`
			ON (`p_osn_nom`.`name_ger` = `p`.`nom_group` AND `p_osn_nom`.`osn_nom` = 1) OR (`p`.`code` = `p`.`nom_group`)
		WHERE
			`p`.`code` IN ($in_item_ids)
		GROUP BY `p`.`id` ORDER BY FIELD(p.code,$in_item_ids)";*/

        $query = "SELECT
			p.*, CASE p.quantity_name WHEN 'mass' THEN p.net_weight WHEN 'volume' THEN p.volume WHEN 'number' THEN 1 END as 'value',
			CASE p.quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number' THEN 'шт.' END as 'unit', CASE p.quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number'
			THEN 'кол.' END as 'quantity_name', `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`,
			(SELECT COUNT(*) FROM `products` AS `pf` WHERE `pf`.`nom_group` = `p`.`nom_group` AND `pf`.`code` NOT IN ($in_item_ids) GROUP BY `pf`.`nom_group`) AS `fasovok`, REPLACE(`p`.code,'/','_') as `code_url`
		FROM `products` p
  		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
		WHERE `p`.`code` IN ($in_item_ids)
		GROUP BY `p`.`id` ORDER BY FIELD(p.code,$in_item_ids)"; // AND ( p.show_product = 1 OR p.is_visible = 1 )

        $item = Yii::$app->db->createCommand( $query,[':branch' => $branch] )->queryAll();

        return $item;
        }

    // todo Скрыть товары с признаком show_product = 0 или is_visible = 0
    // Пытаемся найти продукты с признаком основного в номенклатурной группе,
    // или как это часто бывает этот признак равен нулю у товаров единственных в своей номенклатурной группе, где code == nom_group
    public static function GetNomItemByIdsFasovka( $item_ids )
        {
        $branch = Yii::$app->user->identity->getBranch();

        if ( sizeof( $item_ids ) == 0 )
            return [];

        $in_item_ids = "'" . implode( "','", self::getCode( $item_ids ) ) . "'";

        $query = "
    	SELECT
			p.*, tp.test AS test_id, old.id AS old_id, CASE p.quantity_name WHEN 'mass' THEN p.net_weight WHEN 'volume' THEN p.volume WHEN 'number' THEN 1 END as 'value',
			CASE p.quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number' THEN 'шт.' END as 'unit', CASE p.quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number'
			THEN 'кол.' END as 'quantity_name', `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`,
			(SELECT COUNT(*) FROM `products` AS `pf` WHERE `pf`.`nom_group` = `p`.`nom_group` AND `pf`.`code` NOT IN ($in_item_ids) GROUP BY `pf`.`nom_group`) AS `fasovok`, REPLACE(`p`.code,'/','_') as `code_url`
		FROM
			`products` p
		LEFT JOIN `tests_products` tp
			ON tp.product = p.code
		LEFT JOIN `id_art_old` old
			ON old.real_code = p.code
  		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
		WHERE
			(`p`.`osn_nom` = 1 OR (`p`.`code` = `p`.`nom_group`)) AND
			`p`.`code` IN ($in_item_ids)
		GROUP BY `p`.`id`"; // AND ( p.show_product = 1 OR p.is_visible = 1 )

        $item = Yii::$app->db->createCommand( $query, [':branch' => $branch] )->queryAll();

        return $item;
        }

    // todo Скрыть товары с признаком show_product = 0 или is_visible = 0
    // Получить данные по товарам по артикулам
    public static function GetItemByIds( $item_ids, $reklama = null )
        {
        $branch = Yii::$app->user->identity->getBranch();
        if ( sizeof( $item_ids ) == 0 )
            return [];

        $in_item_ids = "'" . implode( "','", self::getCode( $item_ids ) ) . "'";

        $query = "
    	SELECT
			p.*, tp.test AS test_id, old.id AS old_id, `p_osn_nom`.`code` AS `osn_nom_code`, CASE p.quantity_name WHEN 'mass' THEN p.net_weight WHEN 'volume' THEN p.volume WHEN 'number' THEN 1 END as 'value',
			CASE p.quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number' THEN 'шт.' END as 'unit', CASE p.quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number'
			THEN 'кол.' END as 'quantity_name', `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`, REPLACE(`p`.code,'/','_') as `code_url`, REPLACE(`p_osn_nom`.`code`,'/','_') as `osn_nom_code_url`
		FROM
			`products` p
		LEFT JOIN `tests_products` tp ON tp.product = p.code
		LEFT JOIN `id_art_old` old ON old.real_code = p.code
  		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND  goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
		LEFT JOIN `products` AS `p_osn_nom` ON (`p_osn_nom`.`name_ger` = `p`.`nom_group` AND `p_osn_nom`.`osn_nom` = 1) OR (`p`.`code` = `p`.`nom_group`)
		WHERE `p`.`code` IN ($in_item_ids) " . (!is_null($reklama)?' AND `p`.`reklama` = '.intval($reklama):'') ."
		GROUP BY `p`.`id`
        ORDER BY FIELD(p.code,$in_item_ids)"; // AND ( p.show_product = 1 OR p.is_visible = 1 )

        $item = Yii::$app->db->createCommand( $query, [':branch' => $branch] )->queryAll();

        return $item;
        }

    // todo Скрыть товары с признаком show_product = 0 или is_visible = 0
	// Получить данные по товару по его артикулу
    public static function GetItemById( $item_id )
        {
        $branch = Yii::$app->user->identity->getBranch();
        $query = "SELECT
			p.*, tp.test AS test_id, old.id AS old_id, `p_osn_nom`.`code` AS `osn_nom_code`, CASE p.quantity_name WHEN 'mass' THEN p.net_weight WHEN 'volume' THEN p.volume WHEN 'number' THEN 1 END as 'value',
			CASE p.quantity_name WHEN 'mass' THEN 'кг' WHEN 'volume' THEN 'л' WHEN 'number' THEN 'шт.' END as 'unit', CASE p.quantity_name WHEN 'mass' THEN 'вес' WHEN 'volume' THEN 'объем' WHEN 'number'
			THEN 'кол.' END as 'quantity_name', `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`, REPLACE(`p`.code,'/','_') as `code_url`, REPLACE(`p_osn_nom`.`code`,'/','_') as `osn_nom_code_url`
		FROM
			`products` p
		LEFT JOIN `tests_products` tp
			ON tp.product = p.code
		LEFT JOIN `id_art_old` old
			ON old.real_code = p.code
  		LEFT JOIN `goods_branch` ON goods_branch.Code = p.code AND goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = p.code
		LEFT JOIN `products` AS `p_osn_nom`
			ON `p_osn_nom`.`name_ger` = `p`.`nom_group` AND `p_osn_nom`.`osn_nom` = 1
		WHERE
			`p`.`code` = :item_id
		GROUP BY `p`.`id`"; // AND ( p.show_product = 1 OR p.is_visible = 1 )

        $item = Yii::$app->db->createCommand( $query, [ ':item_id' => self::getCode( $item_id ), ':branch' => $branch ] )->queryOne();

        return $item;
        }

 	// Получить товары по основной номенклатуре (не включая основной в группе)
	public static function getProductsByOsnNom($nom, $code)
        {
        $branch = Yii::$app->user->identity->getBranch();

		$products = Products::find()->
            select( ['products.*', 'prices.*', 'goods_branch.Available AS av', 'DATE_FORMAT(`goods_branch`.ReceiptDate,"%d.%m.%Y") as av_date', 'REPLACE(`products`.code,\'/\',\'_\') as `code_url`' ] )->
            leftJoin( 'prices', 'prices.code = products.code' )->
            leftJoin( 'goods_branch', "goods_branch.code = products.code AND goods_branch.Branch = '$branch' " )->
		    where( [ 'nom_group' => $nom ] )->
//                andWhere( [ 'osn_nom' => 0 ] )->
// + Поскольку ajax подгрузка фасовок используется в результатах поиска нельзя отсеивать по osn_nom = 1
// + надо фильтровать по невключению самого себя
// + в $code приходил `id` товара, а не сам `code`
                andWhere( [ '<>', '`products`.`id`', self::getCode( $code ) ] )->
                andWhere( [ '`products`.main_product' => 1 ] )->
                andWhere( ['or', ['show_product' => 1], ['is_visible' => 1]] )->
            orderBy( [ 'cat_order' => SORT_ASC, 'volume' => SORT_ASC ] );
		$products_array = $products->asArray()->all();

		return $products_array;
	    }

	// Получить всех номенклатурные группы по заданной категории
	public static function getNomGroupByIDs($guid)
        {
        $branch = Yii::$app->user->identity->getBranch();
	    array_walk( $guid, function(&$val){$val = '"'.$val.'"';} );
	    $ids = implode(',',$guid);

		$query = "
		SELECT *, COUNT(*) AS `fasovok`, REPLACE(`p`.code,'/','_') as `code_url` FROM
		(SELECT
			`products`.*, `prices`.`RetailPrice`, `prices`.`BranchPrice`, `goods_branch`.`Available` AS `av`, DATE_FORMAT(`goods_branch`.ReceiptDate,'%d.%m.%Y') as `av_date`
		FROM `products`
  		LEFT JOIN `goods_branch` ON goods_branch.Code = products.code AND goods_branch.Branch = :branch
		LEFT JOIN `prices` ON prices.Code = products.code
		WHERE
			`category` IN ($ids) AND `main_product` = 1 AND ( show_product > 0 OR `is_visible` = 1 )
		ORDER BY `osn_nom` DESC)
		AS `p` GROUP BY `p`.nom_group ORDER BY `p`.cat_order ASC";

		$nom_product_array = Yii::$app->db->createCommand($query,[':branch' => $branch])->queryAll();

		return $nom_product_array;
	    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'code', 'access', 'old_code'], 'required'],
            [['nom_group', 'similar', 'description', 'properties', 'application', 'access', 'short_description', 'product_usage', 'quantity_name'], 'string'],
            [['cat_order', 'top_products', 'is_new', 'pro_line', 'summer_line', 'turbo_force', 'winter_line', 'show_product', 'osn_nom', 'price_status', 'available', 'old_code', 'is_visible'], 'integer'],
            [['data_create', 'data_edit'], 'safe'],
            [['volume', 'gross_weight', 'net_weight', 'price'], 'number'],
            [['guid', 'category', 'brand_exclusive', 'name_rus', 'name_ger', 'specification', 'specification_file', 'bar_code', 'customs_code', 'rst_refusal_text', 'rst_refusal_file', 'sez_data', 'sez_number', 'sez_file', 'photo', 'description_file', 'book_file', 'passport_file', 'label_file'], 'string', 'max' => 255],
            [['code', 'quantity_packing', 'quantity_pallet'], 'string', 'max' => 20],
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
            'code' => 'Code',
            'nom_group' => 'Nom Group',
            'similar' => 'Similar',
            'category' => 'Category',
            'cat_order' => 'Cat Order',
            'top_products' => 'Top Products',
            'brand_exclusive' => 'Brand Exclusive',
            'is_new' => 'Is New',
            'pro_line' => 'Pro Line',
            'summer_line' => 'Summer Line',
            'turbo_force' => 'Turbo Force',
            'winter_line' => 'Winter Line',
            'show_product' => 'Show Product',
            'osn_nom' => 'Osn Nom',
            'data_create' => 'Data Create',
            'data_edit' => 'Data Edit',
            'name_rus' => 'Name Rus',
            'name_ger' => 'Name Ger',
            'description' => 'Description',
            'properties' => 'Properties',
            'application' => 'Application',
            'access' => 'Access',
            'specification' => 'Specification',
            'specification_file' => 'Specification File',
            'bar_code' => 'Bar Code',
            'customs_code' => 'Customs Code',
            'volume' => 'Volume',
            'gross_weight' => 'Gross Weight',
            'net_weight' => 'Net Weight',
            'quantity_packing' => 'Quantity Packing',
            'quantity_pallet' => 'Quantity Pallet',
            'rst_refusal_text' => 'Rst Refusal Text',
            'rst_refusal_file' => 'Rst Refusal File',
            'sez_data' => 'Sez Data',
            'sez_number' => 'Sez Number',
            'sez_file' => 'Sez File',
            'photo' => 'Photo',
            'description_file' => 'Description File',
            'book_file' => 'Book File',
            'passport_file' => 'Passport File',
            'label_file' => 'Label File',
            'price' => 'Price',
            'price_status' => 'Price Status',
            'short_description' => 'Short Description',
            'product_usage' => 'Product Usage',
            'available' => 'Available',
            'old_code' => 'Old Code',
            'quantity_name' => 'Quantity Name',
            'is_visible' => 'Is Visible',
        ];
    }
}
