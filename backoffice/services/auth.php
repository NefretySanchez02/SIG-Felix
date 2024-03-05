<?php
  use App\Core\CRUD\Usuario;
  
  require_once '../App/Core/CRUD/Usuario.php';

  //Prepare response
  $response = array();

  /**
   * Captured parameters sended from login form to init session
   */
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  //check if received parameter are valid.
  if ( empty($username) || empty($password)) {
      $response["success"] = 0;
      $response["error"] = "Los datos enviados no son válidos.";
      
      die(json_encode($response));
  }

  $userManager = new Usuario();
  $user_data = $userManager->getByUsername($username);

  if (password_verify($password, $user_data['passwd'])) {
      session_name('Clu5TerM2021');
      //session_unset();
      //session_destroy();
      session_start();
    
      $_SESSION['app-id'] = 'CLUSTERM';
      $_SESSION['user-id'] = $user_data['id'];
      $_SESSION['user-name'] = ucwords(strtolower($user_data['nombre']));

      $response["success"] = 1;
      
  } else {
    $response["success"] = 0;
    $response["error"] = "El usuario o la contrasena son incorrectos.";
  }
  
  //send json response data to the client
  echo json_encode($response);
