<?php
require_once 'conexion_xml.php';

/* =========================
   VALIDACIÓN CENTRAL
========================= */
function validarTesis(array $d, DOMXPath $xp, ?string $claveOriginal = null): array {

    // 🔑 Validar clave única
    $q = "//tesis/tesis[@clave='{$d['clave']}']";
    if ($claveOriginal !== null) {
        $q .= " and @clave!='{$claveOriginal}'";
    }
    if ($xp->query($q)->length > 0) {
        return ['ok'=>false,'msg'=>'La clave ya existe'];
    }

    // 📄 Título mínimo
    if (mb_strlen(trim($d['titulo'])) < 20) {
        return ['ok'=>false,'msg'=>'El título debe tener mínimo 20 caracteres'];
    }

    // 🎓 Alumno existe
    if ($xp->query("//alumnos/alumno[@matricula='{$d['matricula']}']")->length == 0) {
        return ['ok'=>false,'msg'=>'La matrícula no existe'];
    }

    // 👨‍🏫 Director existe
    if ($xp->query("//profesores/profesor[@id='{$d['director']}']")->length == 0) {
        return ['ok'=>false,'msg'=>'El director no existe'];
    }

    // 👥 Codirector (opcional)
    if (!empty($d['codirector']) &&
        $xp->query("//profesores/profesor[@id='{$d['codirector']}']")->length == 0) {
        return ['ok'=>false,'msg'=>'El co-director no existe'];
    }

    // 📊 Avance válido
    if (!is_numeric($d['avance']) || $d['avance'] < 0 || $d['avance'] > 100) {
        return ['ok'=>false,'msg'=>'El avance debe estar entre 0 y 100'];
    }

    // 🚫 Un alumno no puede tener dos tesis en proceso
    $q = "//tesis/tesis[matricula='{$d['matricula']}'
          and (estatus='Registrado' or estatus='En Revisión')]";
    if ($claveOriginal !== null) {
        $q .= " and @clave!='{$claveOriginal}'";
    }
    if ($xp->query($q)->length > 0) {
        return ['ok'=>false,'msg'=>'El alumno ya tiene una tesis en proceso'];
    }

    return ['ok'=>true];
}

/* =========================
   CREAR TESIS
========================= */
function crearTesis(array $d): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $v = validarTesis($d, $xp);
    if (!$v['ok']) return $v;

    $root = $dom->getElementsByTagName('tesis')->item(0);
    if (!$root) {
        $root = $dom->createElement('tesis');
        $dom->documentElement->appendChild($root);
    }

    $t = $dom->createElement('tesis');
    $t->setAttribute('clave', $d['clave']);

    foreach ($d as $k => $v) {
        if ($v !== '') {
            $el = $dom->createElement($k);
            $el->appendChild($dom->createTextNode($v));
            $t->appendChild($el);
        }
    }

    $root->appendChild($t);
    guardarXML($dom);

    return ['ok'=>true,'msg'=>'Tesis registrada correctamente'];
}

/* =========================
   OBTENER TODAS
========================= */
function obtenerTesis(): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);
    $res = [];

    foreach ($xp->query("//tesis/tesis") as $t) {
        $row = ['clave'=>$t->getAttribute('clave')];
        foreach ($t->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE) {
                $row[$c->nodeName] = $c->textContent;
            }
        }
        $res[] = $row;
    }
    return $res;
}

/* =========================
   OBTENER POR CLAVE
========================= */
function obtenerTesisPorClave(string $clave): ?array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$clave']");
    if ($n->length === 0) return null;

    $t = $n->item(0);
    $data = ['clave'=>$t->getAttribute('clave')];

    foreach ($t->childNodes as $c) {
        if ($c->nodeType === XML_ELEMENT_NODE) {
            $data[$c->nodeName] = $c->textContent;
        }
    }
    return $data;
}

/* =========================
   ACTUALIZAR TESIS
========================= */
function actualizarTesis(string $claveOriginal, array $d): array {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$claveOriginal']");
    if ($n->length === 0) {
        return ['ok'=>false,'msg'=>'La tesis no existe'];
    }

    $tesis = $n->item(0);
    $parent = $tesis->parentNode;
    $parent->removeChild($tesis);

    $v = validarTesis($d, $xp, $claveOriginal);
    if (!$v['ok']) {
        $parent->appendChild($tesis);
        guardarXML($dom);
        return $v;
    }

    $nuevo = $dom->createElement('tesis');
    $nuevo->setAttribute('clave', $d['clave']);

    foreach ($d as $k => $v) {
        if ($v !== '') {
            $el = $dom->createElement($k);
            $el->appendChild($dom->createTextNode($v));
            $nuevo->appendChild($el);
        }
    }

    $parent->appendChild($nuevo);
    guardarXML($dom);

    return ['ok'=>true,'msg'=>'Tesis actualizada correctamente'];
}

/* =========================
   ELIMINAR TESIS
========================= */
function eliminarTesis(string $clave): bool {
    $dom = cargarXML();
    $xp  = getXPath($dom);

    $n = $xp->query("//tesis/tesis[@clave='$clave']");
    if ($n->length === 0) return false;

    $t = $n->item(0);
    $t->parentNode->removeChild($t);
    guardarXML($dom);
    return true;
}
