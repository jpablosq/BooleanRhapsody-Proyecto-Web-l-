<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: vehicles_request.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);

// Verificar que el producto existe
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Registro no encontrado';
    header('Location: vehicles_request.php');
    exit();
}


if (acceptVehicle($id)) {
    $_SESSION['success'] = 'El vehiculo a sido aceptado ';
} else {
    $_SESSION['error'] = 'Error al aceptar el vehiculo';
}

header('Location: vehicles_request.php');
exit();
?>