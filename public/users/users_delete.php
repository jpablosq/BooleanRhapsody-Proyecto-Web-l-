  <?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/users_functions.php';

// Verificar autenticación
checkAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método no permitido';
    header('Location: users.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID del vehiculo inválido';
    header('Location: users.php');
    exit();
}

// Verificar que el producto existe
$users = getUserById($id);

if (!$users) {
    $_SESSION['error'] = 'usuario no encontrado';
    header('Location: users.php');
    exit();
}

// Intentar eliminar el producto
if (deleteUser($id)) {
    $_SESSION['success'] = 'El usuario ' . htmlspecialchars($users['nombre']) . ' ' . htmlspecialchars($users['apellidos']) . ' eliminado exitosamente';
} else {
    $_SESSION['error'] = 'Error al eliminar el usuario';
}

header('Location: users.php');
exit();
?>