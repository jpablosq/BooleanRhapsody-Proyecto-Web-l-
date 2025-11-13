<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/rides_functions.php';

checkAuth();

// Obtener todos los viajes disponibles
$viajes = getAllAvailableRides();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Viajes</title>
  <link rel="stylesheet" href="../assets/styles/styles_search_rides.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  <!-- Nueva barra de navegación superior con Aventones, usuario y botón de cerrar sesión -->
  <nav class="navbar">
    <div class="navbar-left">
      <a href="../index.php">
        <button class="btn-back">
          <i class="bi bi-arrow-left"></i>
        </button>
      </a>
      <h1 class="navbar-brand">Aventones</h1>
    </div>
  </nav>

  <!-- Contenedor principal -->
  <div class="container">
    <!-- Tarjetas de viajes -->
    <div class="trips-grid" id="viajesContainer">
      <?php if (empty($viajes)): ?>
        <p class="no-trips">No hay viajes registrados actualmente.</p>
      <?php else: ?>
        <?php foreach ($viajes as $viaje): ?>
          <div class="trip-card">
            <div class="trip-header">
              <div class="route">
                <div class="location"><i class="bi bi-geo-alt icon"></i><?= htmlspecialchars($viaje['lugar_salida']) ?></div>
                <div class="arrow">→</div>
                <div class="location"><i class="bi bi-flag icon"></i><?= htmlspecialchars($viaje['lugar_llegada']) ?></div>
              </div>
              <div class="price">₡<?= htmlspecialchars($viaje['tarifa_espacio']) ?></div>
            </div>

            <div class="trip-details">
              <div class="detail-item"><i class="bi bi-clock detail-icon"></i> <?= htmlspecialchars($viaje['hora_salida']) ?></div>
              <div class="detail-item"><i class="bi bi-car-front detail-icon"></i> <?= htmlspecialchars($viaje['marca'] ?? 'Vehículo no asignado') ?> <?= htmlspecialchars($viaje['modelo'] ?? '') ?></div>
            </div>

            <div class="trip-footer">
              <div class="driver-info">
                <div class="avatar">
                  <?= strtoupper(substr($viaje['nombre_chofer'], 0, 1) . substr($viaje['apellidos_chofer'], 0, 1)) ?>
                </div>
                <div class="driver-name"><?= htmlspecialchars($viaje['nombre_chofer'] . ' ' . $viaje['apellidos_chofer']) ?></div>
              </div>
              <a href="rides_details_view.php?id=<?php echo $viaje['id_viaje']; ?>"><button class="btn-reserve">Ver detalles</button></a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  
</body>
</html>
