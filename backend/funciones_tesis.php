<?php
require_once 'conexion_xml.php';

/* ================= VALIDACIONES ================= */

function validarTesis(array $d, DOMXPath $xp): array {

    // Clave única
    if ($xp->query("//tesis/tesis[@clave='{$d['clave']}']")->length > 0) {
        return ['ok'=>false,'msg'=>'La clave ya existe'];
    }

    // Título mínimo
    if (strlen($d['titulo']) < 20) {
        return ['ok'=>false,'msg'=>'El título debe tener mínimo 20 caracteres'];
    }

    // Alumno existe
    if ($xp->query("//alumnos/alumno[@matricula='{$d['matricula']}']")->length == 0) {
        return ['ok'=>false,'msg'=>'La matrícula no existe'];
    }

    // Director existe
    if ($xp->query("//profesores/profesor[@id='{$d['director']}']")->length == 0) {
        return ['ok'=>false,'msg'=>'El director no existe'];
    }

    // Co-director opcional
    if (!empty($d['codirector'])) {
        if ($xp->query("//profesores/profesor[@id='{$d['codirector']}']")->length == 0) {
            return ['ok'=>false,'msg'=>'El co-director no existe'];
        }
    }

    // Avance
    if ($d['avance'] < 0 || $d['avance'] > 100) {
        return ['ok'=>false,'msg'=>'Avance inválido'];
    }

    // Un alumno no puede tener dos tesis en proceso
    $q = "//tesis/tesis[matricula='{$d['matricula']}' and (estatus='Registrado' or estatus='En Revisión')]";
    if ($xp->query($q)->length > 0) {
        return ['ok'=>false,'msg'=>'El alumno ya tiene una tesis en proceso'];
    }

    return ['ok'=>true];
}

/* ================= CRUD ================= */

function crearTesis(array $d): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $v = validarTesis($d, $xp);
    if (!$v['ok']) return $v;

    $root = $dom->getElementsByTagName('tesis')->item(0);

    $t = $dom->createElement('tesis');
    $t->setAttribute('clave', $d['clave']);

    foreach ($d as $k=>$v) {
        $el = $dom->createElement($k, $v);
        $t->appendChild($el);
    }

    $root->appendChild($t);
    guardarXML($dom);

    return ['ok'=>true,'msg'=>'Tesis registrada'];
}

function obtenerTesis(): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $out = [];
    foreach ($xp->query("//tesis/tesis") as $t) {
         /** @var DOMElement $t */
        $row = ['clave' => $t->getAttribute('clave')];
        foreach ($t->childNodes as $c) {
            if ($c->nodeType === 1) {
                $row[$c->nodeName] = $c->textContent;
            }
        }
        $out[] = $row;
    }
    return $out;
}

function eliminarTesis(string $clave): void {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$clave']")->item(0);
    if ($n) {
        $n->parentNode->removeChild($n);
        guardarXML($dom);
    }
}

function obtenerTesisPorClave(string $clave): ?array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$clave']");
    if ($n->length === 0) return null;

    $t = $n->item(0);
    /** @var DOMElement $t */

    $data = ['clave' => $t->getAttribute('clave')];
    foreach ($t->childNodes as $c) {
        if ($c->nodeType === 1) {
            $data[$c->nodeName] = $c->textContent;
        }
    }
    return $data;
}

function actualizarTesis(string $clave, array $d): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$clave']");
    if ($n->length === 0) return ['ok'=>false,'msg'=>'No existe'];

    $t = $n->item(0);
    $parent = $t->parentNode;
    $parent->removeChild($t); // evitar conflicto de validación

    $v = validarTesis($d, $xp);
    if (!$v['ok']) {
        $parent->appendChild($t);
        guardarXML($dom);
        return $v;
    }

    $nuevo = $dom->createElement('tesis');
    $nuevo->setAttribute('clave', $d['clave']);

    foreach ($d as $k=>$v) {
        $nuevo->appendChild($dom->createElement($k, $v));
    }

    $parent->appendChild($nuevo);
    guardarXML($dom);

    return ['ok'=>true,'msg'=>'Tesis actualizada'];
}
