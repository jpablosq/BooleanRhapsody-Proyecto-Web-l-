<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todos los vehiculos del usuario registrado
$vehicles = getAllUserVehicles($usuario['id_usuario']);

// Variables iniciales
$errors = [];
$nombre_viaje = '';
$lugar_salida = '';
$hora_salida = '';
$lugar_llegada = '';
$hora_llegada = '';
$dias_semana = '';
$tarifa_espacio = '';
$espacios_disponibles = '';

$id_vehiculo = trim($_POST['vehiculo_id'] ?? '');

// Validación crítica
if (empty($id_vehiculo) || $id_vehiculo == '0') {
    $errors[] = "Debe seleccionar un vehículo válido.";
}
$id_chofer = $usuario['id_usuario'];
$nombre_viaje = trim($_POST['nombre_viaje'] ?? '');
$lugar_salida = trim($_POST['lugar_salida'] ?? '');
$hora_salida = trim($_POST['hora_salida'] ?? '');
$lugar_llegada = trim($_POST['lugar_llegada'] ?? '');
$hora_llegada = trim($_POST['hora_llegada'] ?? '');
$tarifa_espacio = trim($_POST['tarifa'] ?? '');
$espacios_disponibles = trim($_POST['espacios'] ?? '');
$dias_semana = isset($_POST['dias']) ? implode(', ', $_POST['dias']) : '';

// Validar datos
$errors = validateRides($nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles);

// Crear viaje si no hay errores
if (empty($errors)) {
    if (createRide($id_chofer, $id_vehiculo, $nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles)) {
        $_SESSION['success'] = 'Viaje creado exitosamente.';
        header('Location: rides_create.php');
        exit();
    } else {
        $errors[] = 'Ocurrió un error al crear el viaje.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar viaje - Aventones</title>
    <link rel="stylesheet" href="styles/styles_raids.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="auth-container">
    <div class="left-section">
        <div class="left-content">
            <div class="logo">Aventones</div>
            <div class="tx">
                <h1>Comparte tu viaje<br>y conecta con pasajeros</h1>
            </div>
        </div>
    </div>

    <div class="right-section">
        <div class="form-container">
            <div class="form-content">
                <h2 class="form-title">Publicar un viaje</h2>
                <p class="form-subtitle">Completa los detalles de tu viaje</p>

                <form method="POST">
                    <!-- Chofer -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <input 
                            type="text" 
                            class="input with-icon"
                            value="<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?>" 
                            readonly
                        >
                    </div>

                    <!-- Vehículo -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <select class="input with-icon" name="vehiculo_id" required>
                            <?php if (!empty($vehicles)): ?>
                                <?php foreach ($vehicles as $index => $v): ?>
                                    <!-- Se selecciona automáticamente el primer vehículo -->
                                    <option value="<?php echo htmlspecialchars($v['id_vehiculo']); ?>" <?php echo $index === 0 ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' - ' . $v['placa']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="0" disabled selected>No tienes vehículos registrados</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Nombre del viaje -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-signpost-2"></i>
                        </div>
                        <input type="text" class="input with-icon" name="nombre_viaje" placeholder="Nombre del viaje" maxlength="100">
                    </div>

                    <!-- Lugar de salida -->
                    <div class="input-row">
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-geo-alt"></i></div>
                                <input type="text" class="input with-icon" name="lugar_salida" placeholder="Lugar de salida" maxlength="200">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-clock"></i></div>
                                <input type="time" class="input with-icon" name="hora_salida">
                            </div>
                        </div>
                    </div>

                    <!-- Lugar de llegada -->
                    <div class="input-row">
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-geo-fill"></i></div>
                                <input type="text" class="input with-icon" name="lugar_llegada" placeholder="Lugar de llegada" maxlength="200">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-clock-fill"></i></div>
                                <input type="time" class="input with-icon" name="hora_llegada">
                            </div>
                        </div>
                    </div>

                    <!-- Días -->
                    <div class="input-group">
                        <label class="label-group">Días de la semana</label>
                        <div class="days-grid">
                            <?php
                            $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
                            foreach ($dias as $d): ?>
                                <label class="day-checkbox">
                                    <input type="checkbox" name="dias[]" value="<?php echo $d; ?>">
                                    <span class="day-label"><?php echo strtoupper(substr($d,0,1)); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tarifa y espacios -->
                    <div class="input-row">
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-people"></i></div>
                                <input type="number" class="input with-icon" name="espacios" placeholder="Espacios disponibles" min="1" max="10">
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon"><i class="bi bi-currency-dollar"></i></div>
                                <input type="number" class="input with-icon" name="tarifa" placeholder="Tarifa por espacio" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        
                        <button type="submit" class="submit-btn">Publicar viaje</button>
                    </div>

                    <div class="view-rides-section">
                        <button type="button" class="view-rides-btn" onclick="window.location.href='my_rides.php'">
                            <i class="bi bi-car-front"></i> Ver mis Rides 
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
</body>
</html>
