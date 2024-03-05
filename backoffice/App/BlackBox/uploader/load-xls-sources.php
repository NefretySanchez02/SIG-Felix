<?php
error_reporting(0);
set_time_limit(600);
//error_reporting(0);
//require_once "../../vendor/autoload.php";

require 'RSFileUploader.php';
require 'XLSEngine.php';
require 'ProjectPersistenceEngagement.php';


//Comprueba que se reciban los dos archivos Excel, las cabeceras y los detalles.
if (!($_FILES["data-source"]["error"] == 0 && $_FILES["data-source"]["name"] != "")) {
  header("Location: ../index.html?ec=E00NFF");
  die();
}

$data_source = new RSFileUploader($_FILES["data-source"], array("max-size" => "8MB", "allowed-types" => RSFileUploader::DOC_EXCEL));


if ($data_source->isItReady()) {
  $data_upd_res = $data_source->uploadFile("../tmp/xls_uploads/", "source");
  if ($data_upd_res) {
    //echo "subido";

    $engine = new XLSEngine($data_source->getFileName());

    $normalized_projects_data_array = $engine->getNormalizedData();

    $project_pe = new ProjectPersistenceEngagement();
    $error_count = 0;

    foreach ($normalized_projects_data_array as $project_data) {
      $persist_response = $project_pe->persistProject($project_data);
      if ($persist_response === false) {
        $error_count++;
      }
    }

    if ($error_count > 0) {
      echo "han ocurrido " . $error_count . " durante el proceso de persistencia.";
    }else{
      header("Location: ../../table.html");
    }
    
  } else {
    echo "no se pudo subir";
  }
} else {
  echo "La prueba itsready falló";
}



















