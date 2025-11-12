<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Usuario registrado
$usuario = $_SESSION["usuario"];

// Obtener ride existente
$ride = getRidesById($id);

// Obtener los vehiculos del usuario registrado
$vehicles = getAllUserVehicles($usuario['id_usuario']);

if (!$ride) {
    $_SESSION['error'] = 'Ride no encontrado';
    header('Location: my_rides.php');
    exit();
}

// Capturar datos
$id_viaje = $ride['id_viaje'];
$id_vehiculo = $ride['id_vehiculo'];
$nombre_viaje = $ride['nombre_viaje'];    
$lugar_salida = $ride['lugar_salida'];
$hora_salida = $ride['hora_salida'];
$lugar_llegada = $ride['lugar_llegada'];
$hora_llegada = $ride['hora_llegada'];
$dias_semana = $ride['dias_semana'];
$tarifa_espacio = $ride['tarifa_espacio'];
$espacios_disponibles = $ride['espacios_disponibles']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_vehiculo = trim($_POST['vehiculo_id'] ?? '');
    $nombre_viaje = trim($_POST['nombre_viaje'] ?? '');
    $lugar_salida = trim($_POST['lugar_salida'] ?? '');
    $hora_salida = trim($_POST['hora_salida'] ?? '');
    $lugar_llegada = trim($_POST['lugar_llegada'] ?? '');
    $hora_llegada = trim($_POST['hora_llegada'] ?? '');
    $dias_semana = trim($_POST['dias_semana'] ?? '');
    $tarifa_espacio = trim($_POST['tarifa_espacio'] ?? '');
    $espacios_disponibles = trim($_POST['espacios_disponibles'] ?? '');

    if (updateRide($id_vehiculo, $nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles, $id_viaje)) {
        $_SESSION['success'] = 'Ride actualizado exitosamente';
        header('Location: my_rides.php');
        exit();
    } else {
        $_SESSION['error'] = 'Error al actualizar el ride';
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ride</title>
    <link rel="stylesheet" href="../styles/styles_vehicles_update.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-pencil-square"></i>
                <h1>Editar Ride</h1>
            </div>
            <a href="my_rides.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver a Lista
            </a>
        </div>

        <form class="edit-form" method="POST" enctype="multipart/form-data">
            <div class="content-grid">
                <div class="vehicle-preview">
                    <div class="image-upload-container">
                        <img src="imagenes/mapa.png" class="vehicle-image" id="mapImage">
                    </div>
                    
                    <h2 class="vehicle-name"><?php echo htmlspecialchars($ride['lugar_salida'])?> <div class="arrow">→</div> <?php echo htmlspecialchars($ride['lugar_llegada'])?></h2>
                    
                    <div class="vehicle-badges">
                        <div class="vehicle-plate"><?php echo htmlspecialchars($ride['dias_semana']); ?></div>
                        <div class="vehicle-year"><?php echo htmlspecialchars($ride['placa']); ?></div>
                    </div>
                </div>

                <div class="vehicle-info">
                    <div class="info-header">
                        <i class="bi bi-info-circle"></i>
                        <h3>Información General</h3>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="propietario">Chofer:</label>
                            <input type="text" id="chofer" name="chofer" value="<?php echo htmlspecialchars($ride['nombre']); ?> <?php echo htmlspecialchars($ride['apellidos']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="id">ID:</label>
                            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($ride['id_viaje']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nombre_viaje">Nombre del viaje:</label>
                            <input type="text" name="nombre_viaje" class="input with-icon" min="1" required
                                value="<?php echo htmlspecialchars($ride['nombre_viaje']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="marca">Vehiculo:</label>
                            <select name="vehiculo_id" required>
                            <?php if (!empty($vehicles)): ?>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?php echo htmlspecialchars($vehicle['id_vehiculo']); ?>"
                                        <?php echo ($vehicle['id_vehiculo'] == $ride['id_vehiculo']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="0" disabled selected>No tienes vehículos registrados</option>
                            <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="lugar_salida">Lugar de salida:</label>
                            <input type="text" name="lugar_salida" class="input with-icon" required
                                value="<?php echo htmlspecialchars($ride['lugar_salida']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="hora_salida">Hora de salida:</label>
                            <input type="time" name="hora_salida" class="input with-icon" required
                                value="<?php echo substr($ride['hora_salida'], 0, 5); ?>">
                        </div>

                        <div class="form-group">
                            <label for="lugar_llegada">Lugar de llegada:</label>
                            <input type="text" name="lugar_llegada" class="input with-icon" required
                                value="<?php echo htmlspecialchars($ride['lugar_llegada']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="hora_llegada">Hora de llegada (aprox):</label>
                            <input type="time" name="hora_llegada" class="input with-icon" required
                                value="<?php echo substr($ride['hora_llegada'], 0, 5); ?>">
                        </div>

                        <div class="form-group">
                            <label for="dias_semana">Días:</label>
                            <input type="text" name="dias_semana" class="input with-icon" required
                                value="<?php echo htmlspecialchars($ride['dias_semana']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="espacios_disponibles">Espacios disponibles:</label>
                            <input type="number" name="espacios_disponibles" class="input with-icon" min="1" required
                                value="<?php echo htmlspecialchars($ride['espacios_disponibles']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="tarifa_espacio">Tarifa:</label>
                            <input type="number" name="tarifa_espacio" class="input with-icon" min="1" required
                                value="<?php echo htmlspecialchars($ride['tarifa_espacio']); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="my_rides.php" class="btn btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <script>

        // Actualizar preview de marca y modelo
        document.getElementById('marca').addEventListener('input', updateVehicleName);
        document.getElementById('modelo').addEventListener('input', updateVehicleName);
        document.getElementById('placa').addEventListener('input', function() {
            document.getElementById('platePreview').textContent = this.value;
        });
        document.getElementById('anio').addEventListener('input', function() {
            document.getElementById('yearPreview').textContent = this.value;
        });

        function updateVehicleName() {
            const marca = document.getElementById('marca').value;
            const modelo = document.getElementById('modelo').value;
            document.getElementById('vehicleNamePreview').textContent = `${marca} ${modelo}`;
        }
    </script>
</body>
</html>
