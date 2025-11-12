<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener producto
$ride = getRidesById($id);

if (!$ride) {
    $_SESSION['error'] = 'Ride no encontrado';
    header('Location: my_rides.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Ride</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_vehiclesAndRegisters_view.css">
</head>
<body>
    <!-- Changed container to main-container to avoid Bootstrap conflicts -->
    <div class="main-container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles del Ride</h1>
            </div>
            <div class="header-actions">
                <a href="my_rides.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="imagenes/mapa.png" alt="Imagen del mapa" id="mapaImagen">
                </div>
                <h2 class="vehicle-name"><?php echo htmlspecialchars($ride['lugar_salida'])?> <div class="arrow">→</div> <?php echo htmlspecialchars($ride['lugar_llegada'])?></h2>
                <div class="vehicle-plate"><?php echo htmlspecialchars($ride['dias_semana']); ?></div>
                <div class="vehicle-year"><?php echo htmlspecialchars($ride['placa']); ?></div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h3>Información General</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Chofer:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['nombre']); ?> <?php echo htmlspecialchars($ride['apellidos']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['id_viaje']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marca:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['marca'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['modelo'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salida:</span>
                        <?php echo htmlspecialchars($ride['lugar_salida'] . ', hora: ' . date("G:i", strtotime($ride['hora_salida']))); ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Llegada:</span>
                        <?php echo htmlspecialchars($ride['lugar_llegada'] . ', hora aproximada: ' . date("G:i", strtotime($ride['hora_llegada']))); ?>

                    </div>
                    <div class="info-item">
                        <span class="info-label">Dia:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['dias_semana']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Espacios disponibles:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['espacios_disponibles']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tarifa:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['tarifa_espacio']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Publicacion del ride:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['fecha_publicacion']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn btn-delete" onclick="confirmDelete(
                                <?php echo htmlspecialchars($ride['id_viaje']); ?>,
                                '<?php echo htmlspecialchars($ride['nombre_viaje']); ?>',
                                '<?php echo htmlspecialchars($ride['nombre'] . ' ' . $ride['apellidos']); ?>'
                            )">
                <i class="bi bi-trash"></i> Eliminar Ride
            </button>
            <a href="rides_update.php?id=<?php echo $ride['id_viaje']; ?>" class="btn btn-edit-main">
                <i class="bi bi-pencil-square"></i> Editar Vehículo
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
