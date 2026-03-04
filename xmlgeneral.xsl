<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <html lang="es" dir="ltr">
      <head>
        <title>Facultad Ciencias de la Computación</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.min.css"/>
        <link rel="stylesheet" href="css/estilos.css"/>
        <script src="js/external/jquery/jquery.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/busquedas.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
      </head>
      <body class="fondo_main">
        <div class="container col-md-10">
          <br/>
          <div align="center">
            <img src="assets/escudo_buap.png" width="250px" height="250px"/>
            <img src="assets/letras.png" width="350px" height="100px"/>
          </div>
          <br/>
          <h2 align="center" class="titulo">FACULTAD DE CIENCIAS DE LA COMPUTACIÓN</h2>
          <br/>
          <div class="accordion" id="accordionExample">
            <!-- Estudiantes -->
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="col">
                  <button class="btn btn-link text_btn_acordeon" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Estudiantes
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse hidden" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body col-md-12">
                  <xsl:for-each select="facultad/posgrado/maestria/areas">
                    <xsl:apply-templates select="area"/>
                  </xsl:for-each>
                </div>
              </div>
            </div>

            <!-- Profesores -->
            <div class="card">
              <div class="card-header" id="headingTwo">
                <h2 class="col">
                  <button class="btn btn-link collapsed text_btn_acordeon" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Profesores
                  </button>
                </h2>
              </div>
              <div id="collapseTwo" class="collapse hidden" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="form-row">
                  <div class="col" style="margin-top:10px;margin-left:20px">
                    <button type="button" class="btn btn-success" name="button">
                      <xsl:attribute name="onclick">agregar(2)</xsl:attribute>
                      <i class="fas fa-plus-circle"></i>  Agregar Profesor
                    </button>
                  </div>
                  <div class="col input-group" style="margin-top:10px;margin-left:20px">
                    <span class="input-group-text span_search">Búsqueda Por Nombre</span>
                    <input type="text" class="form-control input_search" placeholder="Nombre" id="searchTerm" onkeyup="doSearch('profesores','searchTerm')"/>
                  </div>
                </div>
                <div class="card-body col-md-12">
                  <xsl:for-each select="facultad/posgrado/maestria/personal">
                    <xsl:apply-templates select="profesores"/>
                  </xsl:for-each>
                </div>
              </div>
            </div>

            <!-- Materias -->
            <div class="card">
              <div class="card-header" id="headingThree">
                <h2 class="col">
                  <button class="btn btn-link text_btn_acordeon" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                    Materias
                  </button>
                </h2>
              </div>
              <div id="collapseThree" class="collapse hidden" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="form-row">
                  <div class="col" style="margin-top:10px;margin-left:10px">
                    <button type="button" class="btn btn-success" name="button">
                      <xsl:attribute name="onclick">agregar(3)</xsl:attribute>
                      <i class="fas fa-plus-circle"></i>  Agregar Materia
                    </button>
                  </div>
                  <div class="col input-group" style="margin-top:10px;margin-left:20px">
                    <span class="input-group-text span_search">Búsqueda Por Nombre</span>
                    <input type="text" class="form-control input_search" placeholder="Nombre" id="searchTerm2" onkeyup="doSearch('materias','searchTerm2')"/>
                  </div>
                </div>
                <div class="card-body col-md-12">
                  <table class="table" id="materias">
                    <thead class="thead-dark">
                      <th>Clave</th>
                      <th>Nombre</th>
                      <th>Creditos</th>
                      <th>Horario</th>
                      <th>Salón</th>
                      <th>Periodo</th>
                      <th>Acciones</th>
                    </thead>
                    <tbody>
                      <xsl:for-each select="facultad/posgrado/maestria/materias">
                        <xsl:apply-templates select="materia"/>
                      </xsl:for-each>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tesis (nuevo módulo) -->
            <div class="card">
              <div class="card-header" id="headingFour">
                <h2 class="col">
                  <button class="btn btn-link collapsed text_btn_acordeon" type="button"
                          data-toggle="collapse" data-target="#collapseFour"
                          aria-expanded="false" aria-controls="collapseFour">
                    Tesis
                  </button>
                </h2>
              </div>

              <div id="collapseFour" class="collapse hidden"
                   aria-labelledby="headingFour" data-parent="#accordionExample">

                <div class="form-row">
                  <div class="col" style="margin-top:10px;margin-left:20px">
                    <button type="button" class="btn btn-success">
                      <xsl:attribute name="onclick">agregar(4)</xsl:attribute>
                      <i class="fas fa-plus-circle"></i> Agregar Tesis
                    </button>
                  </div>
                </div>

                <div class="card-body col-md-12">
                  <table class="table" id="tesis">
                    <thead class="thead-dark">
                      <th>Clave</th>
                      <th>Título</th>
                      <th>Matrícula</th>
                      <th>Director</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </thead>
                    <tbody>
                      <xsl:for-each select="facultad/posgrado/maestria/tesis/tesis">
                        <xsl:sort select="@clave"/>
                        <tr>
                          <td><xsl:value-of select="@clave"/></td>
                          <td><xsl:value-of select="titulo"/></td>
                          <td><xsl:value-of select="matricula"/></td>
                          <td><xsl:value-of select="director"/></td>
                          <td><xsl:value-of select="estatus"/></td>
                          <td>
                            <button type="button" class="btn btn-danger" title="Eliminar Tesis">
                              <xsl:attribute name="onclick">
                                eliminar('<xsl:value-of select="@clave"/>',4)
                              </xsl:attribute>
                              <i class="fas fa-trash"></i>
                            </button>

                            <button type="button" class="btn btn-info" title="Editar Tesis" style="margin-left:5px">
                              <xsl:attribute name="onclick">
                                editar('<xsl:value-of select="@clave"/>',4)
                              </xsl:attribute>
                              <i class="fas fa-edit"></i>
                            </button>
                          </td>
                        </tr>
                      </xsl:for-each>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </body>

      <!-- SCRIPTS -->
      <script type="text/javascript">
        function agregar(tipo){
          switch (tipo) {
            case 1: window.location.href = "estudiantes.php"; break;
            case 2: window.location.href = "profesores.php"; break;
            case 3: window.location.href = "materias.php"; break;
            case 4: window.location.href = "tesis.php"; break;
          }
        }

        function editar(id,tipo){
          switch (tipo) {
            case 1: window.location.href = "estudiantes.php?id="+encodeURIComponent(id); break;
            case 2: window.location.href = "profesores.php?id="+encodeURIComponent(id); break;
            case 3: window.location.href = "materias.php?id="+encodeURIComponent(id); break;
            case 4: window.location.href = "tesis.php?id="+encodeURIComponent(id); break;
          }
        }

        function editarEstudiante(id,tipo,area){
          switch (area) {
            case "BD":
            case "SD":
            case "ISI":
            case "CM":
              window.location.href = "estudiantes.php?id="+encodeURIComponent(id)+"&amp;area="+encodeURIComponent(area);
              break;
          }
        }

        function eliminar(id,tipo){
          var aviso="Titulo";
          switch (tipo) {
            case 1: aviso="¿Eliminar Estudiante?"; break;
            case 2: aviso="¿Eliminar Profesor?"; break;
            case 3: aviso="¿Eliminar Materia?"; break;
            case 4: aviso="¿Eliminar Tesis?"; break;
          }

          $( "<div>Esta acción no se puede deshacer... ¿Desea continuar?</div>" ).dialog({
            title:aviso,
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Eliminar": function() {
                $( this ).dialog( "close" );
                $.ajax({
                  data: {acc:"3", id: id, tipo: tipo},
                  url: "include/funciones.php",
                  type: "post",
                  success: function (response) {
                    var r = (response || "").toString().trim();
                    if (r === "0") {
                      $("<div>Ocurrió un error: no se pudo eliminar el registro (posible inexistencia o validación).</div>").dialog({
                        title: "Error",
                        resizable: false,
                        height: "auto",
                        width: 450,
                        modal: true,
                        buttons: {
                          "Entendido": function() { $(this).dialog("close"); }
                        }
                      });
                    } else {
                      $("<div>Acción completada</div>").dialog({
                        title:"Acción Completada",
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                          "Entendido": function() {
                            $( this ).dialog( "close" );
                            location.reload();
                          }
                        }
                      });
                    }
                  },
                  error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                  }
                });
              },
              "Cancelar": function() { $( this ).dialog( "close" ); }
            }
          });
        }
      </script>
    </html>
  </xsl:template>

  <!-- area template (sin cambios funcionales respecto a tu versión previa) -->
  <xsl:template match="area">
    <xsl:variable name="sitio" select="attribute::clave"/>
    <div class="card" style="margin-bottom:20px">
      <div class="card-header d-flex justify-content-center">
        <h2>Area: <xsl:value-of select="$sitio"/></h2>
      </div>
      <button type="button" class="btn btn-success" name="button">
        <xsl:attribute name="onclick">agregar(1)</xsl:attribute>
        <i class="fas fa-plus-circle"></i>  Agregar Estudiante
      </button>
      <div class="form-row">
        <div class="col">
          <h4>Estudiantes:</h4>
        </div>
        <div class="col">
          <div class="col input-group" style="margin-top:10px;margin-left:20px;margin-bottom:10px;">
            <span class="input-group-text span_search">Búsqueda Por Nombre / Matrícula</span>
            <input type="text" class="form-control input_search" placeholder="Nombre / Matrícula">
            <xsl:attribute name="onkeyup">
              doSearch('estudiantes<xsl:value-of select="$sitio"/>','<xsl:value-of select="$sitio"/>')
            </xsl:attribute>
            <xsl:attribute name="id">
              <xsl:value-of select="$sitio"/>
            </xsl:attribute>
            </input>
          </div>
        </div>
      </div>
      <table class="table">
        <xsl:attribute name="id">estudiantes<xsl:value-of select="$sitio"/></xsl:attribute>
        <thead class="thead-dark">
          <th>Matricula</th>
          <th>Nombre</th>
          <th>Edad</th>
          <th>Materias en Curso</th>
          <th>Tutor</th>
          <th>Grados</th>
          <th>Acciones</th>
        </thead>
        <tbody>
          <xsl:for-each select="./alumnos/alumno">
            <xsl:sort select="matricula" />
            <tr>
              <td><xsl:value-of select="matricula"/></td>
              <td><xsl:value-of select="nombre"/></td>
              <td><xsl:value-of select="edad"/></td>
              <td>
                <xsl:for-each select="materias">
                  <xsl:apply-templates select="materia"/>
                </xsl:for-each>
              </td>
              <td><xsl:value-of select="tutor"/></td>
              <td>
                <xsl:for-each select="grados_academicos/grado">
                  <ul>
                    <li><xsl:value-of select="titulo"/></li>
                  </ul>
                </xsl:for-each>
              </td>
              <td>
                <button type="button" name="button" class="btn btn-danger" data-toggle="popover" title="Eliminar Estudiante" style="height:32px;height:32px">
                  <xsl:attribute name="onclick">eliminar(<xsl:value-of select="matricula"/>,1)</xsl:attribute>
                  <i class="fas fa-trash"></i>
                </button>
                <button type="button" name="button" class="btn btn-info" data-toggle="popover" title="Editar Estudiante" style="margin-left:5px;height:32px;height:32px">
                  <xsl:attribute name="onclick">editarEstudiante(<xsl:value-of select="matricula"/>,1,'<xsl:value-of select="$sitio"/>')</xsl:attribute>
                  <i class="fas fa-user-edit"></i>
                </button>
              </td>
            </tr>
          </xsl:for-each>
        </tbody>
      </table>
    </div>
  </xsl:template>

  <!-- profesores y materia templates mantengo como estaban (funcionales) -->
  <xsl:template match="profesores">
    <div class="container col-md-12">
      <table class="table" id="profesores">
        <thead class="thead-dark">
          <th>ID</th>
          <th>Nombre</th>
          <th>E-mail</th>
          <th>Materias impartidas</th>
          <th>Publicaciones (Titulos)</th>
          <th>Acciones</th>
        </thead>
        <tbody>
          <xsl:for-each select="profesor">
            <xsl:sort select="attribute::id_profesor" data-type="number" />
            <tr>
              <td><xsl:value-of select="attribute::id_profesor"/></td>
              <td><xsl:value-of select="nombre"/></td>
              <td style="width: 150px;"><xsl:value-of select="correo_electronico"/></td>
              <td>
                <xsl:for-each select="materias_imp">
                  <xsl:apply-templates select="materia"/>
                </xsl:for-each>
              </td>
              <td style="width: 450px;">
                <ul>
                  <xsl:for-each select="publicaciones/publicacion">
                    <li><xsl:value-of select="titulo"/></li>
                  </xsl:for-each>
                </ul>
              </td>
              <td>
                <button type="button" name="button" class="btn btn-danger" data-toggle="popover" title="Eliminar Profesor" style="height:32px;height:32px">
                  <xsl:attribute name="onclick">eliminar(<xsl:value-of select="attribute::id_profesor"/>,2)</xsl:attribute>
                  <i class="fas fa-trash"></i>
                </button>
                <button type="button" name="button" class="btn btn-info" data-toggle="popover" title="Editar Profesor" style="margin-left:5px;height:32px;height:32px">
                  <xsl:attribute name="onclick">editar(<xsl:value-of select="attribute::id_profesor"/>,2)</xsl:attribute>
                  <i class="fas fa-user-edit"></i>
                </button>
              </td>
            </tr>
          </xsl:for-each>
        </tbody>
      </table>
    </div>
  </xsl:template>

  <xsl:template match="materia">
    <xsl:choose>
      <xsl:when test="./attribute::clave_mat!=''">
        <xsl:choose>
          <xsl:when test="./attribute::clave_mat=100409344">
            <p>Optativa II (Topicos selectos de BD-B)</p>
          </xsl:when>
          <!-- mapping mantenido -->
        </xsl:choose>
      </xsl:when>
      <xsl:otherwise>
        <tr>
          <td><xsl:value-of select="clave_mat"/></td>
          <td><xsl:value-of select="nombre"/></td>
          <td><xsl:value-of select="creditos"/></td>
          <td><xsl:value-of select="horario"/></td>
          <td><xsl:value-of select="salon"/></td>
          <td><xsl:value-of select="periodo"/></td>
          <td>
            <button type="button" name="button" class="btn btn-danger" data-toggle="popover" title="Eliminar Materia" style="height:32px;height:32px">
              <xsl:attribute name="onclick">eliminar(<xsl:value-of select="clave_mat"/>,3)</xsl:attribute>
              <i class="fas fa-trash"></i>
            </button>
            <button type="button" name="button" class="btn btn-info" data-toggle="popover" title="Editar Materia" style="margin-left:5px;height:32px;height:32px">
              <xsl:attribute name="onclick">editar(<xsl:value-of select="clave_mat"/>,3)</xsl:attribute>
              <i class="fas fa-user-edit"></i>
            </button>
          </td>
        </tr>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>