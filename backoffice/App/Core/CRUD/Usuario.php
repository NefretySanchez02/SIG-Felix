<?php

namespace App\Core\CRUD;


require_once 'Model.php';

/**
 * Description of User
 *
 * @author rafaelsanchez
 */
class Usuario extends Model {

    const MODEL_CONFIG = array(
        "tablename" => "usuarios",
        "fields" => array(
            "id"=> array("type"=>"INT", "length"=>"10"),
            "usernm"=> array("type"=>"STR", "length"=>"25"),
            "passwd"=> array("type"=>"STR", "length"=>"255"),
            "nombre"=> array("type"=>"STR", "length"=>"100"),
            "email"=> array("type"=>"STR", "length"=>"80"),
            "level"=> array("type"=>"STR", "length"=>"255"),
            "ultimo_acceso"=> array("type"=>"STR", "length"=>"18"),
            "estado"=> array("type"=>"STR", "length"=>"10")
        )
    );

    function __construct() {
        parent::__construct(self::MODEL_CONFIG);
    }


    public function get($id){
        return parent::findOne(array("id"=>$id));
    }

    public function getByEmail($param) {
        return parent::findOne(
            array("email" => $param)
        );
    }

    public function getByUsername($param) {
        return parent::findOne(
            array("usernm" => $param)
        );
    }

}
