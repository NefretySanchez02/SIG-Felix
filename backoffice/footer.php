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

    .input-hidden {
      visibility: hidden;
      position: absolute;
      z-index: -1;
    }

    .pendiente {
      background-color: #f1f1f1;
    }
  </style>
  <link href="https://cdn.quilljs.com/1.1.6/quill.snow.css" rel="stylesheet">
</head>

<body>
  <div class="wrapper ">
    <?php echo sidebar("foot") ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php echo top_bar("Footer") ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-info">
                  <h4 class="card-title ">Bandeja de Footer</h4>
                  <p class="card-category">Listado de datos agregados al sistema</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th class="text-left">Título</th>
                        <th class="text-left">Datos</th>
                      </thead>
                      <tbody id="foot-wrapper">

                      </tbody>
                    </table>
                    <div class="text-right">
                      <a id="add-serv" class="text-info text-hover-effect py-4 pr-2" style="float: right;"
                        onclick="footUIManager.editItem()">
                        <i class="material-icons">mode_edit</i> Editar Información
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div id="foot-detail" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title">Editar Datos</h5>
                  <button type="button" class="close" onclick="footUIManager.hideItemUpdateModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form class="mb-4" method="post" action="javascript:void(0);" enctype="multipart/form-data">
                    <div class="row">
                      <input id="idFoot" type="text" class="d-none" />
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Dirección:</strong></label>
                          <div id="editAddress"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Telefono:</strong></label>
                          <input id="editPhone" type="number" class="form-control px-2" name="phoneEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Correo:</strong></label>
                          <input id="editMail" type="email" class="form-control px-2" name="mailEdit">
                        </div>
                      </div>
                      <div id="submit-button" class="col-12">
                        <button class="btn btn-info" type="buttom" onclick="footUIManager.updateNews()">
                          <span>Editar</span>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                    onclick="footUIManager.hideItemUpdateModal()">Cerrar</button>
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
  <script src="./assets/js/footer.js"></script>
  <script src="https://cdn.quilljs.com/1.1.6/quill.js"></script>
  <script>
    messagesClient.init();
    var quill = new Quill("#editAddress", {
      theme: "snow",
    });
  </script>
</body>

</html>