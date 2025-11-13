<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';

// Verificar autenticación
checkAuth();

// Verificar que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: rides_request.php');
    exit();
}

// Obtener id
$id = intval($_POST['declineUserId'] ?? 0);

// Verificar que la solicitud existe
$rideRequest = getRideRequestById($id);

if (!$rideRequest) {
    $_SESSION['error'] = 'Solicitud no encontrada';
    header('Location: rides_request.php');
    exit();
}


if (declineRide($id)) {
    $_SESSION['success'] = 'El pasajero a sido rechazado ';
} else {
    $_SESSION['error'] = 'Error al rechazar el pasajero';
}

header('Location: rides_request.php');
exit();
?>