<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/users_functions.php';

// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todos los usuarios
$users = getAllUsers($usuario['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles/styles_tables.css">
</head>
<body>
    <div class="container">
        <!-- Mensajes de éxito o error -->
        <span>
        <?php if(isset($_SESSION["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION["success"]; ?>
            </div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif;?>
        </span>

        <span>
            <?php if(isset($_SESSION["error"])): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <?php echo $_SESSION["error"]; ?>
                </div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif;?>
        </span>

        <!-- Encabezado -->
        <div class="header">
            <div class="header-title">
                <i class="bi bi-person"></i>
                <h1>Gestión de Usuarios</h1>
            </div>
            <button class="btn-back" onclick="window.location.href='register.php'">
                <i class="bi bi-person-plus"></i>
                Nuevo Usuario
            </button>
            <button class="btn-back" onclick="window.location.href='../index.php'">
                <i class="bi bi-arrow-left"></i>
                Volver
            </button>
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-container">
            <table class="vehicles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($user['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($user['cedula']); ?></td>
                            <td><?php echo htmlspecialchars($user['correo']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="users_view.php?id=<?php echo $user['id_usuario']; ?>" 
                                        class="btn-action btn-view" title="Ver">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <a href="users_update.php?id=<?php echo $user['id_usuario']; ?>" 
                                        class="btn-action btn-edit" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <button
                                        class="btn-action btn-delete"
                                        title="Eliminar"
                                        onclick="confirmDelete(
                                            <?php echo htmlspecialchars($user['id_usuario']); ?>,
                                            '<?php echo htmlspecialchars($user['nombre']); ?>',
                                            '<?php echo htmlspecialchars($user['apellidos']); ?>'
                                        )">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay usuarios registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Estado vacío -->
        <div class="empty-state" style="display: none;">
            <i class="bi bi-person"></i>
            <h3>No hay usuarios registrados</h3>
            <p>Comienza agregando un usuario nuevo</p>
            <button class="btn-add-first" onclick="window.location.href='users_create.php'">
                <i class="bi bi-plus-circle"></i>
                Agregar usuario
            </button>
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
                    <p>
                        ¿Estás seguro de que deseas eliminar al usuario 
                        <strong id="userName"></strong> 
                        <strong id="userLastName"></strong>?
                    </p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="users_delete.php" style="display: inline;">
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
        // Confirmación antes de eliminar un usuario
        function confirmDelete(id, nombre, apellidos) {
            document.getElementById('deleteUserId').value = id;
            document.getElementById('userName').textContent = nombre;
            document.getElementById('userLastName').textContent = apellidos;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>
