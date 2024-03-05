<?php

namespace App\Core\CRUD;

use App\Core\Database\Database;

require_once dirname(__FILE__) . '/../Database/Database.php';

class Model
{

    const SQL_OPERATION_READ = 1;
    const SQL_OPERATION_INSERT = 2;
    const SQL_OPERATION_UPDATE = 3;

    /*
    * This is a $model_config example
    array(
    "tablename" => "usuarios",
    "fields" => array(
    "id"=> array("type"=>"INT", "length"=>"10"),
    "nombre"=> array("type"=>"STR", "length"=>"40"),
    "apellido"=> array("type"=>"STR", "length"=>"40"),
    "email"=> array("type"=>"STR", "length"=>"80"),
    "username"=> array("type"=>"STR", "length"=>"25"),
    "pass"=> array("type"=>"STR", "length"=>"255")
    )
    );
    */
    protected $model_config = false;

    function __construct($config)
    {
        $this->model_config = $config;
    }

    public function find($criteria = false, $prepare_mode = true)
    {
        return $this->executeReadQuery($criteria, $prepare_mode);
    }
    public function findOne($criteria, $prepare_mode = true)
    {
        if (isset($criteria["where"])) {
            $criteria["limit"] = 1;
        } else {
            $criteria = array("where" => $criteria, "limit" => 1);
        }
        return $this->executeReadQuery($criteria, $prepare_mode);
    }
    public function create($data, $prepare_mode = true)
    {
        return $this->executeWriteQuery(self::SQL_OPERATION_INSERT, $data, false, $prepare_mode);
    }
    public function createEach()
    {

    }
    public function update($data, $criteria, $prepare_mode = true)
    {
        if (is_numeric($criteria))
            $criteria = array("id" => $criteria);
        return $this->executeWriteQuery(self::SQL_OPERATION_UPDATE, $data, $criteria, $prepare_mode);
    }
    public function updateOne($data, $criteria, $prepare_mode = true)
    {
        if (is_numeric($criteria))
            $criteria = array("id" => $criteria);

        if (isset($criteria["where"])) {
            $criteria["limit"] = 1;
        } else {
            $criteria = array("where" => $criteria, "limit" => 1);
        }
        return $this->executeWriteQuery(self::SQL_OPERATION_UPDATE, $data, $criteria, $prepare_mode);
    }
    public function delete()
    {

    }
    public function deleteOne($data)
    {
        return $this->generateDeleteQuery($data);
    }



    /*
    array(
    "where" => array(
    "nombre" => "Rafa",
    "apellido" => ["San%", "LIKE"],
    "pass" => ["22|23|24", "IN"]
    ),
    "limit"=> 5,
    "sort" => array("id" => "DESC", "name" => "ASC")
    )
    * */
    private function formatWhereCriteria($where_criteria, $prepare_mode = true)
    {
        if (!isset($where_criteria) || !$where_criteria)
            return "";
        if (!isset($where_criteria["where"]) && (isset($where_criteria["sort"]) || isset($where_criteria["limit"]) || isset($where_criteria["skip"])))
            return "";
        $where_clause = "WHERE";

        $model = $this->model_config["fields"];

        foreach ($where_criteria as $w_item_key => $w_item) {
            if (isset($model[$w_item_key])) {
                $comls = ($model[$w_item_key]["type"] == "INT") ? "" : "'";

                if (is_array($w_item)) {
                    if ($w_item[1] == "IN") {
                        $in_set = explode("|", $w_item[0]);
                        $where_clause .= " " . $w_item_key . " IN (";
                        foreach ($in_set as $in_item) {
                            $where_clause .= ($prepare_mode) ? "?," : $comls . $in_item . $comls . ",";
                        }
                        $where_clause = substr_replace($where_clause, "", -1);
                        $where_clause .= ") AND";
                    } else {
                        $w_value = ($prepare_mode) ? "?" : $comls . $w_item[0] . $comls;
                        $where_clause .= " " . $w_item_key . " " . $w_item[1] . " " . $w_value . " AND";
                    }
                } else {
                    $w_value = ($prepare_mode) ? "?" : $comls . $w_item . $comls;
                    $where_clause .= " " . $w_item_key . " = " . $w_value . " AND";
                }
            }
        }

        return substr_replace($where_clause, "", -3);
    }


    private function formatSortCriteria($sort_criteria)
    {
        $query_sort = "ORDER BY";
        if ($sort_criteria) {
            //Se construyen las parejas: column1 [ASC|DESC],
            foreach ($sort_criteria as $sort_key => $sort_direction) {
                $direction = ($sort_direction == "ASC") ? "ASC" : "DESC";
                $query_sort .= " " . $sort_key . " " . $direction . ",";
            }
            //Elimina la ultima coma
            return substr_replace($query_sort, "", -1);
        }

        return "";
    }

