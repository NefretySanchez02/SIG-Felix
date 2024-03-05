<?php

namespace App\Core\CRUD;


require_once 'Model.php';

/**
 * Description of User
 *
 * @author rafaelsanchez
 */
class Contact extends Model
{
    const SQL_TABLE = "contacto";

    const MODEL_CONFIG = array(
        "tablename" => "contacto",
        "fields" => array(
            "id" => array("type" => "INT", "length" => "10"),
            "correo" => array("type" => "STR", "length" => "250"),
            "telefono" => array("type" => "STR", "length" => "250"),
            "whatsapp" => array("type" => "STR", "length" => "250"),
            "ubicacion" => array("type" => "STR", "length" => "250")
        )
    );

    function __construct()
    {
        parent::__construct(self::MODEL_CONFIG);
    }


    public function get($id)
    {
        return parent::findOne(array("id" => $id));
    }

    public function getById($id)
    {
        return parent::findOne(array("id" => $id));
    }

    public function getListType($tipo)
    {
        $sql = parent::generateListForColumn(self::SQL_TABLE, "id_Mapa", $tipo);
        $params = array(
            array("value" => $tipo, "type" => "STR")
        );

        return $sql;
    }

    public function getListByName($title)
    {
        $sql = parent::searchList(self::SQL_TABLE, "nombre", $title);
        $params = array(
            array("value" => $title, "type" => "STR")
        );
        return $sql;
    }

    public static function updateContactById($datap)
    {
        $fields_array = array(
            // array( array("field" => FIELD-NAME, "value" => FIELD-VALUE, "type" => ["INT" | "STR"] ) )
            array("field" => "id", "value" => $datap[0], "type" => "INT"),
            //Send the table id field in the first array position
            array("field" => "correo", "value" => $datap[1], "type" => "STR"),
            array("field" => "telefono", "value" => $datap[2], "type" => "STR"),
            array("field" => "whatsapp", "value" => $datap[3], "type" => "STR"),
            array("field" => "ubicacion", "value" => $datap[4], "type" => "STR")
        );
        $sql = parent::generateUpdateQuery(self::SQL_TABLE, $fields_array);
        $params = array(
            array("value" => $fields_array[0]["field"], "type" => "INT")
        );
        $affected_arrows = parent::executeQuery($sql, $params, false);
        return $affected_arrows;
    }

}