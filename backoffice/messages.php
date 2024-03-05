<?php
  require_once 'web-snippets.php';
  session_control();
?>

<!doctype html>
<html lang="es">

<head>
  <?php echo html_head() ?>

  <style>
    .hide {
      display: none !important;
    }
    .input-hidden{
      visibility: hidden;
      position: absolute;
      z-index: -1;
    }
    
    .pendiente {
      background-color: #f1f1f1;
    }
    
  </style>
</head>

<body>
  <div class="wrapper ">
    <?php echo sidebar("contact") ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php echo top_bar("Mensajes") ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-info">
                  <h4 class="card-title ">Bandeja de Mensajes</h4>
                  <p class="card-category">Mensajes de contacto enviados desde el sitio web.</p>                  
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th class="text-left" style="">Fecha</th>
                        <th class="text-left">Remitente</th>
                        <th class="text-left">Asunto</th>
                        <th class="text-center" style="width: 10px;">Opciones</th>
                      </thead>
                      <tbody id="messages-wrapper">
                        
                      </tbody>
                    </table>

                    <!--<div class="text-right">
                      <a id="add-serv" class="text-info text-hover-effect py-4 pr-2" style="float: right;" onclick="servicesUIManager.addServiceItem()">
                        <i class="material-icons">add_circle_outline</i> Agregar Servicio
                      </a>
                    </div> -->

                  </div>
                </div>
              </div>
            </div>
            
          </div>



          <div id="message-datail" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title"></h5>
                  <button type="button" class="close" onclick="messagesUIManager.hideItemDetailModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <label class="d-block">
                    <strong class="text-info" >Nombre:</strong> <span id="item-message-name"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Correo electrónico:</strong> <span id="item-message-email"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Teléfono:</strong> <span id="item-message-phone"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Empresa:</strong> <span id="item-message-business"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Fecha:</strong> <span id="item-message-date"></span>
                  </label>
                  <p id="item-message-txt" class="mt-4" ></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" onclick="messagesUIManager.hideItemDetailModal()">Cerrar</button>
                </div>
              </div>
            </div>
          </div>

          
        </div>
      </div>
      <?php echo footer() ?> 
    </div>
  </div>

  <?php echo scripts() ?>
  <script src="./assets/js/messages.js"></script>
  <script>
    messagesClient.init();
  </script>
</body>

</html>