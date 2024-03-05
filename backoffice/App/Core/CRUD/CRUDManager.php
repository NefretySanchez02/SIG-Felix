<?php

namespace App\Core\CRUD;

/**
 * Description of EntityManager
 *
 * @author marte
 */
class CRUDManager {
    /*Entities*/
    const USER_ENTITY = "users";
    const SERVICE_ENTITY = "servicios";
    
    /*Filters*/
    const USER_EMAIL_FILTER = "email";
    const USER_ENABLED_FILTER = "estado";
    const USER_USERNAME_FILTER = "usernm";

    private $entity = false;

    function __construct($entityName) {

        if ($entityName === self::USER_ENTITY) {
            require_once 'User.php';
            $this->entity = self::USER_ENTITY;
        } else if ($entityName === self::SERVICE_ENTITY) {
            require_once 'Servicios.php';
            $this->entity = self::SERVICE_ENTITY;
        }
    }

    public function getAll($order="ASC", $limit = false) {
        if ($this->entity === self::USER_ENTITY) {
            return User::getAll($order, $limit);
        } else if ($this->entity === self::SERVICE_ENTITY) {
            return Servicios::getAll($order, $limit);
        }
    }

    public function getById($id) {        
        if ($this->entity === self::USER_ENTITY) {
            return User::getById($id);
        } else if ($this->entity === self::SERVICE_ENTITY) {
            return Servicios::getById($id);
        }
    }

    public function getByFilter($filter, $queryParam) {
        if ($this->entity === self::USER_ENTITY) {
            if ($filter === self::USER_EMAIL_FILTER) {
                return User::getByEmail($queryParam);
            } else if ($filter === self::USER_ENABLED_FILTER) {
                return User::getAllActiveUsers();
            } else if ($filter === self::USER_USERNAME_FILTER) {
                return User::getByUser($queryParam);
            }
        }
    }

    public function insert($data) {        
        if ($this->entity === self::USER_ENTITY) {
            return User::insert($data); 
        } else if ($this->entity === self::SERVICE_ENTITY) {
            return Servicios::insert($data); 
        }
    }

    public function update($data) {
        if ($this->entity === self::USER_ENTITY) {
            return User::update($data);
        } else if ($this->entity === self::SERVICE_ENTITY) {
            return Servicios::update($data);
        }
    }

    public function delete($id) {        
        if ($this->entity === self::USER_ENTITY) {
            return User::delete($id);
        } else if ($this->entity === self::SERVICE_ENTITY) {
            return Servicios::delete($id);
        }
    }

}
