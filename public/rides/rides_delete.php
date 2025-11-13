<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: my_rides.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID del ride inválido';
    header('Location: my_rides.php');
    exit();
}

// Verificar que el producto existe
$ride = getRidesById($id);

if (!$ride) {
    $_SESSION['error'] = 'Ride no encontrado';
    header('Location: my_rides.php');
    exit();
}

// Intentar eliminar el producto
if (deleteRides($id)) {
    $_SESSION['success'] = 'El ride llamado ' . htmlspecialchars($ride['nombre_viaje']) . ' de ' . htmlspecialchars($ride['nombre']). ' ' . htmlspecialchars($ride['apellido']) . ' eliminado exitosamente';
} else {
    $_SESSION['error'] = 'Error al eliminar el ride';
}

header('Location: my_rides.php');
exit();
?>