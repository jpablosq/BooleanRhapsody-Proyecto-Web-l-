<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: vehicles.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID del vehiculo inválido';
    header('Location: vehicles.php');
    exit();
}

// Verificar que el producto existe
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'vehiculo no encontrado';
    header('Location: vehicles.php');
    exit();
}

// Intentar eliminar el producto
if (deleteVehicles($id)) {
    $_SESSION['success'] = 'El vehiculo ' . htmlspecialchars($vehicle['marca']) . ' ' . htmlspecialchars($vehicle['modelo']) . ' eliminado exitosamente';
} else {
    $_SESSION['error'] = 'Error al eliminar el vehiculo';
}

header('Location: vehicles.php');
exit();
?>