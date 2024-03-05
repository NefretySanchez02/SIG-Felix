<?php
session_name('Clu5TerM2021');
    session_start();

    if(!isset($_SESSION['app-id']) || !isset($_SESSION['user-id'])){
        die('{"success": 0, "error": "Usuario no autenticado"}');
    }

    if($_SESSION['app-id'] != 'CLUSTERM'){
        die('{"success": 0, "error": "Usuario no autenticado"}');
}