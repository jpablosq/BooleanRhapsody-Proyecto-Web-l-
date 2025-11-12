<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/rides_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);

// Obtener el viaje
$ride = getRidesById($id);

if (!$ride) {
    $_SESSION['error'] = 'Ride no encontrado';
    header('Location: search_rides.php');
    exit();
}

// Procesar solicitud de viaje (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_viaje = intval($_POST['id_viaje'] ?? 0);
    $id_pasajero = $_SESSION['usuario']['id_usuario'];
    $cantidad_espacios = intval($_POST['espacios_hidden'] ?? 1);
    $metodo_pago = $_POST['metodo_pago_hidden'] ?? '';
    $estado = 'pendiente';

    $exito = createRideRequest($id_viaje, $id_pasajero, $metodo_pago, $cantidad_espacios, $estado);

    if ($exito) {
        $_SESSION['success'] = "Tu solicitud se envió correctamente. Método de pago: $metodo_pago.";
    } else {
        $_SESSION['error'] = "Error al enviar la solicitud. Inténtalo de nuevo.";
    }

    header('Location: rides_details_view.php?id=' . $id_viaje);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalles del Viaje</title>
  <link rel="stylesheet" href="styles/styles_details.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <!-- Botón de regreso -->
    <a href="search_rides.php" class="btn-back">
      <i class="bi bi-arrow-left"></i> Regresar
    </a>

    <!-- Header con ruta principal -->
    <div class="details-header">
      <h1 class="title">Detalles del Viaje</h1>
      <div class="main-route">
        <div class="location-large">
          <i class="bi bi-geo-alt-fill"></i>
          <span><?php echo htmlspecialchars($ride['lugar_salida']) ?></span>
        </div>
        <div class="route-arrow">
          <i class="bi bi-arrow-right"></i>
        </div>
        <div class="location-large">
          <i class="bi bi-flag-fill"></i>
          <span><?php echo htmlspecialchars($ride['lugar_llegada']) ?></span>
        </div>
      </div>
      <div class="main-price">₡<?php echo htmlspecialchars($ride['tarifa_espacio']) ?></div>
    </div>

    <!-- Grid de información -->
    <div class="details-grid">
      <!-- Información del viaje -->
      <div class="info-card">
        <h2 class="card-title">
          <i class="bi bi-calendar-event"></i>
          Información del Viaje
        </h2>
        <div class="info-list">
          <div class="info-item">
            <span class="info-label">Días</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['dias_semana']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Hora de salida</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['hora_salida']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Lugar de salida</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['lugar_salida']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Lugar de llegada</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['lugar_llegada']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Espacios disponibles</span>
            <span class="info-value highlight"><?php echo htmlspecialchars($ride['espacios_disponibles']) ?> asientos</span>
          </div>
        </div>
      </div>

      <!-- Información del vehículo -->
      <div class="info-card">
        <h2 class="card-title">
          <i class="bi bi-car-front-fill"></i>
          Información del Vehículo
        </h2>
        <div class="vehicle-photo">
          <img src="uploads/<?php echo htmlspecialchars($ride['fotografia']) ?>" 
               alt="<?php echo htmlspecialchars($ride['marca'] . ' ' . $ride['modelo']) ?>">
        </div>
        <div class="info-list">
          <div class="info-item">
            <span class="info-label">Marca</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['marca']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Modelo</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['modelo']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Placa</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['placa']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Color</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['color']) ?></span>
          </div>
        </div>
      </div>

      <!-- Información del conductor -->
      <div class="info-card">
        <h2 class="card-title">
          <i class="bi bi-person-circle"></i>
          Información del Conductor
        </h2>
        <div class="driver-profile">
          <div class="driver-avatar-large">
            <img src="<?php echo htmlspecialchars($ride['fotoUsuario']) ?>" 
                 alt="<?php echo htmlspecialchars($ride['nombre'] . ' ' . $ride['apellidos']) ?>">
          </div>
          <div class="driver-details">
            <h3 class="driver-name-large"><?php echo htmlspecialchars($ride['nombre'] . ' ' . $ride['apellidos']) ?></h3>
          </div>
        </div>
        <div class="info-list">
          <div class="info-item">
            <span class="info-label">Teléfono</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['telefono']) ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Cédula</span>
            <span class="info-value"><?php echo htmlspecialchars($ride['cedula']) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección de acciones -->
    <div class="actions-section">
        <button class="btn-request" id="btnSolicitar">
          <i class="bi bi-send-fill"></i>
          Solicitar Viaje
        </button>
    </div>
  </div>

  <!-- Modal de solicitud -->
  <div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="requestForm" method="POST" action="">
          <div class="modal-header">
            <h5 class="modal-title">Solicitar Viaje</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Completa los datos para enviar tu solicitud:</p>

            <div class="mb-3">
              <label for="espacios" class="form-label">Cantidad de espacios</label>
              <input type="number" class="form-control" id="espacios" name="espacios" min="1" required placeholder="Ej. 1">
            </div>

            <div class="mb-3">
              <label class="form-label">Método de pago</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="SINPE" id="pagoSinpe">
                <label class="form-check-label" for="pagoSinpe">SINPE</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Efectivo" id="pagoEfectivo">
                <label class="form-check-label" for="pagoEfectivo">Efectivo</label>
              </div>
            </div>

            <input type="hidden" name="id_viaje" value="<?php echo $ride['id_viaje']; ?>">
            <input type="hidden" name="espacios_hidden" id="espacios_hidden">
            <input type="hidden" name="metodo_pago_hidden" id="metodo_pago_hidden">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle"></i> Enviar Solicitud
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mostrar modal
    document.getElementById("btnSolicitar").addEventListener("click", () => {
      new bootstrap.Modal(document.getElementById("requestModal")).show();
    });

    // Preparar datos antes de enviar
    document.getElementById("requestForm").addEventListener("submit", (e) => {
      const espacios = document.getElementById("espacios").value.trim();
      const metodos = [];
      if (document.getElementById("pagoSinpe").checked) metodos.push("SINPE");
      if (document.getElementById("pagoEfectivo").checked) metodos.push("Efectivo");

      if (!espacios || espacios <= 0) {
        e.preventDefault();
        alert("Ingrese una cantidad válida de espacios.");
        return;
      }

      if(espacios > <?php echo $ride['espacios_disponibles']; ?>) {
        e.preventDefault();
        alert("La cantidad de espacios solicitados excede los disponibles.");
        return;
      }

      if (metodos.length === 0) {
        e.preventDefault();
        alert("Debe seleccionar al menos un método de pago.");
        return;
      }

      document.getElementById("espacios_hidden").value = espacios;
      document.getElementById("metodo_pago_hidden").value = metodos.join(", ");
    });
  </script>
</body>
</html>
