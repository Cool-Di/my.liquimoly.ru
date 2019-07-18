<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.12.2017
 * Time: 13:55
 */

class LmDb
{
    const
        ERROR_DB_QUERY = 'Ошибка запроса к БД';

    private static $instance = null;
    private $connect = null;
    private $config = null;
    private $error = null;

    private $dbCfg = __DIR__ . DIRECTORY_SEPARATOR . 'db.php';

    private function __construct()
    {
        try {

            LmUtil::applyConfiguration($this->config, $this->dbCfg);

            $dsn = LmUtil::getVars($this->config, 'dsn');
            $user = LmUtil::getVars($this->config, 'user');
            $password = LmUtil::getVars($this->config, 'password');

            try {

                $this->connect = new PDO($dsn, $user, $password);

            } catch (PDOException $e) {

                LmException::createLmException('Нет подключения к БД', $e->getMessage());

            }
        } catch (LmException $e) {

            $e->registerException();

        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPDOConnection()
    {
        return $this->connect;
    }

    public function getError()
    {
        return $this->error;
    }

    public function doQuery($query, $type, $format = null)
    {
        $result = null;
        try {
            switch ($type) {
                case 'fetch':
                    $result = $this->connect->query($query);
                    $result = ($result !== false) ? $result->fetch($format)
                        : LmException::createLmException(self::ERROR_DB_QUERY, $query);

                    break;
                case 'fetchAll':
                    $result = $this->connect->query($query);
                    $result = ($result !== false) ? $result->fetchAll($format)
                        : LmException::createLmException(self::ERROR_DB_QUERY, $query);

                    break;
                case 'exec':
                    $result = $this->connect->exec($query);
                    $result = ($result !== false) ? $result
                        : LmException::createLmException(self::ERROR_DB_QUERY, $query);
                    break;

            }

        } catch (PDOException $e) {

            LmException::createLmException($e->getMessage());
        } catch (LmException $e) {

            $this->error = $e->getMessage();
            $e->registerException();

        }

        return ($result === false) ? null : $result;
    }

    public function getClientProfile($clientId)
    {
        $tableName = 'oilapi_clients';

        $query = "SELECT * FROM " . $tableName . " WHERE token='" . $clientId . "'";

        return $this->doQuery($query, 'fetch', PDO::FETCH_ASSOC);
    }

    public function updateClientProfile($clientId, $val)
    {
        $tableName = 'oilapi_clients';

        $query = "UPDATE " . $tableName .
            " SET `limit` = `limit` - " . $val . ", `update_time` = '" . date('Y-m-d H:i:s') . "' WHERE token='" . $clientId . "'";

        $this->doQuery($query, 'exec');
    }

    public function getLinkProducts($codeList, $clientId)
    {
        $codes = "'" . implode("','", $codeList) . "'";

        $query = "SELECT `codes`.`g_code`, `links`.`r_code`, `links`.`link` 
                  FROM `oilapi_links` links
                  LEFT JOIN `oilapi_clients` clients ON clients.id = links.id_client
                  LEFT JOIN `oilapi_codes` codes ON (codes.r_code = links.r_code)
                  WHERE links.status = 1 AND codes.g_code IN (" . $codes . ") AND `clients`.`token`='" . $clientId . "'";

        return $this->doQuery($query, 'fetchAll', PDO::FETCH_ASSOC);
    }

    public function getDataProductsByVendorCode($item_ids)
    {
        $in_item_ids = "'" . implode("','", $item_ids) . "'";
        $photoPath = 'http://liquimoly.ru/catalogue_images/thumbs/';

        $query = "
    	SELECT
			`p`.code, `p`.name_rus AS name, CONCAT('" . $photoPath . "',`p`.photo) AS photo, `p`.description
		FROM
			`products` p
		LEFT JOIN `id_art_old` old ON old.real_code = p.code
		LEFT JOIN `products` AS `p_osn_nom` ON (`p_osn_nom`.`name_ger` = `p`.`nom_group` AND `p_osn_nom`.`osn_nom` = 1) OR (`p`.`code` = `p`.`nom_group`)
		WHERE `p`.`code` IN ($in_item_ids) 
        ORDER BY p.code";

        return $this->doQuery($query, 'fetchAll', PDO::FETCH_ASSOC);
    }

    public function getRequestData($request)
    {
        $result = null;

        $key = $this->getKeyFromRequest($request);

        $tableName = 'oilapi_' . LmUtil::getVars($request, 'operation');


        $query = "SELECT jsondata FROM " . $tableName . " WHERE `keycode`='" . $key . "'";

        return $this->doQuery($query, 'fetch', PDO::FETCH_COLUMN);
    }

    public function setRequestData($json, $request)
    {
        $key = $this->getKeyFromRequest($request);

        $tableName = 'oilapi_' . LmUtil::getVars($request, 'operation');


        $query = "INSERT INTO " . $tableName . " (keycode, jsondata, updatetime) 
                  VALUES (" . $this->connect->quote($key) . ", " . $this->connect->quote($json) . ", "
            . $this->connect->quote(date('Y-m-d H:i:s')) . ") " .
            "ON DUPLICATE KEY UPDATE keycode=" . $this->connect->quote($key);

        $this->doQuery($query, 'exec');
    }

    private function getKeyFromRequest($request)
    {
        $operation = LmUtil::getVars($request, 'operation');
        $id = LmUtil::getVars($request, 'id');
        $text = LmUtil::getVars($request, 'text');

        return LmUtil::getKey([$operation, $id, $text]);
    }

    public function getCssClient($clientId)
    {
        $query = "SELECT a.class_name, CONCAT_WS(': ', b.property_name, c.css_value) AS val
            FROM oilapi_css_value c
            LEFT JOIN oilapi_clients clients ON clients.id = c.id_client
            LEFT JOIN oilapi_css_class a ON a.type = c.type_class
            LEFT JOIN oilapi_css_property b ON b.type = c.type_property
            WHERE  token='" . $clientId . "'";

        return $this->doQuery($query, 'fetchAll', PDO::FETCH_COLUMN | PDO::FETCH_GROUP);
    }

}