<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/users_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener usuario
$users = getUserById($id);

if (!$users) {
    $_SESSION['error'] = 'Usuario no encontrado';
    header('Location: users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles/styles_view.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles del Usuario</h1>
            </div>
            <div class="header-actions">
                <a href="users.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="user-card">
                <div class="user-image">
                    <img src="<?php echo htmlspecialchars($users['Fotografia'] ?? '../assets/img/default-user.png'); ?>" 
                         alt="Foto del usuario" class="img-fluid rounded">
                </div>
                <h2 class="user-name">
                    <?php echo htmlspecialchars($users['nombre']); ?> 
                    <?php echo htmlspecialchars($users['apellidos']); ?>
                </h2>
                <div class="user-role">Rol: <?php echo htmlspecialchars($users['rol']); ?></div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h3>Información General</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['nombre']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Apellido:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['apellidos']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cédula:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['cedula']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de nacimiento:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['fecha_nacimiento']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Correo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['correo']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['telefono']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nombre de Usuario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['nombre_usuario']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Contraseña:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['contrasena']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Registro:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['fecha_registro']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Rol:</span>
                        <span class="info-value"><?php echo htmlspecialchars($users['rol']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-danger" 
                    onclick="confirmDelete(
                        <?php echo htmlspecialchars($users['id_usuario']); ?>,
                        '<?php echo htmlspecialchars($users['nombre']); ?>',
                        '<?php echo htmlspecialchars($users['apellidos']); ?>'
                    )">
                <i class="bi bi-trash"></i> Eliminar Usuario
            </button>
            <a href="users_update.php?id=<?php echo $users['id_usuario']; ?>" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Editar Usuario
            </a>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al usuario 
                        <strong id="userName"></strong> 
                        <strong id="userLastName"></strong>?
                    </p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="users_delete.php">
                        <input type="hidden" name="id" id="deleteUserId">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id, nombre, apellidos) {
            document.getElementById('deleteUserId').value = id;
            document.getElementById('userName').textContent = nombre;
            document.getElementById('userLastName').textContent = apellidos;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
