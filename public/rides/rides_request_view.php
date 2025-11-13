<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener ride existente
$ride = getRideRequestById($id);

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
    <link rel="stylesheet" href="../assets/styles/styles_view.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles del Usuario y Ride</h1>
            </div>
            <div class="header-actions">
                <a href="rides_request.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="../uploads/<?php echo htmlspecialchars($ride['fotografia'])?>" alt="Imagen del usuario" id="usuarioImagen">
                </div>
                <h2 class="vehicle-name"><?php echo htmlspecialchars($ride['nombre']); ?> <?php echo htmlspecialchars($ride['apellidos']); ?></h2>
                <div class="vehicle-plate"><?php echo htmlspecialchars($ride['telefono']); ?></div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h3>Información General</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['id_solicitud']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Usuario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['nombre']); ?> <?php echo htmlspecialchars($ride['apellidos']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cedula:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['cedula'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telefono:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['telefono'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pago:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['metodo'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total a pagar:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['tarifa_espacio'] * $ride['cantidad_espacios'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cantidad de campos:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['cantidad_espacios'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado Usuario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['estado'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de registro del usuario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['fecha_registro'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nombre del viaje:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['nombre_viaje'])?></span>
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
                        <span class="info-label">Tarifa:</span>
                        <span class="info-value"><?php echo htmlspecialchars($ride['tarifa_espacio']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <?php if($ride['estado'] === 'pendiente'): ?>
                <button class="btn btn-delete" onclick="confirmDecline(<?php echo htmlspecialchars($ride['id_solicitud']); ?>, '<?php echo htmlspecialchars($ride['nombre']); ?>', '<?php echo htmlspecialchars($ride['apellidos']); ?>')">
                    <i class="bi bi-x-lg"></i> Rechazar
                </button>
                <button class="btn btn-accept" onclick="confirmAccept(<?php echo htmlspecialchars($ride['id_solicitud']); ?>, '<?php echo htmlspecialchars($ride['nombre']); ?>', '<?php echo htmlspecialchars($ride['apellidos']); ?>', '<?php echo htmlspecialchars($ride['cantidad_espacios']); ?>')">
                    <i class="bi bi-check-lg"></i></i> Aceptar  
                </button>

            <?php endif; ?>
        </div>
    </div>

<!-- Modal de confirmación para aceptar -->
    <div class="modal fade" id="acceptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas aceptar al pasajero <strong id="userName"></strong> <strong id="userLastName"></strong> en tu ride?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="rides_accept.php" style="display: inline;">
                        <input type="hidden" name="id" id="acceptUserId">
                        <input type="hidden" name="cantidad" id="spaces">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i> Aceptar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmAccept(id, name, lastname, spaces) {
            document.getElementById('acceptUserId').value = id;
            document.getElementById('userName').textContent = name;
            document.getElementById('userLastName').textContent = lastname;
            document.getElementById('spaces').value = spaces;
            new bootstrap.Modal(document.getElementById('acceptModal')).show();
        }
    </script>

    <!-- Modal de confirmación para rechazar -->
    <div class="modal fade" id="declineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas rechazar al pasajero <strong id="userName"></strong> <strong id="userLastName"></strong> en tu ride?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="rides_decline.php" style="display: inline;">
                        <input type="hidden" name="declineUserId" id="declineUserId">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i> Aceptar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDecline(id, name, lastname, spaces) {
            document.getElementById('declineUserId').value = id;
            document.getElementById('userName').textContent = name;
            document.getElementById('userLastName').textContent = lastname;
            new bootstrap.Modal(document.getElementById('declineModal')).show();
        }
    </script>
</body>
</html>
