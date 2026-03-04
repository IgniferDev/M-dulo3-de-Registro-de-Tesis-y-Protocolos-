<?php
// include/funciones.php
switch ($_POST["acc"]) {
  case '1': # nuevo Registro del XML
    // Obtener variables (legacy: mantiene compatibilidad con tu proyecto)
    foreach($_POST as $nombre_campo => $valor) {
      eval("\$" . $nombre_campo . " = \$_POST[\"".$nombre_campo."\"];");
    }
    $xml = simplexml_load_file("../xmlgeneral.xml");

    switch ($tipo) {
      case 1: // Insertar Alumno
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area[@clave='".$area."']/alumnos/alumno[matricula='".$matricula."']");
        if ( count($dato) > 0 ) { echo "0"; break; }

        switch ( $area ) {
          case "BD": $areaIndice = 0; break;
          case "SD": $areaIndice = 1; break;
          case "ISI": $areaIndice = 2; break;
          case "CM": $areaIndice = 3; break;
          default: $areaIndice = 0; break;
        }
        $alumno = $xml->posgrado->maestria->areas->area[$areaIndice]->alumnos->addChild('alumno');
        $alumno->addChild('matricula', $matricula);
        $alumno->addChild('nombre', $nombre);
        $alumno->addChild('fecha_nac', $fecha_nac);
        $alumno->addChild('edad', $edad);
        $alumno->addChild('tutor', $tutor);
        $alumno->addChild('prom_actual', $promedio);
        $alumno->addChild('creditos', $creditos);
        $alumno->addChild('email', $correo);
        $alumno->addChild('telefono', $telefono);
        $alumno->addChild('genero', $genero);
        $alumno->addChild('no_cvu', $no_cvu);
        $alumno->addChild('curp', $curp);
        $alumno->addChild('rfc', $rfc);
        $grados_academicos = $alumno->addChild('grados_academicos');
        $grado = $grados_academicos->addChild('grado');
        $grado->addChild('titulo', $titulo);
        $grado->addChild('promedio', $promedio);
        $grado->addChild('escuela', $escuela);
        $materias_imp = $alumno->addChild('materias');
        if (isset($materias) && is_array($materias)) {
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
        }
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 2: // Insertar Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor='".$id_profesor."']");
        if ( count($dato) > 0 ) { echo "0"; break; }

        $profesor = $xml->posgrado->maestria->personal->profesores->addChild('profesor');
        $profesor->addAttribute('id_profesor', $id_profesor);
        $profesor->addChild('nombre', $nombre);
        $profesor->addChild('ubicacion', $cubiculo);
        $profesor->addChild('correo_electronico', $correo);
        $publicaciones = $profesor->addChild('publicaciones');
        $publicacion = $publicaciones->addChild('publicacion');
        $publicacion->addChild('autores', $autores);
        $publicacion->addChild('titulo', $titulo_pub);
        $publicacion->addChild('anio', $anio);
        $materias_imp = $profesor->addChild('materias_imp');
        if (isset($materias) && is_array($materias)) {
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
        }
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 3: // Insertar Materia
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat='".$clave_mat."']");
        if ( count($dato) > 0 ) { echo "0"; break; }

        $materia = $xml->posgrado->maestria->materias->addChild('materia');
        $materia->addAttribute('es', "MA");
        $materia->addChild('clave_mat', $clave_mat);
        $materia->addChild('nombre', $nombre);
        $materia->addChild('creditos', $creditos);
        $materia->addChild('horario', $horario);
        $materia->addChild('salon', $salon);
        $materia->addChild('periodo', $periodo);
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 4: // Insertar Tesis
        // clave enviada en 'clave'
        if (!isset($clave) || trim($clave) == "") { echo "0"; break; }
        $dato = $xml->xpath("/facultad/posgrado/maestria/tesis/tesis[@clave='".$clave."']");
        if ( count($dato) > 0 ) { echo "0"; break; }

        // validar alumno
        $foundAlumno = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='".$matricula."']");
        if (count($foundAlumno) == 0) { echo "0"; break; }

        // validar director
        $foundDirector = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor='".$director."']");
        if (count($foundDirector) == 0) { echo "0"; break; }

        // validar avance
        if (!is_numeric($avance) || $avance < 0 || $avance > 100) { echo "0"; break; }

        // validar que el alumno no tenga otra tesis en proceso
        $qproc = "/facultad/posgrado/maestria/tesis/tesis[matricula='".$matricula."' and (estatus='Registrado' or estatus='En Revisión')]";
        if (count($xml->xpath($qproc)) > 0) { echo "0"; break; }

        // asegurar contenedor <tesis>
        $rootTesis = $xml->xpath("/facultad/posgrado/maestria/tesis");
        if (count($rootTesis) == 0) {
          $xml->posgrado->maestria->addChild('tesis');
          $rootTesis = $xml->xpath("/facultad/posgrado/maestria/tesis");
        }
        $tesisRoot = $rootTesis[0];

        // insertar
        $t = $tesisRoot->addChild('tesis');
        $t->addAttribute('clave', $clave);
        $t->addChild('titulo', $titulo);
        $t->addChild('matricula', $matricula);
        $t->addChild('director', $director);
        if (isset($codirector) && trim($codirector) !== "") $t->addChild('codirector', $codirector);
        $t->addChild('linea', $linea);
        $t->addChild('fecha_registro', (isset($fecha_registro) && $fecha_registro !== "" ? $fecha_registro : (isset($fecha)?$fecha:"")));
        $t->addChild('avance', $avance);
        $t->addChild('modalidad', $modalidad);
        $t->addChild('estatus', $estatus);

        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;
    }
    break;

  case '2': # editar Registro del XML
    // Obtener variables
    foreach($_POST as $nombre_campo => $valor) {
      eval("\$" . $nombre_campo . " = \$_POST[\"".$nombre_campo."\"];");
    }
    $xml = simplexml_load_file("../xmlgeneral.xml");

    switch ($tipo) {
      case 1: // Editar Estudiante
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='".$id."']");
        if (count($dato) > 0) { // eliminar y reinsertar
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        switch ( $area ) {
          case "BD": $areaIndice = 0; break;
          case "SD": $areaIndice = 1; break;
          case "ISI": $areaIndice = 2; break;
          case "CM": $areaIndice = 3; break;
          default: $areaIndice = 0; break;
        }
        $alumno = $xml->posgrado->maestria->areas->area[$areaIndice]->alumnos->addChild('alumno');
        $alumno->addChild('matricula', $id);
        $alumno->addChild('nombre', $nombre);
        $alumno->addChild('fecha_nac', $fecha_nac);
        $alumno->addChild('edad', $edad);
        $alumno->addChild('tutor', $tutor);
        $alumno->addChild('prom_actual', $promedio);
        $alumno->addChild('creditos', $creditos);
        $alumno->addChild('email', $correo);
        $alumno->addChild('telefono', $telefono);
        $alumno->addChild('genero', $genero);
        $alumno->addChild('no_cvu', $no_cvu);
        $alumno->addChild('curp', $curp);
        $alumno->addChild('rfc', $rfc);
        $grados_academicos = $alumno->addChild('grados_academicos');
        $grado = $grados_academicos->addChild('grado');
        $grado->addChild('titulo', $titulo);
        $grado->addChild('promedio', $promedio);
        $grado->addChild('escuela', $escuela);
        $materias_imp = $alumno->addChild('materias');
        if (isset($materias) && is_array($materias)) {
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
        }
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 2: // Editar Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor='".$id."']");
        if (count($dato) > 0) {
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        $profesor = $xml->posgrado->maestria->personal->profesores->addChild('profesor');
        $profesor->addAttribute('id_profesor', $id);
        $profesor->addChild('nombre', $nombre);
        $profesor->addChild('ubicacion', $cubiculo);
        $profesor->addChild('correo_electronico', $correo);
        $publicaciones = $profesor->addChild('publicaciones');
        $publicacion = $publicaciones->addChild('publicacion');
        $publicacion->addChild('autores', $autores);
        $publicacion->addChild('titulo', $titulo_pub);
        $publicacion->addChild('anio', $anio);
        $materias_imp = $profesor->addChild('materias_imp');
        if (isset($materias) && is_array($materias)) {
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
        }
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 3: // Editar Materia
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat='".$id."']");
        if (count($dato) > 0) {
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        $materia = $xml->posgrado->maestria->materias->addChild('materia');
        $materia->addAttribute('es', "MA");
        $materia->addChild('clave_mat', $id);
        $materia->addChild('nombre', $nombre);
        $materia->addChild('creditos', $creditos);
        $materia->addChild('horario', $horario);
        $materia->addChild('salon', $salon);
        $materia->addChild('periodo', $periodo);
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 4: // Editar Tesis
        // id = original clave (hidden input), clave = posible nueva clave (field)
        if (!isset($id) || trim($id) === "") { echo "0"; break; }

        // buscar por la clave original ($id)
        $ruta = "/facultad/posgrado/maestria/tesis/tesis[@clave='".$id."']";
        $nodos = $xml->xpath($ruta);

        if (count($nodos) != 1) { echo "0"; break; }

        // validaciones de actualización
        // 1) si se cambió la clave, validar que no exista otra con la nueva clave
        $newClave = (isset($clave) ? $clave : $id);
        if ($newClave !== $id) {
          $chk = $xml->xpath("/facultad/posgrado/maestria/tesis/tesis[@clave='".$newClave."']");
          if (count($chk) > 0) { echo "0"; break; }
        }

        // 2) alumno existe?
        $foundAlumno = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='".$matricula."']");
        if (count($foundAlumno) == 0) { echo "0"; break; }

        // 3) director existe?
        $foundDirector = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor='".$director."']");
        if (count($foundDirector) == 0) { echo "0"; break; }

        // 4) avance válido
        if (!is_numeric($avance) || $avance < 0 || $avance > 100) { echo "0"; break; }

        // 5) único: alumno no puede tener otra tesis en proceso (excluyendo la actual)
        $qproc = "/facultad/posgrado/maestria/tesis/tesis[matricula='".$matricula."' and (estatus='Registrado' or estatus='En Revisión')]";
        $foundProc = $xml->xpath($qproc);
        if (count($foundProc) > 0) {
          // permitir si la única coincidencia es la misma tesis (mismo id)
          foreach ($foundProc as $fp) {
            $attrs = $fp->attributes();
            if ((string)$attrs['clave'] != $id) { echo "0"; break 2; }
          }
        }

        // ya validado: actualizar el nodo (evitamos eliminar/insertar para no cambiar orden innecesario)
        $t = $nodos[0];

        // cambiar atributo 'clave' si se editó
        if ($newClave !== $id) {
          $t['clave'] = $newClave;
        }

        // actualizar/crear nodos hijos
        $t->titulo = $titulo;
        $t->matricula = $matricula;
        $t->director = $director;
        if (isset($codirector) && trim($codirector) !== "") $t->codirector = $codirector;
        else { // si se dejó vacío, eliminar nodo si existe
          if (isset($t->codirector)) {
            unset($t->codirector);
          }
        }
        $t->linea = $linea;
        // usar fecha_registro consistentemente
        $t->fecha_registro = (isset($fecha_registro) && $fecha_registro !== "" ? $fecha_registro : (isset($fecha)?$fecha:""));
        $t->avance = $avance;
        $t->modalidad = $modalidad;
        $t->estatus = $estatus;

        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;
    }
    break;

  case '3': # eliminar Registro del XML
    $id=$_POST["id"];
    $tipo=$_POST["tipo"];
    $xml = simplexml_load_file("../xmlgeneral.xml");
    switch ($tipo) {
      case 1: // Estudiante
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='".$id."']");
        if (count($dato) > 0) {
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
          $xml->asXML("../xmlgeneral.xml");
          echo "OK";
        } else { echo "0"; }
        break;

      case 2: // Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor='".$id."']");
        if (count($dato) > 0) {
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
          $xml->asXML("../xmlgeneral.xml");
          echo "OK";
        } else { echo "0"; }
        break;

      case 3: // Materia (y limpiezas)
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat='".$id."']");
        if (count($dato) > 0) {
          $node = $dato[0];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        // limpiar referencias en profesores
        $datoP = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor/materias_imp/materia[@clave_mat='".$id."']");
        for ($i=0; $i < count($datoP); $i++) {
          $node = $datoP[$i];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        // limpiar referencias en alumnos
        $datoA = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno/materias/materia[@clave_mat='".$id."']");
        for ($i=0; $i < count($datoA); $i++) {
          $node = $datoA[$i];
          $dom = dom_import_simplexml($node);
          $dom->parentNode->removeChild($dom);
        }
        $xml->asXML("../xmlgeneral.xml");
        echo "OK";
        break;

      case 4: // Tesis
        $ruta = "/facultad/posgrado/maestria/tesis/tesis[@clave='".$id."']";
        $nodos = $xml->xpath($ruta);
        if (count($nodos) == 1) {
          $dom = dom_import_simplexml($nodos[0]);
          $dom->parentNode->removeChild($dom);
          $xml->asXML("../xmlgeneral.xml");
          echo "OK";
        } else {
          echo "0";
        }
        break;
    }
    break;

  default:
    echo "0";
    break;
}
?>