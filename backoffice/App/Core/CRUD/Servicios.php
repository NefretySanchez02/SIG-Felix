<?php

namespace App\Core\CRUD;


require_once 'Crud.php';

/**
 * Description of User
 *
 * @author rafaelsanchez 
 */
class Servicios extends Crud/* implements Crud */ {

    const SQL_TABLE = "otros_servicios";

    public static function getAll($order="ASC", $limit = false) {
        $order_param = "orden";
        return parent::getCollectionByParam(self::SQL_TABLE, "1", 1, "INT", $order, $limit, $order_param);
    }

    public static function getById($id) {
        return parent::getByParam(self::SQL_TABLE, "id", $id);
    }
    
    public static function insert($datap) {

        $fields_array = array(
            // array( array("field" => FIELD-NAME, "value" => FIELD-VALUE, "type" => ["INT" | "STR"] ) )            
            array("field" => "nombre_recurso", "value" => $datap[1], "type" => "STR"),
            array("field" => "imagen", "value" => $datap[2], "type" => "STR"),
            array("field" => "orden", "value" => $datap[3], "type" => "INT"),
            array("field" => "ultima_actualizacion", "value" => $datap[4], "type" => "STR")
        );

        $last_id = parent::executeQuery(parent::generateInsertQuery(self::SQL_TABLE, $fields_array), $fields_array, false, parent::SQL_OPERATION_INSERT);

        return $last_id;
    }

    public static function update($datap) {
        
        $fields_array = array(
            // array( array("field" => FIELD-NAME, "value" => FIELD-VALUE, "type" => ["INT" | "STR"] ) )
            array("field" => "id", "value" => $datap[0], "type" => "INT"),//Send the table id field in the first array position
            array("field" => "nombre_recurso", "value" => $datap[1], "type" => "STR"),
            array("field" => "imagen", "value" => $datap[2], "type" => "STR"),
            array("field" => "orden", "value" => $datap[3], "type" => "INT"),
            array("field" => "ultima_actualizacion", "value" => $datap[4], "type" => "STR")
        );
        
        $affected_arrows = parent::executeQuery(parent::generateUpdateQuery(self::SQL_TABLE, $fields_array), $fields_array, false, parent::SQL_OPERATION_UPDATE);

        return $affected_arrows;
    }

   
    public static function delete($id) {
        $sql = parent::generateDeleteQuery(self::SQL_TABLE, "id");
        $params = array(
            array("value" => $id, "type" => "INT")
        );
        echo $sql;
        return self::executeQuery($sql, $params, false);
    }

}
