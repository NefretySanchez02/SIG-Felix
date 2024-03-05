<?php

namespace App\Core\CRUD;


require_once 'Model.php';

/**
 * Description of User
 *
 * @author rafaelsanchez
 */
class Mensaje extends Model {

    const MODEL_CONFIG = array(
        "tablename" => "mensajes",
        "fields" => array(
            "id"=> array("type"=>"INT", "length"=>"10"),
            "nombre"=> array("type"=>"STR", "length"=>"100"),
            "email"=> array("type"=>"STR", "length"=>"80"),
            "telefono"=> array("type"=>"STR", "length"=>"15"),
            "asunto"=> array("type"=>"STR", "length"=>"160"),
            "mensaje"=> array("type"=>"STR", "length"=>""),
            "datetime"=> array("type"=>"STR", "length"=>"18"),
            "empresa"=> array("type"=>"STR", "length"=>"50"),
            "estado"=> array("type"=>"STR", "length"=>"10")
        )
    );

    function __construct() {
        parent::__construct(self::MODEL_CONFIG);
    }


    public function get($id){
        return parent::findOne(array("id"=>$id));
    }

    

}
