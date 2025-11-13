<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener solicitud
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Solicitud de vehiculo no encontrada';
    header('Location: my_vehicles_request.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vehículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/styles/styles_view.css">
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles de la solicitud</h1>
            </div>
            <div class="header-actions">
                <a href="my_vehicles_request.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="../uploads/<?php echo htmlspecialchars($vehicle['fotografia']); ?>" alt="Imagen del vehículo" id="vehicleImage">
                </div>
                <h2 class="vehicle-name"><?php echo htmlspecialchars($vehicle['marca'])?> <?php echo htmlspecialchars($vehicle['modelo'])?></h2>
                <div class="vehicle-plate"><?php echo htmlspecialchars($vehicle['placa']); ?></div>
                <div class="vehicle-year"><?php echo htmlspecialchars($vehicle['anio_fabricacion']); ?></div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h3>Información General</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Propietario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['nombre']); ?> <?php echo htmlspecialchars($vehicle['apellidos']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['id_vehiculo']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marca:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['marca'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['modelo'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Año:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['anio_fabricacion']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Placa:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['placa']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Color:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['color']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Registro:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['fecha_registro']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['estado']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Motivo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($vehicle['descripcion']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
