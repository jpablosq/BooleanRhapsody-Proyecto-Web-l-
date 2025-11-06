<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/registers_funcions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: registers.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID del registro inválido';
    header('Location: registers.php');
    exit();
}

// Verificar que el producto existe
$vehicle = getRegistersByid($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Registro no encontrado';
    header('Location: registers.php');
    exit();
}


if (aceptarRegister($id)) {
    $_SESSION['success'] = 'El vehiculo a sido aceptado ';
} else {
    $_SESSION['error'] = 'Error al aceptar el vehiculo';
}

header('Location: registers.php');
exit();
?>