<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

checkAuth();

// Obtener todos los viajes
$viajes = getAllRides2();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Viajes</title>
  <link rel="stylesheet" href="styles/styles_inbox.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- 🔹 Navbar de Filtros -->
  <nav class="navbar">
    <h2>Mis Viajes</h2>
    <div class="filters">
      <input type="text" id="filtroSalida" placeholder="Lugar de salida">
      <input type="text" id="filtroLlegada" placeholder="Lugar de llegada">
      <input type="date" id="filtroFecha">
      <input type="time" id="filtroHora" placeholder="Hora de salida">
      <button id="btnFiltrar">Filtrar</button>
      <button id="btnLimpiar">Limpiar</button>
    </div>
  </nav>

  <!-- 🔹 Contenedor principal -->
  <div class="container">
    <header class="header">
      <h1 class="title">Listado de Viajes</h1>
      <p class="subtitle">Consulta y filtra tus viajes fácilmente</p>
    </header>

    <!-- 🔹 Tarjetas de viajes -->
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
              <button class="btn-reserve"><a href="rides_details_view.php?id=<?php echo $viaje['id_viaje']; ?>">Ver detalles</a></button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  
</body>
</html>
