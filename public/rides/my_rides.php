<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';
// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todos los rides del usuario
$rides = getRidesByUserId($usuario['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Solicitudes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles/styles_tables.css">
</head>
<body>
    <div class="container">
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
                <div class= "alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <?php echo $_SESSION["error"]; ?>
                </div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif;?>
        </span>

        <div class="header">
            <div class="header-title">
                <i class="bi bi-car-front-fill"></i>
                <h1>Gestión de Rides</h1>
            </div>
            <button class="btn-back" onclick="window.location.href='rides_create.php'">
                <i class="bi bi-arrow-left"></i>
                Volver
            </button>
        </div>

        <div class="table-container">
    <table class="vehicles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Chofer</th>
                <th>Vehiculo</th>
                <th>Salida</th>
                <th>Llegada</th>
                <th>Dia</th>
                <th>Campos</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rides as $ride): ?>
            <tr>
                <td><?php echo $ride['id_viaje']; ?></td>
                <td><?php echo $ride['nombre'] . ' ' . $ride['apellidos']; ?></td>
                <td><?php echo $ride['marca'] . ' ' . $ride['modelo']; ?></td>
                <td><?php echo $ride['lugar_salida']; ?></td>
                <td><?php echo $ride['lugar_llegada']; ?></td>
                <td><?php echo $ride['dias_semana']; ?></td>
                <td><?php echo $ride['espacios_disponibles']; ?></td>
                <td><?php echo $ride['tarifa_espacio']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="rides_view.php?id=<?php echo $ride['id_viaje']; ?>" 
                            class="btn-action btn-view" title="Ver">
                            <i class="bi bi-eye-fill"></i>
                        </a>

                        <a href="rides_update.php?id=<?php echo $ride['id_viaje']; ?>" 
                            class="btn-action btn-edit" title="Editar">
                            <i class="bi bi-pencil-fill"></i>
                        </a>

                        <button
                            class="btn-action btn-delete"
                            title="Eliminar"
                            onclick="confirmDelete(
                                <?php echo htmlspecialchars($ride['id_viaje']); ?>,
                                '<?php echo htmlspecialchars($ride['nombre_viaje']); ?>',
                                '<?php echo htmlspecialchars($ride['nombre'] . ' ' . $ride['apellidos']); ?>'
                            )">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
        <div class="empty-state" style="display: none;">
            <i class="bi bi-car-front"></i>
            <h3>No hay rides registrados</h3>
            <p>Comienza agregando tu primer ride</p>
            <button class="btn-add-first" onclick="window.location.href='rides_create.php'">
                <i class="bi bi-plus-circle"></i>
                Agregar Ride
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
                    <p>¿Estás seguro de que deseas eliminar el ride <strong id="rideName"></strong> de <strong id="rideDriverName"></strong> <strong id="rideDriverLastName"></strong>?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="rides_delete.php" style="display: inline;">
                        <input type="hidden" name="id" id="deleteRideId">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id, rideName, name, lastName) {
            document.getElementById('deleteRideId').value = id;
            document.getElementById('rideName').textContent = rideName;
            document.getElementById('rideDriverName').textContent = name;
            document.getElementById('rideDriverLastName').textContent = lastName;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
