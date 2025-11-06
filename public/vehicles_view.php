<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener producto
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Vehiculo no encontrado';
    header('Location: vehicles.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vehículo</title>
    <link rel="stylesheet" href="../styles/styles_vehicles_view.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles del Vehículo</h1>
            </div>
            <div class="header-actions">
                <a href="vehicles.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="uploads/<?php echo htmlspecialchars($vehicle['fotografia']); ?>" alt="Imagen del vehículo" id="vehicleImage">
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
                </div>
            </div>
        </div>

        <!-- Eliminando toda la sección de descripción -->

        <div class="action-buttons">
            <button class="btn btn-delete" onclick="confirmDelete($vehicle['id_vehiculo'], '<?php echo htmlspecialchars($vehicle['marca']); ?>', '<?php echo htmlspecialchars($vehicle['modelo']); ?>')">
                <i class="bi bi-trash"></i> Eliminar Vehículo
            </button>
            <a href="vehicles_update.php?id=<?php echo $vehicle['id_vehiculo']; ?>" class="btn btn-edit-main">
                <i class="bi bi-pencil-square"></i> Editar Vehículo
            </a>
        </div>
    </div>
</body>
</html>