    private function formatLimitCriteria($limit, $skip = false)
    {
        return ($skip && $limit) ? " LIMIT " . $skip . ", " . $limit : (($limit) ? " LIMIT " . $limit : "");
    }

    private function prepareCriteriaToBind($criteria, $prepare_mode = true)
    {
        if (!$prepare_mode)
            return false;

        $readyToBind = array();
        $model = $this->model_config["fields"];
        $where_criteria = (isset($criteria['where']) && is_array($criteria['where']) && count($criteria['where']) > 0) ? $criteria['where'] : $criteria;

        foreach ($where_criteria as $w_item_key => $w_item) {
            if (isset($model[$w_item_key])) {
                $type = $model[$w_item_key]["type"];

                if (is_array($w_item)) {
                    if ($w_item[1] == "IN") {
                        $in_set = explode("|", $w_item[0]);
                        foreach ($in_set as $in_item) {
                            array_push($readyToBind, array($in_item, $type));
                        }
                    } else {
                        array_push($readyToBind, array($w_item[0], $type));
                    }
                } else {
                    array_push($readyToBind, array($w_item, $type));
                }
            }
        }

        return $readyToBind;
    }

    private function prepareDatasetToBind($dataset, $prepare_mode = true)
    {
        if (!$prepare_mode)
            return false;

        $readyToBind = array();
        $model = $this->model_config["fields"];
        foreach ($dataset as $d_item_key => $data_item) { //(field_name => value, field_name2 => value2,...)
            if (isset($model[$d_item_key]) && $data_item !== "") {
                $type = $model[$d_item_key]["type"];
                array_push($readyToBind, array($data_item, $type));
            }
        }
        return $readyToBind;
    }

