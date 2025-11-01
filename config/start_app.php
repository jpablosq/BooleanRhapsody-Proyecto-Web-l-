<?php
    session_start();
    define("SITIO", "Hello PHP");
    date_default_timezone_set("America/Costa_Rica");
    $fecha = new DateTime();

    // Mostrar errores de PHP (solo en desarrollo)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

?>