<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/users_functions.php';

$errors = [];
$nombre = '';
$apellidos = '';
$cedula = '';
$fecha_nacimiento = '';
$correo = '';
$fotografia = '';
$telefono = '';
$nombre_usuario = '';
$contrasena = '';
$rol = 'Pasajero';

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capturar y limpiar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha'] ?? '');
    $correo = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $nombre_usuario = trim($_POST['nombreUsuario'] ?? '');
    $contrasena = trim($_POST['password'] ?? '');
    $confirmar = trim($_POST['confirmPassword'] ?? '');
    $rol = 'Pasajero';

   // Validar datos
    $errors = validateUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $telefono, $nombre_usuario, $contrasena, $confirmar);

    // Config foto 
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            // Guarda solo el nombre del archivo en la BD
            $fotografia = $fileName;
        } else {
            $fotografia = null;
        }
    } else {
        $fotografia = null;
    }


    // Si no hay errores, crear el usuario
    if (empty($errors)) {
        if (createUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $fotografia, $telefono, $nombre_usuario, $contrasena, $rol)) {
            $_SESSION['success'] = 'Usuario creado exitosamente.';
            header('Location: login.php');
            exit();
        } else {
            $errors[] = 'Ocurrió un error al crear el usuario.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Aventones</title>
    <link rel="stylesheet" href="../assets/styles/styles_login_register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="auth-container">
    <!-- Lado izquierdo -->
    <div class="left-section">
        <div class="left-content">
            <div class="logo">Aventones</div>
            <div class="tx">
                <h1>Conecta con personas<br>que van a tu mismo destino</h1>
            </div>
        </div>
    </div>

    <!-- Lado derecho -->
    <div class="right-section">
        <div class="form-container">
            <div class="form-content">
                <h2 class="form-title">Crear una cuenta</h2>
                <p class="form-subtitle">
                    ¿Ya tienes una cuenta? <a href="login.php" class="link">Iniciar sesión</a>
                </p>
                 <!-- enctype para conectar el archivo de la foto con el servidor -->
                <form method="POST" id="registerForm" enctype="multipart/form-data">
                    <div class="input-row">
                        <div class="input-wrapper">
                            <input type="text" class="input" name="nombre" placeholder="Nombre" maxlength="100" value="<?= htmlspecialchars($nombre) ?>">
                            <!-- span para darle estilo al error -->
                            <span class="field-error">
                            <?php 
                            foreach ($errors as $error) {
                                // Busca donde haya un error que diga o contenga "nombre" y que no contegna usuario para no confundir con el error de usuario y lo imprime
                                if (stripos($error, 'nombre') !== false && stripos($error, 'usuario') === false) {
                                    echo htmlspecialchars($error);
                                    break;
                                }
                            }
                            ?>
                            </span>
                        </div>
                        <div class="input-wrapper">
                            <input type="text" class="input" name="apellidos" placeholder="Apellidos" maxlength="100" value="<?= htmlspecialchars($apellidos) ?>">
                            <span class="field-error">
                            <?php 
                            foreach ($errors as $error) {
                                // Busca donde haya un error que diga o contenga "apellidos" y lo imprime
                                if (stripos($error, 'apellidos') !== false) {
                                    echo htmlspecialchars($error);
                                    break;
                                }
                            }
                            ?>
                            </span>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" class="input" name="cedula" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Cédula" value="<?= htmlspecialchars($cedula) ?>">
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "cédula" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'cédula') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <div class="input-group">
                        <input type="date" class="input" name="fecha" placeholder="Fecha de nacimiento" value="<?= htmlspecialchars($fecha_nacimiento) ?>">
                        <span class="field-error">
                        <?php 
                        foreach ($errors as $error) {
                            // Busca donde haya un error que diga o contenga "fecha" y lo imprime
                            if (stripos($error, 'fecha') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <div class="input-group">
                        <input type="email" class="input" name="email" placeholder="Correo electrónico" value="<?= htmlspecialchars($correo) ?>">
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "correo" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'correo') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <div class="input-group">
                        <input type="text" class="input" name="telefono" placeholder="Número de teléfono" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= htmlspecialchars($telefono) ?>">
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "teléfono" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'teléfono') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <div class="input-group">
                        <input type="text" class="input" name="nombreUsuario" placeholder="Nombre de usuario" maxlength="50" value="<?= htmlspecialchars($nombre_usuario) ?>">
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "nombre de usuario" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'usuario') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <!-- Foto -->
                    <div class="input-group">
                        <label for="photo" class="lblPhoto">
                            <i class="bi bi-camera-fill"></i> Fotografía (opcional)
                            <input type="file" id="photo" name="photo" class="photo" accept="image/*">
                        </label>
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" alt="Vista previa">
                            <button type="button" id="removeImage" class="remove-image">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="input-group password-group">
                        <input type="password" class="input" name="password" placeholder="Contraseña">
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "contraseña" o "contraseñas" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'contraseña') !== false || stripos($error, 'contraseñas') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <div class="input-group password-group">
                        <input type="password" class="input" name="confirmPassword" placeholder="Confirmar contraseña">
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                        <span class="field-error">
                        <?php 
                        // Busca donde haya un error que diga o contenga "contraseña" o "contraseñas" y lo imprime
                        foreach ($errors as $error) {
                            if (stripos($error, 'contraseña') !== false || stripos($error, 'contraseñas') !== false) {
                                echo htmlspecialchars($error);
                                break;
                            }
                        }
                        ?>
                        </span>
                    </div>

                    <button type="submit" class="submit-btn">Crear cuenta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Funcionalidad para ver la  password
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });

    // Funcionalidad para ver el preview de la imagen
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
</script>
</body>
</html>
