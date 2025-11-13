<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';

// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todas las solicitudes de rides del chofer
$rides = getDriverRidesRequest($usuario['id_usuario']);
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
    <div class="main-container">
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
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Viaje</th>
                    <th>Salida</th>
                    <th>Llegada</th>
                    <th>Dias</th>
                    <th>Solicita</th>
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
                    <td><?php echo $ride['nombre_viaje']; ?></td>
                    <td><?php echo $ride['lugar_salida']; ?></td>
                    <td><?php echo $ride['lugar_llegada']; ?></td>
                    <td><?php echo $ride['dias_semana']; ?></td>
                    <td><?php echo $ride['cantidad_espacios']; ?></td>
                    <td><?php echo $ride['estado']; ?></td>
                    <td>
                    <div class="action-buttons">
                        <a href="rides_request_view.php?id=<?php echo $ride['id_solicitud']; ?>" 
                            class="btn-action btn-view" title="Ver">
                            <i class="bi bi-eye-fill"></i>
                        </a>

                        <?php if ($ride['estado'] === 'pendiente'): ?>
                            <button class="btn-action btn-accept" title="Aceptar" onclick="confirmAccept(<?php echo htmlspecialchars($ride['id_solicitud']); ?>, '<?php echo htmlspecialchars($ride['nombre']); ?>', '<?php echo htmlspecialchars($ride['apellidos']); ?>', '<?php echo htmlspecialchars($ride['cantidad_espacios']); ?>')">
                                <i class="bi bi-check-lg"></i>
                            </button>

                            <button class="btn-action btn-delete" title="Rechazar" onclick="confirmDecline(<?php echo htmlspecialchars($ride['id_solicitud']); ?>, '<?php echo htmlspecialchars($ride['nombre']); ?>', '<?php echo htmlspecialchars($ride['apellidos']); ?>')">
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

    <?php if(empty($rides)): ?>
        <div class="empty-state">
            <i class="bi bi-car-front"></i>
            <h3>No hay pasajeros registrados en tus rides</h3>
        </div>
    <?php endif; ?>

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
