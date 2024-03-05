<?php

namespace App\Core\Database;



/**
 * @class Database
 */
class Database {

    /**
     * @desc nombre del usuario de la base de datos
     * @var $_dbUser
     * @access private
     */
    private $_dbUser;

    /**
     * @desc password de la base de datos
     * @var $_dbPassword
     * @access private
     */
    private $_dbPassword;

    /**
     * @desc nombre del host
     * @var $_dbHost
     * @access private
     */
    private $_dbHost;

    /**
     * @desc nombre de la base de datos
     * @var $_dbName
     * @access protected
     */
    protected $_dbName;

    /**
     * @desc conexión a la base de datos
     * @var $_connection
     * @access private
     */
    private $_connection;

    /**
     * @desc Constante para configurar order ascendente
     * @var ASCENDENT_ORDER
     * @access public
     */
    const ASCENDENT_ORDER = 1;

    /**
     * @desc Constante para configurar order descendente
     * @var DESCENDENT_ORDER
     * @access public
     */
    const DESCENDENT_ORDER = 2;

    /**
     * @desc instancia de la base de datos
     * @var $_instance
     * @access private
     */
    private static $_instance;

    /**
     * [__construct]
     */
    private function __construct() {
        try {
            //load from Config/Config.ini

            $config = parse_ini_file(dirname(__FILE__) . '/../Config/config.ini');;
            $this->_dbHost = $config["host"];
            $this->_dbUser = $config["user"];
            $this->_dbPassword = $config["password"];
            $this->_dbName = $config["database"];

            $this->_connection = new \PDO('mysql:host=' . $this->_dbHost . '; dbname=' . $this->_dbName, $this->_dbUser, $this->_dbPassword);
            $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_connection->exec("SET CHARACTER SET utf8");
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    /**
     * [prepare]
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function prepare($sql) {
        return $this->_connection->prepare($sql);
    }

    /**
     * [getLastId]
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function getLastId() {
        return $this->_connection->lastInsertId();
    }

    /**
     * [getOrderConfiguration]
     * @param  [Array] $order_conf [Array con los dos párametros necesarios para configurar un ordenamiento.
     * La estructura debe ser: Array("ORDER-COLUMN" => [Column name], "ORDER"=>[Order Ascendent | Order Descendent])].
     * En el index asociativo "ORDER", para ordenar ascendente enviar 1 y para descendente enviar 2
     * @return [String]      [instrucción SQL para ordenar el resultado]
     */
    public static function getOrderConfiguration($order_conf) {
        $order = "";
        if(!$order_conf){
            if( isset($order_conf["ORDER-COLUMN"]) && isset($order_conf["ORDER"]) ){
                switch ($order_conf["ORDER"]) {
                    case 1: $order = " ORDER BY " . $order_conf["ORDER-COLUMN"] . "ASC";
                        break;
                    case 2: $order = " ORDER BY " . $order_conf["ORDER-COLUMN"] . "DESC";
                        break;
                    default:
                        break;
                }
            }
        }
        return $order;
    }

    /**
     * [instance singleton]
     * @return [object] [class database]
     */
    public static function instance() {
        if (!isset(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }

    /**
     * [__clone Evita que el objeto se pueda clonar]
     * @return [type] [message]
     */
    public function __clone() {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }

}
