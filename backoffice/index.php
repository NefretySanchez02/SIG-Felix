<?php
require_once 'web-snippets.php';
session_control();
?>
<!doctype html>
<html lang="es">

<head>
  <?php echo html_head() ?>
</head>

<body>
  <div class="wrapper ">
    <?php echo sidebar() ?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php echo top_bar() ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">article</i>
                  </div>
                  <h3 class="card-title">Servicios</h3>
                </div>
                <div class="card-footer">
                  <div class="stats text-info">
                    <i class="material-icons">remove_red_eye</i>
                    <a class="text-info" href="service_section.php">Ver m&aacute;s</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">build</i>
                  </div>
                  <h3 class="card-title">Herramientas</h3>
                </div>
                <div class="card-footer">
                  <div class="stats text-info">
                    <i class="material-icons">remove_red_eye</i>
                    <a class="text-info" href="formacion.php">Ver m&aacute;s</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">settings</i>
                  </div>
                  <h3 class="card-title">Footer</h3>
                </div>
                <div class="card-footer">
                  <div class="stats text-info">
                    <i class="material-icons">remove_red_eye</i>
                    <a class="text-info" href="formacion.php">Ver m&aacute;s</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">info</i>
                  </div>
                  <h3 class="card-title">Contacto</h3>
                </div>
                <div class="card-footer">
                  <div class="stats text-info">
                    <i class="material-icons">remove_red_eye</i>
                    <a class="text-info" href="formacion.php">Ver m&aacute;s</a>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>

  <?php echo scripts() ?>
  <script src="./assets/js/dash.js"></script>
  <script>
    $(document).ready(function () {
      dash.init()

    });

  </script>
</body>

</html>