    private function executeReadQuery($criteria = false, $prepare_mode = true)
    {
        $query_str = "SELECT * FROM " . $this->model_config["tablename"] . " ";
        $query = $where = $sort = $skip = $limit = false;

        try {
            $connection = Database::instance();

            if ($criteria && is_array($criteria)) {

                //Construye el bloque Where de la sentencia
                $where = (isset($criteria['where']) && is_array($criteria['where']) && count($criteria['where']) > 0) ? $criteria['where'] : $criteria;
                $query_where = $this->formatWhereCriteria($where, $prepare_mode);

                //Construye el bloque Order By de la sentencia
                $sort = (isset($criteria['sort']) && is_array($criteria['sort']) && count($criteria['sort']) > 0) ? $criteria['sort'] : false;
                $query_sort = $this->formatSortCriteria($sort);

                //Construye el bloque limit de la sentencia
                $skip = (isset($criteria['skip'])) ? intval($criteria['skip']) : false;
                $limit = (isset($criteria['limit'])) ? intval($criteria['limit']) : false;
                $query_limit = $this->formatLimitCriteria($limit, $skip);


                $query_str .= $query_where . $query_sort . $query_limit;
                //var_dump($this->prepareCriteriaToBind($criteria, $prepare_mode));
                $query = $connection->prepare($query_str);

                $params = $this->prepareCriteriaToBind($criteria, $prepare_mode);
                if ($params) {
                    for ($i = 1; $i <= count($params); $i++) {
                        $w_item = $params[$i - 1];
                        if (is_array($w_item)) { // [value, type: INT|STR]
                            $query->bindParam($i, $w_item[0], ($w_item[1] == "INT") ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                        }
                    }
                }
            } else {
                // Dado que no se recibieron criterios de busqueda se ejecuta la consulta por defecto
                $query_str .= ";";
                //Se prepara la consulta para ser ejecutada
                $query = $connection->prepare($query_str);
            }


            // Setea el fetch mode para que retorne un array asociativo
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();

            return ($limit == 1) ? $query->fetch() : $query->fetchAll();

        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }


    private function executeWriteQuery($writeOperation, $data, $criteria = false, $prepare_mode = true)
    {
        $query_str = "";
        try {
            $connection = Database::instance();

            if ($writeOperation === self::SQL_OPERATION_INSERT) {
                $query_str = $this->generateInsertQuery($data, $prepare_mode);
            } else if ($writeOperation === self::SQL_OPERATION_UPDATE) {
                $query_str = $this->generateUpdateQuery($data, $criteria, $prepare_mode);
            }

            $query = $connection->prepare($query_str);

            if ($prepare_mode) {
                $params = $this->prepareDatasetToBind($data, $prepare_mode);
                if ($writeOperation === self::SQL_OPERATION_UPDATE && $criteria) {
                    $params = array_merge($params, $this->prepareCriteriaToBind($criteria, $prepare_mode));
                }
                //var_dump($params);

                foreach ($params as $p_index => $param) {
                    $query->bindParam($p_index + 1, $param[0], ($param[1] == "INT") ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                }
            }

            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $success_flag = $query->execute();
            if ($writeOperation === self::SQL_OPERATION_INSERT) {
                return ($success_flag) ? $connection->getLastId() : false;
            } else if ($writeOperation === self::SQL_OPERATION_UPDATE) {
                return ($success_flag) ? $query->rowCount() : false;
            }

        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateInsertQuery($bd_table, $fields_array)
    {
        $sql_query = "INSERT INTO `" . $bd_table . "`(";

        $fields_count = count($fields_array);
        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "`" . $fields_array[$f]["field"] . "`" : "`" . $fields_array[$f]["field"] . "`,";
        }

        $sql_query .= ") VALUES (";

        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "'" . $fields_array[$f]["value"] . "'" : "'" . $fields_array[$f]["value"] . "',";
        }

        $sql_query .= ");";

        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);

            $success_flag = $query->execute();
            return ($success_flag) ? $query->rowCount() : false;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateInsertManyQuerys($bd_table, $fields_array)
    {
        $sql_query = "INSERT INTO `" . $bd_table . "`(";

        $fields_count = count($fields_array);
        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "`" . $fields_array[$f]["field"] . "`" : "`" . $fields_array[$f]["field"] . "`,";
        }

        $sql_query .= ") VALUES (";

        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "'" . $fields_array[$f]["value"] . "'" : "'" . $fields_array[$f]["value"] . "',";
        }

        $sql_query .= ");";

        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $success_flag = $query->execute();
            return ($success_flag) ? $query->rowCount() : false;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateUpdateQuery($bd_table, $fields_array)
    {
        $sql_query = "UPDATE `" . $bd_table . "` SET ";

        $fields_count = count($fields_array);
        for ($f = 1; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "`" . $fields_array[$f]["field"] . "`= '" . $fields_array[$f]["value"] . "'" : "`" . $fields_array[$f]["field"] . "`= '" . $fields_array[$f]["value"] . "',";
        }
        $sql_query .= " WHERE `" . $bd_table . "`.`" . $fields_array[0]["field"] . "`=" . $fields_array[0]["value"];
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);

            $success_flag = $query->execute();
            return ($success_flag) ? $query->fetch() : false;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    /*  private function generateUpdateQuery($fields_data, $criteria, $prepare_mode = true) {
    if( !is_array($fields_data) || count($fields_data) == 0 ) return false;
    $sql_query = "UPDATE `" . $this->model_config["tablename"] . "` SET ";
    $model = $this->model_config["fields"];
    $processed = 0;
    foreach ($fields_data as $field_key => $field){ // $fields_data: (field_name => value, field_name2 => value2,...)
    if( isset($model[$field_key]) && $field !== "" ){
    $comls = ($model[$field_key]["type"] == "INT") ? "" : "'";
    $sql_query.= ($prepare_mode) ? $field_key . "=?, " : $field_key."=".$comls.$field.$comls. ", ";
    $processed++;
    }
    }
    if($processed == 0) return false;
    $sql_query = substr_replace($sql_query ,"", -2) . " ";
    //Construye el bloque Where de la sentencia
    $where = ( isset($criteria['where']) && is_array($criteria['where']) && count($criteria['where']) > 0 ) ? $criteria['where'] : $criteria;
    $query_where = $this->formatWhereCriteria($where, $prepare_mode);
    //Construye el bloque limit de la sentencia
    $limit = ( isset($criteria['limit']) ) ? intval( $criteria['limit'] ) : false;
    $query_limit = $this->formatLimitCriteria($limit);
    $sql_query.= $query_where . $query_limit;
    return $sql_query;
    } */

    protected static function generateDeleteQuery($bd_table, $forDelete_label, $value)
    {
        $sql_query = "DELETE FROM `" . $bd_table . "` WHERE " . $forDelete_label . " = " . $value;
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateEntrysRecentWithoutSlug($bd_table, $forDelete_label, $value)
    {
        $sql_query = "SELECT * FROM `" . $bd_table . "` WHERE `" . $forDelete_label . "` NOt IN('$value') ORDER BY id DESC LIMIT 3";
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateIdMapsCategory($bd_table, $forDelete_label, $value)
    {
        $sql_query = "SELECT * FROM `" . $bd_table . "` WHERE `" . $forDelete_label . "` NOt IN('$value')";
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }


    protected static function generateListForColumn($bd_table, $forDelete_label, $value)
    {
        $sql_query = "SELECT * FROM `" . $bd_table . "` WHERE `" . $forDelete_label . "`='$value'";
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function generateListImageForIdMap($bd_table, $forDelete_label, $value)
    {
        $sql_query = "SELECT * FROM `" . $bd_table . "` WHERE `" . $forDelete_label . "`='$value' ORDER BY id DESC LIMIT 5";
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }

    protected static function searchList($bd_table, $forDelete_label, $value)
    {
        $sql_query = "SELECT * FROM `" . $bd_table . "` WHERE `" . $forDelete_label . "` LIKE '%" . $value . "%'";
        try {
            $connection = Database::instance();
            $query = $connection->prepare($sql_query);
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            // Ejecuta la consulta
            $success_flag = $query->execute();
            return $query->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    }




    /*
    *
    protected static function executeQueryLegacy($custom_query, $param_array, $is_collection = false, $operation = self::SQL_OPERATION_READ) {
    try {
    $connection = Database::instance();
    $query = $connection->prepare($custom_query);
    if ($operation === self::SQL_OPERATION_UPDATE) {
    for ($i = 1; $i < count($param_array); $i++) {
    $param = $param_array[$i];
    $query->bindParam($i, $param["value"], ($param["type"] == "STR") ? \PDO::PARAM_STR : \PDO::PARAM_INT);
    }
    $param = $param_array[0];
    $query->bindParam(count($param_array), $param["value"], ($param["type"] == "STR") ? \PDO::PARAM_STR : \PDO::PARAM_INT);
    } else {
    for ($i = 1; $i <= count($param_array); $i++) {
    $param = $param_array[$i - 1];
    $query->bindParam($i, $param["value"], ($param["type"] == "STR") ? \PDO::PARAM_STR : \PDO::PARAM_INT);
    }
    }
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $success_flag = $query->execute();
    if ($operation === self::SQL_OPERATION_READ) {
    $result = ($is_collection) ? $query->fetchAll() : $query->fetch();
    return $result;
    } else if ($operation === self::SQL_OPERATION_INSERT) {
    return ($success_flag) ? $connection->getLastId() : false;
    } else if ($operation === self::SQL_OPERATION_UPDATE) {
    return ($success_flag) ? $query->rowCount() : false;
    }
    } catch (\PDOException $e) {
    print "Error!: " . $e->getMessage();
    return false;
    }
    }
    protected static function getByParam($bd_table, $param_label, $param, $param_type = "INT") {
    if (is_array($param_label)) {
    $sql = "SELECT * FROM " . $bd_table . " WHERE ";
    $params = array();
    foreach ($param_label as $key => $param) {
    $val = $param['type'] == "ID" ? $param['value'] : "?";
    $sql .= $param['label'] . " = $val AND ";
    if ($param['type'] != "ID") {
    array_push($params, array("value" => $param['value'], "type" => $param['type']));
    }
    }
    $sql = substr($sql, 0, -5);
    } else {
    $sql = "SELECT * FROM " . $bd_table . " WHERE " . $param_label . " = ?";
    $params = array(
    array("value" => $param, "type" => $param_type)
    );
    }
    return self::executeQuery($sql, $params, false);
    }*/

    /*
    protected static function getCollectionByParam($bd_table, $param_label, $param, $param_type = "INT", $order = false) {
    if (is_array($param_label)) {
    $sql = "SELECT * FROM " . $bd_table . " WHERE ";
    $params = array();
    foreach ($param_label as $key => $param) {
    $val = $param['type'] == "ID" ? $param['value'] : "?";
    $sql .= $param['label'] . " = $val AND ";
    if ($param['type'] != "ID") {
    array_push($params, array("value" => $param['value'], "type" => $param['type']));
    }
    }
    $sql = substr($sql, 0, -5);
    $query_order = "";
    if($order){
    $order_direction = ($order[1] == "A") ? " ASC" : " DESC";
    $query_order = "ORDER BY " . $order[0] . $order_direction;
    }
    $sql.= " ". $query_order;
    } else {
    $query_order = "";
    if($order){
    $order_direction = ($order[1] == "A") ? " ASC" : " DESC";
    $query_order = "ORDER BY " . $order[0] . $order_direction;
    }
    $sql = "SELECT * FROM " . $bd_table . " WHERE " . $param_label . " = ? " . $query_order;
    $params = array(
    array("value" => $param, "type" => $param_type)
    );
    }
    return self::executeQuery($sql, $params, true);
    }
    */




    protected static function createQueryData($bd_table, $params = false, $third_tables = false)
    {
        $table = $bd_table;
        $conditions = array();
        /* Se añaden tablas adicionales a query */
        if ($third_tables && is_array($third_tables)) {
            foreach ($third_tables as $key => $third_table) {
                $table .= ", $third_table";
            }
        }
        /* Se añaden las condiciones al query */
        if ($params && is_array($params)) {
            foreach ($params as $key => $param) {
                $label = $param['third_table'] ? $param['third_table'] . "." . $param['column'] : $param['column'];
                array_push($conditions, array(
                    "label" => $label,
                    "value" => $param['value'],
                    "type" => $param['type']
                )
                );
            }
        }
        return array("table" => $table, "conditions" => $conditions);
    }

}