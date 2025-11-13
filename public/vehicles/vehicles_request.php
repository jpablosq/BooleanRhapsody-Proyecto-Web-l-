<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todos los vehiculos con estado 'pendiente'
$vehicles = getAllVehiclesByStatus('pendiente');
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
                <h1>Gestión de Solicitudes</h1>
            </div>
            <button class="btn-back" onclick="window.location.href='../index.php'">
                <i class="bi bi-arrow-left"></i>
                Volver
            </button>
        </div>

        <div class="table-container">
    <table class="vehicles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Placa</th>
                <th>Color</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
            <tr>
                <td><?php echo $vehicle['id_vehiculo']; ?></td>
                <td><?php echo $vehicle['nombre_usuario']; ?></td>
                <td><?php echo $vehicle['marca']; ?></td>
                <td><?php echo $vehicle['modelo']; ?></td>
                <td><?php echo $vehicle['anio_fabricacion']; ?></td>
                <td><span class="badge-plate"><?php echo $vehicle['placa']; ?></span></td>
                <td><?php echo $vehicle['color']; ?></td>
                <td><?php echo $vehicle['estado']; ?></td>
                <td><?php echo $vehicle['fecha_registro']; ?></td>
                <td>
                <div class="action-buttons">

                    <?php if ($vehicle['estado'] === 'aceptado' || $vehicle['estado'] === 'rechazado'): ?>
                        <a href="vehicles_request_view.php?id=<?php echo $vehicle['id_vehiculo']; ?>" 
                            class="btn-action btn-view" title="Ver">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    <?php else: ?>
                    <?php endif; ?>

                    <a href="vehicles_request_view.php?id=<?php echo $vehicle['id_vehiculo']; ?>" 
                        class="btn-action btn-view" title="Ver">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <button class="btn-action btn-accept" title="Aceptar" onclick="confirmAccept(<?php echo htmlspecialchars($vehicle['id_vehiculo']); ?>, '<?php echo htmlspecialchars($vehicle['marca']); ?>', '<?php echo htmlspecialchars($vehicle['modelo']); ?>')">
                        <i class="bi bi-check-lg"></i>
                    </button>

                    <button class="btn-action btn-delete" title="Rechazar" onclick="confirmDecline(<?php echo htmlspecialchars($vehicle['id_vehiculo']); ?>, '<?php echo htmlspecialchars($vehicle['marca']); ?>', '<?php echo htmlspecialchars($vehicle['modelo']); ?>')">
                        <i class="bi bi-x-lg"></i>
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
            <h3>No hay solicitudes registradas</h3>
            <p>Comienza agregando tu primer solicitud</p>
            <button class="btn-add-first" onclick="window.location.href='vehicles_create.php'">
                <i class="bi bi-plus-circle"></i>
                Agregar Vehículo
            </button>
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
                    <p>¿Estás seguro de que deseas aceptar el vehiculo <strong id="vehicleBrand"></strong> <strong id="vehicleModel"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="vehicles_accept.php" style="display: inline;">
                        <input type="hidden" name="id" id="acceptVehicleId">
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
        function confirmAccept(id, brand, model) {
            document.getElementById('acceptVehicleId').value = id;
            document.getElementById('vehicleBrand').textContent = brand;
            document.getElementById('vehicleModel').textContent = model;
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
                    <p>¿Estás seguro de que deseas rechazar el vehículo <strong id="declineVehicleBrand"></strong> <strong id="declineVehicleModel"></strong>?</p>

                    <label for="motivo" class="form-label mt-2">Motivo del rechazo:</label>
                    <textarea class="form-control" name="motivo" id="motivo" rows="3" required placeholder="Escribe el motivo..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="declineForm" method="POST" action="vehicles_decline.php" style="display: inline;">
                        <input type="hidden" name="id" id="declineVehicleId">
                        <input type="hidden" name="motivo_hidden" id="motivo_hidden">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-lg"></i> Rechazar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDecline(id, brand, model) {
            document.getElementById('declineVehicleId').value = id;
            document.getElementById('declineVehicleBrand').textContent = brand;
            document.getElementById('declineVehicleModel').textContent = model;
            new bootstrap.Modal(document.getElementById('declineModal')).show();
        }

        // Antes de enviar, pasamos el textarea al input hidden
        document.getElementById("declineForm").addEventListener("submit", function () {
            document.getElementById("motivo_hidden").value = document.getElementById("motivo").value;
        });
    </script>
</body>
</html>
