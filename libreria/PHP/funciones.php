<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

function dinero($numero) {
    $texto = number_format((float)$numero, 2, ',', '.');
    $texto = preg_replace('/,00$/', '', $texto); // $ 37.800 en vez de $ 37.800,00
    return '$ ' . $texto;
}

function fecha_es($fecha) {
    $meses = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
              'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $t = strtotime($fecha);
    return date('d', $t) . ' de ' . $meses[(int)date('n', $t)] . ' de ' . date('Y', $t);
}

function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
