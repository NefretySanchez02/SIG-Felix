<?php

require_once 'security.php';

use App\Core\CRUD\Contact;
use App\Core\Box\Tools;

require_once '../App/Core/CRUD/Contact.php';
//require_once '../App/BlackBox/uploader/RSFileUploader.php';
//require_once '../Core/Box/Tools.php';

date_default_timezone_set('America/Bogota');

if (!(isset($_POST['action']) || isset($_GET['action']))) {
    die('{"success": 0, "error": "No action sent"}');
}

if ($_GET['action'] == "list") {
    listItems();
} else if ($_GET['action'] == "get") {
    get();
} else if ($_GET['action'] == "getByName") {
    getByName();
} else if ($_POST['action'] == "update") {
    update();
} else {
    die('{"success": 0, "error": "No valid action or method"}');
}



function listItems()
{
    $newsManager = new Contact();
    $newsItem_list = $newsManager->find(array("sort" => array("id" => "ASC")));
    print_r($newsItem_list);

    $response = array();

    $response["success"] = 1;
    $response["messages"] = $newsItem_list;

    echo json_encode($response);
}

function getByName()
{
    $newsManager = new Contact();

    $title = filter_input(INPUT_GET, "id_mapa", FILTER_SANITIZE_STRING);
    if (empty($title))
        die(json_encode(array("success" => 0, "error_msg" => "slug param has and invalid value")));

    $newsItem = $newsManager->getListType($title);
    if (!$newsItem) {

    }

    $response = array();

    $response["success"] = 1;
    $response["news_item"] = $newsItem;
    //$response["services_count"] = count($servicios);

    echo json_encode($response);
}

function get()
{
    $newsManager = new Contact();

    $slug = filter_input(INPUT_GET, "slug", FILTER_SANITIZE_STRING);
    if (empty($slug))
        die(json_encode(array("success" => 0, "error_msg" => "slug param has and invalid value")));

    $newsItem = $newsManager->getById($slug);
    if (!$newsItem) {

    }

    $response = array();

    $response["success"] = 1;
    $response["news_item"] = $newsItem;
    //$response["services_count"] = count($servicios);

    echo json_encode($response);
}

function update()
{

    $serviceManager = new Contact();
    $service_data[0] = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $service_data[1] = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_STRING);
    $service_data[2] = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
    $service_data[3] = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_STRING);
    $service_data[4] = filter_input(INPUT_POST, 'ubicacion', FILTER_SANITIZE_STRING);

    $qres = $serviceManager->updateContactById($service_data);

    $response = array();
    if ($qres) {
        $response["success"] = 1;
        $response["data"] = "Register could be updated";
    } else {
        $response["success"] = 0;
        $response["error"] = "Register couldn't be updated";
    }

    echo json_encode($response);
}
