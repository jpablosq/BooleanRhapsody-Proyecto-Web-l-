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
$contrasena = 'changeme';
$rol = '';

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
    $contrasena = 'changeme';
    $confirmar_contrasena = 'changeme';
    $rol = trim($_POST['rol'] ?? '');

   // Validar datos
    $errors = validateUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $telefono, $nombre_usuario, $contrasena, $confirmar_contrasena);

    // Si no hay errores, crear el usuario
    if (empty($errors)) {
        if (createUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $fotografia, $telefono, $nombre_usuario, $contrasena, $rol)) {
            $_SESSION['success'] = 'Usuario creado exitosamente.';
            header('Location: users.php');
            exit();
        } else {
            $_SESSION['success'] = 'Ocurrió un error al crear el usuario.';
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
    <link rel="stylesheet" href="../assets/styles/styles_create.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="auth-container">
    <!-- Lado izquierdo -->
    <div class="left-section">
        <div class="left-content">
            <div class="logo">Aventones</div>
            <div class="tx">
                <h1>Crea un usuario</h1>
            </div>
        </div>
    </div>

    <!-- Lado derecho -->
    <div class="right-section">
        <div class="form-container">
            <div class="form-content">
                <h2 class="form-title">Crear cuenta</h2>
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

                    <div class="form-group">
                            <label for="id">Rol:</label>
                                <div class="role-options">
                                <label>
                                    <input type="radio" name="rol" value="Administrador">Administrador
                                </label>
                                <br>
                                <label>
                                    <input type="radio" name="rol" value="Pasajero">Pasajero
                                </label>
                                <br>
                                <label>
                                    <input type="radio" name="rol" value="Chofer">Chofer
                                </label>
                            </div>
                        </div>

                    <button type="submit" class="submit-btn">Crear cuenta</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
