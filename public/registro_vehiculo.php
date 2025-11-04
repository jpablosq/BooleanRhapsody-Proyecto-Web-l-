<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/vehicles_functions.php';

$errors = [];
$marca = '';
$modelo = '';
$anio = '';
$color = '';
$placa = '';
$foto = '';


// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capturar y limpiar datos del formulario
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $placa = trim($_POST['placa'] ?? '');
    $foto = trim($_POST['foto'] ?? '');

   // Validar datos
    $errors = validateVehiculos($marca, $modelo, $anio, $color, $placa, $foto);

    // config foto 
    // config foto 
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

        // Corregido:
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $foto = $fileName;
        } else {
            $foto = null;
        }
    } else {
        $foto = null;
    }

    // Si no hay errores, crear el vehiculo
    if (empty($errors)) {
        if (createVehiculo($marca, $modelo, $anio, $color, $placa, $foto)) {
            $_SESSION['success'] = 'Vehiculo agregado exitosamente.';
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Ocurrió un error al agregar el vehiculo .';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Vehículo - Aventones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styles_vehiculos.css">
</head>
<body>
<div class="auth-container">
    <!-- Lado izquierdo -->
    <div class="left-section">
        <div class="left-content">
            <div class="logo">Aventones</div>
            <div class="tx">
                <h1>Registra tu vehículo<br>y comienza a compartir viajes</h1>
            </div>
        </div>
    </div>

    <!-- Lado derecho -->
    <div class="right-section">
        <div class="form-container">
            <div class="form-content">
                <h2 class="form-title">Agregar Vehículo</h2>
                <p class="form-subtitle">
                    Completa la información de tu automotor
                </p>

                <form method="POST" id="vehicleForm" enctype="multipart/form-data">
                    <!-- Marca -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="bi bi-car-front-fill"></i> Marca
                        </label>
                        <input type="text" class="input" name="marca" placeholder="Ej: Toyota, Honda, Nissan" maxlength="50">
                        <span class="field-error"></span>
                    </div>

                    <!-- Modelo -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="bi bi-speedometer2"></i> Modelo
                        </label>
                        <input type="text" class="input" name="modelo" placeholder="Ej: Corolla, Civic, Hilux" maxlength="50">
                        <span class="field-error"></span>
                    </div>

                    <!-- Año y Color -->
                    <div class="input-row">
                        <div class="input-wrapper">
                            <label class="input-label">
                                <i class="bi bi-calendar-event"></i> Año
                            </label>
                            <input type="number" class="input" name="anio" placeholder="Ej: 2020" min="1900" max="2025">
                            <span class="field-error"></span>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">
                                <i class="bi bi-palette-fill"></i> Color
                            </label>
                            <input type="text" class="input" name="color" placeholder="Ej: Blanco, Negro, Rojo" maxlength="30">
                            <span class="field-error"></span>
                        </div>
                    </div>

                    <!-- Placa -->
                    <div class="input-group">
                        <label class="input-label">
                            <i class="bi bi-credit-card-2-front"></i> Placa
                        </label>
                        <input type="text" class="input" name="placa" placeholder="Ej: ABC-1234" maxlength="20" style="text-transform: uppercase;">
                        <span class="field-error"></span>
                    </div>

                    <!-- Fotografía -->
                    <div class="input-group">
                        <label for="photo" class="lblPhoto">
                            <i class="bi bi-camera-fill"></i> Fotografía del vehículo (opcional)
                            <input type="file" id="photo" name="photo" class="photo" accept="image/*">
                        </label>
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" alt="Vista previa del vehículo">
                            <button type="button" id="removeImage" class="remove-image">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="button-group">
                        <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="submit-btn" onclick="window.location.href='index.php'">
                            <i class="bi bi-check-lg"></i> Agregar Vehículo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para vista previa y mayúsculas -->
<script>
    const photoInput = document.getElementById('photo');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        photoInput.value = '';
        imagePreview.style.display = 'none';
        previewImg.src = '';
    });

    document.querySelector('input[name="placa"]').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
</script>
</body>
</html>
