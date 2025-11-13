<?php
require_once '../../config/start_app.php';
require_once '../../config/functions.php';
require_once '../../config/users_functions.php';

// Verificar autenticación
checkAuth();

$id = intval($_GET['id'] ?? 0);
$errors = [];

// Usuario logueado
$usuario = $_SESSION["usuario"];

// Obtener usuario existente
$users = getUserById($id);

if (!$users) {
    $_SESSION['error'] = 'Usuario no encontrado';
    header('Location: users.php');
    exit();
}

// Variables iniciales
$id_usuario = $users['id_usuario'];
$nombre = $users['nombre'];
$apellidos = $users['apellidos'];    
$cedula = $users['cedula'];
$fecha_nacimiento = $users['fecha_nacimiento'];
$correo = $users['correo'];
$fotografia = $users['fotografia'];
$telefono = $users['telefono'];
$nombre_usuario = $users['nombre_usuario'];
$contrasena = $users['contrasena']; 
$rol = $users['rol']; 

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    // Manejo de fotografía
    if (!empty($_FILES['fotografia']['name'])) {
        $directorio = '../assets/imagenes/';
        $nombreArchivo = basename($_FILES['fotografia']['name']);
        $rutaDestino = $directorio . $nombreArchivo;

        if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $rutaDestino)) {
            $fotografia = $rutaDestino;
        }
    }

    // Actualizar usuario
    if (updateUser($id_usuario, $nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $fotografia, $telefono, $nombre_usuario, $contrasena, $rol)) {
        $_SESSION['success'] = 'Usuario actualizado exitosamente';
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = 'Error al actualizar el usuario';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../assets/styles/styles_update.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="bi bi-pencil-square"></i>
                <h1>Editar Usuario</h1>
            </div>
            <a href="users.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver a Lista
            </a>
        </div>

        <form class="edit-form" method="POST" enctype="multipart/form-data">
            <div class="content-grid">
                <!-- Vista previa del usuario -->
                <div class="user-preview" style="background:#1e1b2e; color:white; padding:20px; border-radius:10px;">
                    <img id="photoPreview" src="<?php echo htmlspecialchars($users['fotografia']); ?>" alt="Foto actual" width="120" style="border-radius:10px; margin-bottom:10px;">
                    <h2 id="namePreview"><?php echo htmlspecialchars($users['nombre'] . ' ' . $users['apellidos']); ?></h2>
                    <p id="emailPreview"><?php echo htmlspecialchars($users['correo']); ?></p>
                    <p id="phonePreview"><?php echo htmlspecialchars($users['telefono']); ?></p>
                    <p id="rolePreview">Rol: <?php echo htmlspecialchars($users['rol']); ?></p>
                </div>

                <div class="vehicle-info">
                    <div class="info-header">
                        <i class="bi bi-info-circle"></i>
                        <h3>Información del Usuario</h3>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_usuario">ID Usuario:</label>
                            <input type="text" id="id_usuario" name="id_usuario" value="<?php echo htmlspecialchars($users['id_usuario']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($users['nombre']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($users['apellidos']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="cedula">Cédula:</label>
                            <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($users['cedula']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha Nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($users['fecha_nacimiento']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($users['correo']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fotografia">Fotografía:</label>
                            <input type="file" id="fotografia" name="fotografia" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($users['telefono']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre_usuario">Nombre de Usuario:</label>
                            <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($users['nombre_usuario']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="contrasena">Contraseña:</label>
                            <input type="text" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($users['contrasena']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="rol">Rol:</label>
                            <input type="text" id="rol" name="rol" value="<?php echo htmlspecialchars($users['rol']); ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="users.php" class="btn btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <script>
        // === SCRIPT DE PREVISUALIZACIÓN ===
        const nombreInput = document.getElementById('nombre');
        const apellidosInput = document.getElementById('apellidos');
        const correoInput = document.getElementById('correo');
        const telefonoInput = document.getElementById('telefono');
        const rolInput = document.getElementById('rol');
        const fotoInput = document.getElementById('fotografia');

        const namePreview = document.getElementById('namePreview');
        const emailPreview = document.getElementById('emailPreview');
        const phonePreview = document.getElementById('phonePreview');
        const rolePreview = document.getElementById('rolePreview');
        const photoPreview = document.getElementById('photoPreview');

        function updateFullName() {
            const nombre = nombreInput.value.trim();
            const apellidos = apellidosInput.value.trim();
            namePreview.textContent = `${nombre} ${apellidos}`.trim() || 'Nombre completo';
        }

        function updateEmail() {
            emailPreview.textContent = correoInput.value || 'correo@ejemplo.com';
        }

        function updatePhone() {
            phonePreview.textContent = telefonoInput.value || '0000-0000';
        }

        function updateRole() {
            rolePreview.textContent = `Rol: ${rolInput.value || 'No definido'}`;
        }

        function updatePhoto() {
            const file = fotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        nombreInput.addEventListener('input', updateFullName);
        apellidosInput.addEventListener('input', updateFullName);
        correoInput.addEventListener('input', updateEmail);
        telefonoInput.addEventListener('input', updatePhone);
        rolInput.addEventListener('input', updateRole);
        fotoInput.addEventListener('change', updatePhoto);
    </script>
</body>
</html>
