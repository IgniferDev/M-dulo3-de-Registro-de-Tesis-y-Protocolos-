<?php
// tesis.php
// Inicializar variables
$clave = $titulo = $matricula = $director = $codirector = $linea = $fecha_registro = $avance = $modalidad = $estatus = "";

// Si viene id por GET -> precargar para edición
if (isset($_GET['id']) && $_GET['id'] !== "") {
  $id_param = $_GET['id'];
  $xml = simplexml_load_file("xmlgeneral.xml");
  $tesisNodes = $xml->xpath("/facultad/posgrado/maestria/tesis/tesis[@clave='".$id_param."']");
  if (count($tesisNodes) > 0) {
    $t = $tesisNodes[0];
    // CORRECCIÓN: acceder al atributo correctamente
    $clave = (string) $t['clave'];
    $titulo = (string) $t->titulo;
    $matricula = (string) $t->matricula;
    $director = (string) $t->director;
    $codirector = (string) $t->codirector;
    // admitir tanto fecha_registro como fecha si existiera en XML antiguo
    if (isset($t->fecha_registro) && trim((string)$t->fecha_registro) !== "") {
      $fecha_registro = (string) $t->fecha_registro;
    } elseif (isset($t->fecha) && trim((string)$t->fecha) !== "") {
      $fecha_registro = (string) $t->fecha;
    }
    $linea = (string) $t->linea;
    $avance = (string) $t->avance;
    $modalidad = (string) $t->modalidad;
    $estatus = (string) $t->estatus;
  }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registro de Tesis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.min.css"/>
    <link rel="stylesheet" href="css/estilos.css"/>
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
  </head>
  <body class="fondo_main">
    <br/><br/>
    <div class="container">
      <h2 align="center" class="titulo"><?php echo ($clave!="" ? "Editar Tesis" : "Registrar Tesis"); ?></h2>
      <form id="formulario" name="formulario" action="javascript:guardar();">
        <div class="card">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Clave de Protocolo</label>
                <input type="text" name="clave" id="clave" class="form-control" required
                       value="<?php echo htmlspecialchars($clave); ?>" <?php echo ($clave!="" ? "readonly" : ""); ?>>
                <input type="hidden" name="id" id="id" value="<?php echo ($clave!=""?htmlspecialchars($clave):""); ?>" />
                <input type="hidden" name="acc" id="acc" value="<?php echo ($clave!=""?"2":"1"); ?>" />
                <input type="hidden" name="tipo" id="tipo" value="4" />
              </div>
              <div class="form-group col-md-8">
                <label>Título de la Tesis</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required minlength="20"
                       value="<?php echo htmlspecialchars($titulo); ?>">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Matrícula del Alumno</label>
                <input type="text" name="matricula" id="matricula" class="form-control" required
                       value="<?php echo htmlspecialchars($matricula); ?>">
              </div>
              <div class="form-group col-md-4">
                <label>Director de Tesis</label>
                <select name="director" id="director" class="form-control" required>
                  <option value="">-- Selecciona --</option>
                  <?php
                    // poblar select de directores desde xmlgeneral.xml
                    $xmlAll = simplexml_load_file("xmlgeneral.xml");
                    $profes = $xmlAll->xpath("/facultad/posgrado/maestria/personal/profesores/profesor");
                    foreach ($profes as $p) {
                      $idp = (string) $p['id_profesor'];
                      $n = (string) $p->nombre;
                      $sel = ($director == $idp) ? 'selected' : '';
                      echo "<option value=\"".htmlspecialchars($idp)."\" $sel>".htmlspecialchars($idp)." - ".htmlspecialchars($n)."</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label>Co-Director (opcional)</label>
                <select name="codirector" id="codirector" class="form-control">
                  <option value="">-- Ninguno --</option>
                  <?php
                    foreach ($profes as $p) {
                      $idp = (string) $p['id_profesor'];
                      $n = (string) $p->nombre;
                      $sel = ($codirector == $idp) ? 'selected' : '';
                      echo "<option value=\"".htmlspecialchars($idp)."\" $sel>".htmlspecialchars($idp)." - ".htmlspecialchars($n)."</option>";
                    }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Línea de Investigación</label>
                <select name="linea" id="linea" class="form-control" required>
                  <option value="">-- Selecciona --</option>
                  <option value="IA" <?php if($linea=='IA') echo 'selected'; ?>>IA</option>
                  <option value="BD" <?php if($linea=='BD') echo 'selected'; ?>>BD</option>
                  <option value="Redes" <?php if($linea=='Redes') echo 'selected'; ?>>Redes</option>
                  <option value="Ingenieria de Sw" <?php if($linea=='Ingenieria de Sw') echo 'selected'; ?>>Ingeniería de Sw</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label>Fecha de Registro</label>
                <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" required
                       value="<?php echo htmlspecialchars($fecha_registro); ?>">
              </div>
              <div class="form-group col-md-4">
                <label>Porcentaje de Avance</label>
                <input type="number" name="avance" id="avance" min="0" max="100" class="form-control" required
                       value="<?php echo htmlspecialchars($avance); ?>">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Modalidad</label>
                <select name="modalidad" id="modalidad" class="form-control" required>
                  <option value="">-- Selecciona --</option>
                  <option value="Tesis Experimental" <?php if($modalidad=='Tesis Experimental') echo 'selected'; ?>>Tesis Experimental</option>
                  <option value="Tesis Teórica" <?php if($modalidad=='Tesis Teórica') echo 'selected'; ?>>Tesis Teórica</option>
                  <option value="Desarrollo Tecnológico" <?php if($modalidad=='Desarrollo Tecnológico') echo 'selected'; ?>>Desarrollo Tecnológico</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Estatus</label>
                <select name="estatus" id="estatus" class="form-control" required>
                  <option value="">-- Selecciona --</option>
                  <option value="Registrado" <?php if($estatus=='Registrado') echo 'selected'; ?>>Registrado</option>
                  <option value="En Revisión" <?php if($estatus=='En Revisión') echo 'selected'; ?>>En Revisión</option>
                  <option value="Aprobado" <?php if($estatus=='Aprobado') echo 'selected'; ?>>Aprobado</option>
                  <option value="Defendido" <?php if($estatus=='Defendido') echo 'selected'; ?>>Defendido</option>
                </select>
              </div>
            </div>

            <br/>
            <div align="center" style="margin-bottom: 20px;">
              <button type="submit" class="btn btn-primary" style="width:350px;"><?php echo ($clave!="" ? "Guardar cambios" : "Guardar"); ?></button>
            </div>

          </div>
        </div>
      </form>
    </div>

    <script>
      function guardar() {
        // validación mínima en cliente
        if ($("#titulo").val().length < 20) {
          alert("El título debe tener al menos 20 caracteres.");
          return;
        }
        if (!$("#matricula").val()) { alert("Capture la matrícula del alumno."); return; }
        if (!$("#director").val()) { alert("Seleccione el director."); return; }

        $.ajax({
          url: "include/funciones.php",
          type: "post",
          data: $("#formulario").serialize(),
          success: function(response) {
            var r = (response || "").toString().trim();
            if (r === "0") {
              $("<div>Ocurrió un error en las validaciones (clave duplicada, alumno/profesor inexistente, o regla de tesis en proceso).</div>").dialog({
                title: "Error",
                resizable: false,
                height: "auto",
                width: 500,
                modal: true,
                buttons: {
                  "Entendido": function() { $(this).dialog("close"); }
                }
              });
            } else {
              $("<div>Acción completada.</div>").dialog({
                title: "Acción completada",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                  "Entendido": function() { $(this).dialog("close"); window.location.href='xmlgeneral.xml'; }
                }
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert("Error al comunicar con el servidor: " + xhr.status);
          }
        });
      }
    </script>
  </body>
</html>