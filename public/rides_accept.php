<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: rides_request.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);
$espacios = intval($_POST['cantidad'] ?? 0);

// Verificar que la solicitud existe
$rideRequest = getRideRequestById($id);

if (!$rideRequest) {
    $_SESSION['error'] = 'Solicitud no encontrada';
    header('Location: rides_request.php');
    exit();
}


if (acceptRide($id, $espacios)) {
    $_SESSION['success'] = 'El pasajero a sido aceptado ';
} else {
    $_SESSION['error'] = 'Error al aceptar el pasajero';
}

header('Location: rides_request.php');
exit();
?>