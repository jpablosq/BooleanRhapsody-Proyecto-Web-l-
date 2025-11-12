<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todas las solicitudes de rides del chofer
$rides = getUserRidesRequest($usuario['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Rides</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_vehiclesAndRegisters.css">
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
            <button class="btn-back" onclick="window.location.href='index.php'">
                <i class="bi bi-arrow-left"></i>
                Volver
            </button>
        </div>

        <div class="table-container">
    <table class="vehicles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Metodo</th>
                <th>Reservados</th>
                <th>Salida</th>
                <th>Llegada</th>
                <th>Dia</th>
                <th>Tarifa</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rides as $ride): ?>
            <tr>
                <td><?php echo $ride['id_solicitud']; ?></td>
                <td><?php echo $ride['nombre']; ?></td>
                <td><?php echo $ride['apellidos']; ?></td>
                <td><?php echo $ride['metodo']; ?></td>
                <td><?php echo $ride['cantidad_espacios']; ?></td>
                <td><?php echo $ride['lugar_salida']; ?></td>
                <td><?php echo $ride['lugar_llegada']; ?></td>
                <td><?php echo $ride['dias_semana']; ?></td>
                <td><?php echo $ride['tarifa_espacio']; ?></td>
                <td><?php echo $ride['estado']; ?></td>
                <td>
                <div class="action-buttons">
                    <a href="registers_view.php?id=<?php echo $register['id_registro']; ?>" 
                        class="btn-action btn-view" title="Ver">
                        <i class="bi bi-eye-fill"></i>
                    </a>

                    <?php if ($ride['estado'] === 'aceptado'): ?>
                        <button class="btn-action btn-delete" title="Aceptar" onclick="confirmAccept(<?php echo htmlspecialchars($ride['id_solicitud']); ?>, '<?php echo htmlspecialchars($ride['lugar_salida']); ?>', '<?php echo htmlspecialchars($ride['lugar_llegada']); ?>', '<?php echo htmlspecialchars($ride['cantidad_espacios']); ?>')">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    <?php else: ?>
                    <?php endif; ?>
                </div>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


    <div class="empty-state" style="display: none;">
        <i class="bi bi-car-front"></i>
        <h3>No hay pasajeros registrados en tus rides</h3>
    </div>

    <!-- Modal de confirmación para aceptar -->
    <div class="modal fade" id="acceptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Baja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas darte de baja del ride de <strong id="departurePlace"></strong> a <strong id="arrivalPlace"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="rides_leave.php" style="display: inline;">
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
        function confirmAccept(id, departurePlace, arrivalPlace, spaces) {
            document.getElementById('acceptUserId').value = id;
            document.getElementById('departurePlace').textContent = departurePlace;
            document.getElementById('arrivalPlace').textContent = arrivalPlace;
            document.getElementById('spaces').value = spaces;
            new bootstrap.Modal(document.getElementById('acceptModal')).show();
        }
    </script>
</body>
</html>
