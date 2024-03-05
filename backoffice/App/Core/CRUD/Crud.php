<?php
namespace App\Core\CRUD;

use App\Core\Database\Database;

require_once dirname(__FILE__) . '/../Database/Database.php';

abstract class Crud
{

    const SQL_OPERATION_READ = 1;
    const SQL_OPERATION_INSERT = 2;
    const SQL_OPERATION_UPDATE = 3;

    abstract protected static function getAll();

    abstract protected static function getById($id);

    abstract protected static function insert($data);

    abstract protected static function update($data);

    abstract protected static function delete($id);

    protected static function executeQuery($custom_query, $param_array, $is_collection = false, $operation = self::SQL_OPERATION_READ)
    {
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



            $query->setFetchMode(\PDO::FETCH_BOTH);
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

    protected static function getByParam($bd_table, $param_label, $param, $param_type = "INT")
    {
        $sql = "SELECT * FROM " . $bd_table . " WHERE " . $param_label . " = ?;";
        $params = array(
            array("value" => $param, "type" => $param_type)
        );
        return self::executeQuery($sql, $params, false);
    }

    protected static function getCollectionByParam($bd_table, $param_label, $param, $param_type = "INT", $order = "ASC", $limit = false, $order_param = "id")
    {
        $sql_order = ($order == "ASC") ? " ORDER BY " . $order_param . " ASC" : " ORDER BY " . $order_param . " DESC";
        $sql_limit = (is_numeric($limit) && $limit > 0) ? " LIMIT " . $limit : "";
        $sql = "SELECT * FROM " . $bd_table . " WHERE " . $param_label . " = ?" . $sql_order . $sql_limit;
        $params = array(
            array("value" => $param, "type" => $param_type)
        );
        return self::executeQuery($sql, $params, true);
    }

    protected static function generateInsertQuery($bd_table, $fields_array)
    {
        $sql_query = "INSERT INTO `" . $bd_table . "`(";

        $fields_count = count($fields_array);
        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "`" . $fields_array[$f]["field"] . "`" : "`" . $fields_array[$f]["field"] . "`, ";
        }

        $sql_query .= ") VALUES (";

        for ($f = 0; $f < $fields_count; $f++) {
            $sql_query .= ($f == 0) ? "?" : ",?";
        }

        $sql_query .= ");";

        return $sql_query;
    }

    protected static function generateUpdateQuery($bd_table, $fields_array)
    {
        $sql_query = "UPDATE `" . $bd_table . "` SET ";

        $fields_count = count($fields_array);
        for ($f = 1; $f < $fields_count; $f++) {
            $sql_query .= ($f == $fields_count - 1) ? "`" . $fields_array[$f]["field"] . "`=?" : "`" . $fields_array[$f]["field"] . "`=?, ";
        }

        $sql_query .= " WHERE `" . $fields_array[0]["field"] . "`=?;";
        return $sql_query;
    }

    protected static function generateDeleteQuery($bd_table, $forDelete_label)
    {
        $sql_query = "DELETE FROM `" . $bd_table . "` WHERE " . $forDelete_label . " = ?";

        return $sql_query;
    }

}