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
    <?php echo sidebar("services") ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php echo top_bar("Servicios") ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-info">
                  <h4 class="card-title ">Bandeja de Servicios</h4>
                  <p class="card-category">Listado de Servicios agregadas en el sistema.</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-info">
                        <th class="text-left">Título</th>
                        <th class="text-left">Opciones</th>
                      </thead>
                      <tbody id="services-wrapper">

                      </tbody>
                    </table>

                    <div class="text-right">
                      <a id="add-serv" class="text-info text-hover-effect py-4 pr-2" style="float: right;"
                        onclick="servicesUIManager.addServiceModal()">
                        <i class="material-icons">add_circle_outline</i> Agregar servicio
                      </a>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>

          <div id="delete-services" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title">Eliminar Servicio</h5>
                  <button type="button" class="close" onclick="servicesUIManager.hideItemDetailModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p>¿Desea eliminar el servicio <span id="item-services-name"></span>?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" onclick="servicesUIManager.removeItem()">Aceptar</button>
                  <button type="button" class="btn btn-info"
                    onclick="servicesUIManager.hideItemDetailModal()">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
          <div id="view-services" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title">Mostrar Servicio</h5>
                  <button type="button" class="close" onclick="servicesUIManager.hideItemViewModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <label class="d-block">
                    <strong class="text-info">Titulo:</strong> <span id="item-title"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Subtitulo Banner:</strong> <span id="item-subtitle"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Titulo Banner:</strong> <span id="item-titleB"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Texto Banner:</strong> <span id="item-txt"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Url Boton Banner:</strong> <span id="item-urlBtn" style="word-break: break-all;"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Texto Boton Banner:</strong> <span id="item-txtBtn"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Imagen Principal:</strong> <span id="item-img"></span>
                  </label>
                  <label class="d-block">
                    <strong class="text-info">Imagen Trasera:</strong> <span id="item-imgT"></span>
                  </label>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info"
                    onclick="servicesUIManager.hideItemViewModal()">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <div id="services-detail" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title">Editar Servicio</h5>
                  <button type="button" class="close" onclick="servicesUIManager.hideItemUpdateModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form class="mb-4" method="post" action="javascript:void(0);" enctype="multipart/form-data">
                    <div class="row">
                      <input id="idService" type="text" class="d-none" />
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Titulo:</strong></label>
                          <input id="editTitle" type="text" class="form-control px-2" name="titleEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Subtitulo Banner:</strong></label>
                          <input id="editSubtitle" type="text" class="form-control px-2" name="subtitleEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Titulo Banner:</strong></label>
                          <input id="editTitleB" type="text" class="form-control px-2" name="titleBEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Parrafo Banner:</strong></label>
                          <div id="editText"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Texto Boton:</strong></label>
                          <input id="editTxtBtn" type="text" class="form-control px-2" name="txtBtnEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Url Boton:</strong></label>
                          <input id="editUrlBtn" type="text" class="form-control px-2" name="urlBtnEdit">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group form-file-upload form-file-simple">
                          <label class="text-info"><strong>Imagen Principal:</strong></label>
                          <input id="editImg-name" type="text" class="form-control inputFileVisible">
                          <input id="editImg-file" type="file" class="inputFileHidden" accept="image/png, image/jpg, image/jpeg">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group form-file-upload form-file-simple">
                          <label class="text-info"><strong>Imagen Trasera:</strong></label>
                          <input id="editImgT-name" type="text" class="form-control inputFileVisible">
                          <input id="editImgT-file" type="file" class="inputFileHidden"
                            accept="image/png, image/jpg, image/jpeg">
                        </div>
                      </div>
                      <div id="submit-button" class="col-12">
                        <button class="btn btn-info" type="buttom" onclick="servicesUIManager.updateNews()">
                          <span>Editar</span>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                    onclick="servicesUIManager.hideItemUpdateModal()">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <div id="create-services" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 id="item-message-subject" class="modal-title">Crear Servicio</h5>
                  <button type="button" class="close" onclick="servicesUIManager.hideItemCreateModal()">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form class="mb-4" method="post" action="javascript:void(0);" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Titulo:</strong></label>
                          <input id="createTitle" type="text" class="form-control px-2" name="title" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Subtitulo Banner:</strong></label>
                          <input id="createSubtitle" type="text" class="form-control px-2" name="subtitle" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Titulo Banner:</strong></label>
                          <input id="createTitleB" type="text" class="form-control px-2" name="title_banner" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Parrafo Banner:</strong></label>
                          <div id="createText"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Texto Boton:</strong></label>
                          <input id="createTxtBtn" type="text" class="form-control px-2" name="txt_btn" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                          <label class="text-info"><strong>Url Boton:</strong></label>
                          <input id="createUrlBtn" type="url" class="form-control px-2" name="url_btn" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group form-file-upload form-file-simple">
                          <label class="text-info"><strong>Imagen Principal:</strong></label>
                          <input id="createImg-name" type="text" class="form-control inputFileVisible">
                          <input id="createImg-file" type="file" class="inputFileHidden"
                            accept="image/png, image/jpg, image/jpeg" required>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group bmd-form-group form-file-upload form-file-simple">
                          <label class="text-info"><strong>Imagen Trasera:</strong></label>
                          <input id="createImgT-name" type="text" class="form-control inputFileVisible">
                          <input id="createImgT-file" type="file" class="inputFileHidden"
                            accept="image/png, image/jpg, image/jpeg" required>
                        </div>
                      </div>
                      <div id="submit-button" class="col-12">
                        <button class="btn btn-info" type="buttom" onclick="servicesUIManager.addServices()">
                          <span>Crear</span>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                    onclick="servicesUIManager.hideItemCreateModal()">Cerrar</button>
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
  <script src="./assets/js/services_section.js"></script>
  <script src="https://cdn.quilljs.com/1.1.6/quill.js"></script>
  <script>
    messagesClient.init();
    var quill = new Quill("#createText", {
      theme: "snow",
    });
    var quill = new Quill("#editText", {
      theme: "snow",
    });
  </script>
</body>

</html>