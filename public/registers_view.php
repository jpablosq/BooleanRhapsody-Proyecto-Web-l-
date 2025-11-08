<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/my_registers_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener solicitud
$register = getRegistersByid($id);

if (!$register) {
    $_SESSION['error'] = 'Solicitud no encontrada';
    header('Location: registers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Vehículo</title>
    <link rel="stylesheet" href="../styles/styles_vehiclesAndRegisters_view.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-eye"></i>
                <h1>Detalles de la solicitud</h1>
            </div>
            <div class="header-actions">
                <a href="registers.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </div>

        <div class="content">
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="uploads/<?php echo htmlspecialchars($register['fotografia']); ?>" alt="Imagen del vehículo" id="vehicleImage">
                </div>
                <h2 class="vehicle-name"><?php echo htmlspecialchars($register['marca'])?> <?php echo htmlspecialchars($register['modelo'])?></h2>
                <div class="vehicle-plate"><?php echo htmlspecialchars($register['placa']); ?></div>
                <div class="vehicle-year"><?php echo htmlspecialchars($register['anio_fabricacion']); ?></div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h3>Información General</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Propietario:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['nombre']); ?> <?php echo htmlspecialchars($register['apellidos']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['id_registro']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marca:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['marca'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['modelo'])?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Año:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['anio_fabricacion']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Placa:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['placa']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Color:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['color']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Registro:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['fecha_registro']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['estado']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Motivo:</span>
                        <span class="info-value"><?php echo htmlspecialchars($register['descripcion']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="action-buttons">
           <form action="register_accept.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $register['id_registro']; ?>">
                <button type="submit" class="btn btn-accept" title="Aceptar">
                    <i class="bi bi-check-lg">Aceptar solicitud</i>
                </button>
            </form>

            <form action="registers_decline.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $register['id_registro']; ?>">
                <button type="submit" class="btn btn-delete" title="Aceptar">
                    <i class="bi bi-x-lg">Rechazar solicitud</i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
