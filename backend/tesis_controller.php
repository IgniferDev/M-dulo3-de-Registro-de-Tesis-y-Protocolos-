<?php
require_once 'funciones_tesis.php';

/* ====== CREAR / ACTUALIZAR ====== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'clave'      => $_POST['clave'],
        'titulo'     => $_POST['titulo'],
        'matricula'  => $_POST['matricula'],
        'director'   => $_POST['director'],
        'codirector' => $_POST['codirector'],
        'linea'      => $_POST['linea'],
        'fecha'      => $_POST['fecha'],
        'avance'     => $_POST['avance'],
        'modalidad'  => $_POST['modalidad'],
        'estatus'    => $_POST['estatus']
    ];

    if (isset($_POST['editar'])) {
        $res = actualizarTesis($_POST['original'], $data);
    } else {
        $res = crearTesis($data);
    }

    header("Location: ../frontend/tesis_form.php?msg=" .
        urlencode($res['msg']) . "&ok=" . ($res['ok'] ? 1 : 0));
    exit;
}

/* ====== ELIMINAR ====== */
if (isset($_GET['del'])) {
    eliminarTesis($_GET['del']);
    header("Location: ../frontend/tesis_listado.php");
    exit;
}
