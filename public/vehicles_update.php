<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/vehicles_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Obtener vehiculo existente
$vehicle = getVehiclesById($id);

if (!$vehicle) {
    $_SESSION['error'] = 'Vehiculo no encontrado';
    header('Location: vehicles.php');
    exit();
}

// Capturar datos
$idVehicle = $vehicle['id_vehiculo'];
$idUsuario = $vehicle['id_usuario'];
$marca = $vehicle['marca'];
$modelo = $vehicle['modelo'];
$anio = $vehicle['anio_fabricacion'];
$color = $vehicle['color'];
$placa = $vehicle['placa'];
$foto = $vehicle['fotografia'];
$fechaRegistro = $vehicle['fecha_registro']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $placa = trim($_POST['placa'] ?? '');
    
    // Si el usuario sube una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $foto = $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], 'uploads/' . $foto);
    } else {
        // Si no sube nada, mantenemos la imagen anterior
        $foto = $vehicle['fotografia'];
    }

    $fechaRegistro = trim($_POST['fecha_registro'] ?? '');

    if (updateVehicle($idVehicle, $marca, $modelo, $anio, $color, $placa, $foto)) {
        $_SESSION['success'] = 'Vehiculo actualizado exitosamente';
        header('Location: vehicles.php');
        exit();
    } else {
        $_SESSION['error'] = 'Error al actualizar el vehiculo';
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vehículo</title>
    <link rel="stylesheet" href="../styles/styles_vehicles_update.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-pencil-square"></i>
                <h1>Editar Vehículo</h1>
            </div>
            <a href="vehicles.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver a Lista
            </a>
        </div>

        <form class="edit-form" method="POST" enctype="multipart/form-data">
            <div class="content-grid">
                <div class="vehicle-preview">
                    <div class="image-upload-container">
                        <img src="uploads/<?php echo htmlspecialchars($vehicle['fotografia']); ?>" alt="Vehículo" class="vehicle-image" id="vehicleImage">
                        <div class="image-overlay">
                            <label for="imageInput" class="upload-label">
                                <i class="bi bi-camera-fill"></i>
                                <span>Cambiar Imagen</span>
                            </label>
                            <input type="file" id="imageInput" name="imagen" accept="image/*" style="display: none;">
                        </div>
                    </div>
                    
                    <h2 class="vehicle-name" id="vehicleNamePreview"><?php echo htmlspecialchars($vehicle['marca']); ?> <?php echo htmlspecialchars($vehicle['modelo']); ?></h2>
                    
                    <div class="vehicle-badges">
                        <span class="badge badge-plate" id="platePreview"><?php echo htmlspecialchars($vehicle['placa']); ?></span>
                        <span class="badge badge-year" id="yearPreview"><?php echo htmlspecialchars($vehicle['anio_fabricacion']); ?></span>
                    </div>
                </div>

                <div class="vehicle-info">
                    <div class="info-header">
                        <i class="bi bi-info-circle"></i>
                        <h3>Información General</h3>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="propietario">PROPIETARIO:</label>
                            <input type="text" id="propietario" name="propietario" value="<?php echo htmlspecialchars($vehicle['nombre']) . ' ' . htmlspecialchars($vehicle['apellidos']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="id">ID:</label>
                            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($vehicle['id_vehiculo']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="marca">MARCA:</label>
                            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($vehicle['marca']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="modelo">MODELO:</label>
                            <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($vehicle['modelo']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="anio">AÑO:</label>
                            <input type="number" id="anio" name="anio" value="<?php echo htmlspecialchars($vehicle['anio_fabricacion']); ?>" min="2004" max="2099" required>
                        </div>

                        <div class="form-group">
                            <label for="placa">PLACA:</label>
                            <input type="text" id="placa" name="placa" value="<?php echo htmlspecialchars($vehicle['placa']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="color">COLOR:</label>
                            <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($vehicle['color']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_registro">FECHA DE REGISTRO:</label>
                            <input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo htmlspecialchars($vehicle['fecha_registro']); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="vehicles.php" class="btn btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <script>
        // Preview de imagen cuando se selecciona un archivo
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('vehicleImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

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
