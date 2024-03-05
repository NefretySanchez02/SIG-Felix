<?php
    error_reporting(0);
    
    require_once 'security.php';

    use App\Core\CRUD\Usuario;
    //use App\Core\Box\Tools;

    require_once '../App/Core/CRUD/Usuario.php';
    //require_once '../App/RedBox/uploader/RSFileUploader.php';
    //require_once '../Core/Box/Tools.php';

    date_default_timezone_set('America/Bogota');

    if ( !( isset($_POST['action']) || isset($_GET['action']) ) ) {
        die('{"success": 0, "error": "No action sent"}');
    }

    if($_GET['action'] == "list"){ listItems(); } 
    else if($_GET['action'] == "get"){ get(); }
    else if($_GET['action'] == "stats"){ stats(); } 
    else if($_POST['action'] == "create"){ create(); }
    else if($_POST['action'] == "set-state"){ setState(); }
    else{
        die('{"success": 0, "error": "No valid action or method"}');
    }



    function listItems () {
        $userManager = new Usuario();
        $user_list = $userManager->find(array( "sort" => array("nombre" => "ASC") ));
        
        $response = array();
        
        $response["success"] = 1;
        $response["messages"] = $user_list;
        
        echo json_encode($response);
    }

    function get () {
        $userManager = new Usuario();

        $id  = filter_input(INPUT_GET, "mid", FILTER_SANITIZE_NUMBER_INT);
        if(empty($id)) die(json_encode(array("success" => 0, "error_msg" => "mid param has and invalid value")));

        $message = $userManager->get($id);
        if(!$message){

        }
        
        $response = array();
        
        $response["success"] = 1;
        $response["messaje"] = $message;
        //$response["services_count"] = count($servicios);
        
        echo json_encode($response);
    }

    function stats () {
        $userManager = new Usuario();
        $message_list = $userManager->find(array("estado" => "ACTIVO"));
        
        $response = array();
        
        $response["success"] = 1;
        $response["messages_count"] = count($message_list);
        
        echo json_encode($response);
    }

    function create () {
        if($_POST['decoy'] != "") die(); //for robot detection

        if (
            empty($_POST['nombre']) ||
            empty($_POST['email']) ||
            empty($_POST['usernm']) ||
            empty($_POST['passwd']) ||
            empty($_POST['level'])
        ){
            die('{"success": 0, "error": "Missing parameters"}');
        }

        $messaje = array();
        $messaje["nombre"] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $messaje["email"] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $messaje["usernm"] = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $messaje["passwd"] = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING);
        $messaje["level"] = filter_input(INPUT_POST, 'lvl', FILTER_SANITIZE_STRING);
        
        $messaje["ultimo_acceso"] = date("Y-m-d H:i:s");
        $messaje["estado"] = "ACTIVO";


        $userManager = new Usuario();
        $newId = $userManager->create($messaje);

        $response = array();
        if($newId){
            $response["success"] = 1;
            $response["nid"] = $newId;
        }else{
            $response["success"] = 0;
            $response["error"] = "Register couldn't be created";
        }
        
        echo json_encode($response);
    }

    function setState () {
        $id = filter_input(INPUT_POST, "uid", FILTER_SANITIZE_NUMBER_INT);
        if(empty($id)) die(json_encode(array("success" => 0, "error_msg" => "uid param has and invalid value")));

        $state = filter_input(INPUT_POST, "new_state", FILTER_SANITIZE_NUMBER_INT);
        if(empty($state)) die(json_encode(array("success" => 0, "error_msg" => "state param has and invalid value")));

        $states = ['INACTIVO', 'ACTIVO'];
        $userManager = new Usuario();
        $qres = $userManager->updateOne(array("estado" => $states[$state]), $id);
        
        $message = $userManager->get($id);

        $response = array();
        if($qres){
            $response["success"] = 1;
            $response["data"] = $message;
        }else{
            $response["success"] = 0;
            $response["error"] = "Register couldn't be updated";
        }
        
        echo json_encode($response);
    }










