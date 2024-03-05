<?php
  require_once 'web-snippets.php';
  session_control();
?>

<!doctype html>
<html lang="es">

<head>
  <title>Admin Module</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Kit CSS -->
  <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />

  <style>
    .hide {
      display: none !important;
    }
    .input-hidden{
      visibility: hidden;
      position: absolute;
      z-index: -1;
    }
    
    tr.new{
      background: rgba(156,38,176, .05);
    }

    .service-img{
      position: relative;
    }

    .service-overlay{
      display: none;
    }
    .edit .service-overlay{
      position: absolute;
      background: rgba(255,255,255, .4);
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;

      display: flex;
      justify-content: center;
      align-items: flex-end;

      font-size: 1.1em;
      font-weight: 400;
      padding-bottom: 1.5em;
      cursor: pointer;
      transition: all .3s ease;
    }
    .new .service-overlay{
      background: rgba(156,38,176, .05) !important;
    }
    .edit .service-overlay:hover{
      background: rgba(255,255,255, .8);
      font-size: 1.2em;
      transition: all .3s ease;
    }
    .new .service-overlay:hover{
      background: rgba(156,38,176, .2) !important;
    }

    .edit .service-overlay label, .new .service-overlay label{
      cursor: pointer;
    }

    .service-name, .service-order{
      display: inline;
    }
    .edit .service-name, .edit .service-order{
      display: none;
    }

    .service-input-name, .service-input-order{
      display: none;
    }
    .edit .service-input-name, .edit .service-input-order{
      display: block;
    }

    .service-save{
      display: none;
    }
    .service-save a{
      display: inline-block;
    }
    .edit .service-save{
      display: block;
    }
    .service-options{
      text-align: center;
    }
    .service-options a{
      display: inline-block;
    }
    .edit .service-options{
      display: none;
    }
    
    
  </style>
</head>

<body>
  <div class="wrapper ">
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="assets/img/sidebar-2.jpg">
      <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
      <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
          San L&aacute;zaro
        </a>        
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item  ">
            <a class="nav-link" href="./">
              <i class="material-icons">home</i>
              <p>Inicio</p>
            </a>
          </li>
          <li class="nav-item active  ">
            <a class="nav-link" href="#">
              <i class="material-icons">work</i>
              <p>Otros Servicios</p>
            </a>
          </li>
          <li class="nav-item  ">
            <a class="nav-link" href="logout.php">
              <i class="material-icons">exit_to_app</i>
              <p>Salir</p>
            </a>
          </li>
          <!-- your sidebar here -->
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Servicios Complementarios</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                  <i class="material-icons">account_circle</i> Admin
                </a>
              </li>
              <!-- your navbar here -->
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-info">
                  <h4 class="card-title ">Servicios</h4>
                  <p class="card-category"> Configura los servicios que aparecer&aacute;n en la sección de Servicios Complementarios</p>                  
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th class="text-center" style="width: 10px;">Imagen</th>
                        <th class="text-left">Recurso</th>
                        <th class="text-center" style="width: 10px;">Orden</th>
                        <th class="text-center" style="width: 10px;">Opciones</th>
                      </thead>
                      <tbody id="services-wrapper">                      
                        
                      </tbody>
                    </table>

                    <div class="text-right">
                      <a id="add-serv" class="text-info text-hover-effect py-4 pr-2" style="float: right;" onclick="servicesUIManager.addServiceItem()">
                        <i class="material-icons">add_circle_outline</i> Agregar Servicio
                      </a>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            
          </div>




          
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  San L&aacute;zaro
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>

  <script src="./assets/js/core/jquery.min.js"></script>
  <script src="./assets/js/services.js"></script>
  <script>
    servicesClient.get();
  </script>
</body>

</html>