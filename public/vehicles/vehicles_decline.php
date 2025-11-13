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
$motivo = trim($_POST['motivo_hidden']);

// Verfica que el motivo no esté vacío
if (empty($motivo)) {
    $_SESSION['error'] = 'Debes ingresar un motivo para rechazar.';
    header('Location: vehicles_request.php');
    exit();
}

// Verificar que el registro existe
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Registro no encontrado';
    header('Location: vehicles_request.php');
    exit();
}


if (declineVehicle($id, $motivo)) {
    $_SESSION['success'] = 'El vehiculo a sido rechazado ';
} else {
    $_SESSION['error'] = 'Error al rechazar el vehiculo';
}

header('Location: vehicles_request.php');
exit();
?>