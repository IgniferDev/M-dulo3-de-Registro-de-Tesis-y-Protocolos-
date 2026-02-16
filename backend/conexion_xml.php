<?php
define('XML_PATH', __DIR__ . '/../data/maestria.xml');

function cargarXML(): DOMDocument {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->load(XML_PATH);
    return $dom;
}

function guardarXML(DOMDocument $dom): bool {
    return $dom->save(XML_PATH) !== false;
}

function getXPath(DOMDocument $dom): DOMXPath {
    return new DOMXPath($dom);
}
