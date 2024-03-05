<?php
    session_name('SLAZAR0APP');
    session_start();

    if(!isset($_SESSION['app-id']) || !isset($_SESSION['user-id'])){
        die('{"success": 0, "error": "Usuario no autenticado"}');
    }

    if($_SESSION['app-id'] != 'SANLAZARO'){
        die('{"success": 0, "error": "Usuario no autenticado"}');
    }



    use App\Core\CRUD\CRUDManager;
    //use App\Core\Box\Tools;

    require_once '../App/Core/CRUD/CRUDManager.php';
    require_once '../App/RedBox/uploader/RSFileUploader.php';
    //require_once '../Core/Box/Tools.php';

    if ( !( isset($_POST['action']) || isset($_GET['action']) ) ) {
        die('{"success": 0, "error": "No action sent"}');
    }

    if(isset($_GET['action']) && $_GET['action'] == "get"){
        $serviceManager = new CRUDManager(CRUDManager::SERVICE_ENTITY);
        $services_list = $serviceManager->getAll();
        
        $response = array();
        $servicios = array();
        foreach ($services_list as $serv) {
            $servicio_data = array();
            $servicio_data['service_id'] = $serv["id"];
            $servicio_data['service_name'] = $serv["nombre_recurso"];
            $servicio_data['service_order'] = $serv["orden"];
            $servicio_data['service_img'] = './assets/img/services/' . $serv["imagen"];
            
            $servicios[] = $servicio_data;
        }
        $response["success"] = 1;
        $response["services"] = $servicios;
        $response["services_count"] = count($servicios);
        
        
        
        echo json_encode($response);
    } 
    else if($_POST['action'] == "create"){
        if (empty($_POST['name']) || empty($_POST['order']) || empty($_POST['image'])) {
            die('{"success": 0, "error": "Missing parameters"}');
        }

        $service_data[1] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $service_data[2] = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);
        $service_data[3] = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
        $service_data[4] = date("Y-m-d H:i:s");

        $serviceManager = new CRUDManager(CRUDManager::SERVICE_ENTITY);
        $newId = $serviceManager->insert($service_data);

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
    else if($_POST['action'] == "update"){
        if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['order']) || empty($_POST['image'])) {
            die('{"success": 0, "error": "Missing parameters"}');
        }

        $service_data[0] = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $service_data[1] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $service_data[2] = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);
        $service_data[3] = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
        $service_data[4] = date("Y-m-d H:i:s");

        if(strpos($service_data[2], '/')){
            $splitedUrl = explode('/', $service_data[2]);
            $service_data[2] = $splitedUrl[count($splitedUrl) - 1];
        }

        $serviceManager = new CRUDManager(CRUDManager::SERVICE_ENTITY);
        $qres = $serviceManager->update($service_data);
        
        $response = array();
        if($qres){
            $response["success"] = 1;
            $response["data"] = $newId;
        }else{
            $response["success"] = 0;
            $response["error"] = "Register couldn't be updated";
        }
        
        echo json_encode($response);
    }
    else if($_POST['action'] == "delete"){
        if (empty($_POST['id'])) {
            die('{"success": 0, "error": "Missing parameters"}');
        }

        $service_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $serviceManager = new CRUDManager(CRUDManager::SERVICE_ENTITY);
        $qres = $serviceManager->delete($service_id);

        $response = array();
        $response["success"] = 1;

        
        
        echo json_encode($response);
    }
    else if($_POST['action'] == "upload_image"){
        $response = array();

        $itemId = $_POST['id'];
        $dataId = ($itemId) ? 'service-img-tmp-' . $itemId : 'new-service-img-tmp';

        //Comprueba que se reciba el archivo.
        if (!($_FILES[$dataId]["error"] == 0 && $_FILES[$dataId]["name"] != "")) {
            //AQUI SE RETORNA UN MENSAJE DE ERROR EN JSON PARA AJAX
            die();
        }

        $uploaded_img = new RSFileUploader($_FILES[$dataId], array("max-size" => "1MB", "allowed-types" => RSFileUploader::IMAGE));


        if ($uploaded_img->isItReady()) {
            $file_up_res = $uploaded_img->uploadFile(__DIR__ . '/../assets/img/services/', "serv");
            if ($file_up_res) {
            //echo "subido";
            } else {
                $response['success'] = 0;
                $response['error'] = 'No se pudo subir el archivo.';
                die(json_encode($response));
            }
        } else {
            $response['success'] = 0;
            $response['error'] = 'El archivo no fue aceptado.';
            die(json_encode($response));
        }

        $response = array();
        $response['success'] = 1;
        $response['filename'] = $uploaded_img->getFileName();

        echo json_encode($response);
    }
    else{
        die('{"success": 0, "error": "No valid action or method"}');
    }











