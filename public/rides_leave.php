<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: my_rides_request.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);
$espacios = intval($_POST['cantidad'] ?? 0);

// Verificar que la solicitud existe
$rideRequest = getRideRequestById($id);

if (!$rideRequest) {
    $_SESSION['error'] = 'Solicitud no encontrada';
    header('Location: my_rides_request.php');
    exit();
}


if (leaveRide($id, $espacios)) {
    $_SESSION['success'] = 'Te has dado de baja del ride correctamente';
} else {
    $_SESSION['error'] = 'Error al darte de baja del ride';
}

header('Location: my_rides_request.php');
exit();
?>