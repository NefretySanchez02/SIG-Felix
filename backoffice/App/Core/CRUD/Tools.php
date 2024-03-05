<?php

namespace App\Core\CRUD;


require_once 'Model.php';

/**
 * Description of User
 *
 * @author rafaelsanchez
 */
class Herramientas extends Model
{
    const SQL_TABLE = "herramientas";

    const MODEL_CONFIG = array(
        "tablename" => "herramientas",
        "fields" => array(
            "id" => array("type" => "INT", "length" => "10"),
            "titulo" => array("type" => "STR", "length" => "250"),
            "subtitulo" => array("type" => "STR", "length" => "250"),
            "imagen" => array("type" => "STR", "length" => "250"),
            "parrafo_banner" => array("type" => "STR", "length" => ""),
            "texto_boton" => array("type" => "STR", "length" => "250"),
            "url_boton" => array("type" => "STR", "length" => "250")
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

    public function deleteById($id)
    {
        $sql = parent::generateDeleteQuery(self::SQL_TABLE, "id", $id);
        $params = array(
            array("value" => $id, "type" => "INT")
        );
        echo $sql;
        return parent::executeQuery($sql, $params, false);
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

    public static function updateToolsById($datap)
    {
        $fields_array = array(
            // array( array("field" => FIELD-NAME, "value" => FIELD-VALUE, "type" => ["INT" | "STR"] ) )
            array("field" => "id", "value" => $datap[0], "type" => "INT"),
            //Send the table id field in the first array position
            array("field" => "titulo", "value" => $datap[1], "type" => "STR"),
            array("field" => "subtitulo", "value" => $datap[2], "type" => "STR"),
            array("field" => "imagen", "value" => $datap[3], "type" => "STR"),
            array("field" => "texto_boton", "value" => $datap[4], "type" => "STR"),
            array("field" => "url_boton", "value" => $datap[5], "type" => "STR")
        );
        $sql = parent::generateUpdateQuery(self::SQL_TABLE, $fields_array);
        $params = array(
            array("value" => $fields_array[0]["field"], "type" => "INT")
        );
        $affected_arrows = parent::executeQuery($sql, $params, false);
        return $affected_arrows;
    }

    public static function createTools($data)
    {
        $fields_array = array(
            array("field" => "titulo", "value" => $data[0], "type" => "STR"),
            array("field" => "subtitulo", "value" => $data[1], "type" => "STR"),
            array("field" => "imagen", "value" => $data[2], "type" => "STR"),
            array("field" => "texto_boton", "value" => $data[3], "type" => "STR"),
            array("field" => "url_boton", "value" => $data[4], "type" => "STR")
        );
        $sql = parent::generateInsertQuery(self::SQL_TABLE, $fields_array);
        $affected_arrows = parent::executeQuery($sql, false);
        return $affected_arrows;
    }
}