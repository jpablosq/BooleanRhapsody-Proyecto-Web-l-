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
$selectUser = getUserById($id);

if (!$user) {
    $_SESSION['error'] = 'Usuario no encontrado';
    header('Location: users.php');
    exit();
}

// Variables iniciales
$id_usuario = $selectUser['id_usuario'];
$nombre = $selectUser['nombre'];
$apellidos = $selectUser['apellidos'];    
$cedula = $selectUser['cedula'];
$fecha_nacimiento = $selectUser['fecha_nacimiento'];
$correo = $selectUser['correo'];
$fotografia = $selectUser['fotografia'];
$telefono = $selectUser['telefono'];
$nombre_usuario = $selectUser['nombre_usuario'];
$contrasena = $selectUser['contrasena']; 
$rol = $selectUser['rol']; 

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    // Actualizar usuario
    if (updateUser($id_usuario, $nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $telefono, $nombre_usuario, $rol)) {
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
    <title>Editar Ride</title>
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
                <div class="vehicle-preview">
                    <div class="image-upload-container">
                        <img src="../uploads/<?php echo htmlspecialchars($selectUser['fotografia'])?>" class="vehicle-image" id="imageUser">
                    </div>
                    
                    <h2 class="vehicle-name"><?php echo htmlspecialchars($selectUser['nombre']); ?> <?php echo htmlspecialchars($selectUser['apellidos']); ?></h2>
                    
                    <div class="vehicle-badges">
                        <span class="badge badge-year"><?php echo htmlspecialchars($selectUser['cedula']); ?></span>
                    </div>
                </div>

                <div class="vehicle-info">
                    <div class="info-header">
                        <i class="bi bi-info-circle"></i>
                        <h3>Información General</h3>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="propietario">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($selectUser['nombre']); ?>" require>
                        </div>

                        <div class="form-group">
                            <label for="id">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($selectUser['apellidos']); ?>" require>
                        </div>

                        <div class="form-group">
                            <label for="id">Cedula:</label>
                            <input type="text" id="cedula" name="cedula" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo htmlspecialchars($selectUser['cedula']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="id">Fecha nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($selectUser['fecha_nacimiento']); ?>" require>
                        </div>

                        <div class="form-group">
                            <label for="id">Correo:</label>
                            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($selectUser['correo']); ?>" require>
                        </div>

                        <div class="form-group">
                            <label for="id">Telefono:</label>
                            <input type="text" id="telefono" name="telefono" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo htmlspecialchars($selectUser['telefono']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="id">Usuario:</label>
                            <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($selectUser['nombre_usuario']); ?>" require>
                        </div>
                        
                        <div class="form-group">
                            <label for="id">Rol:</label>
                                <div class="role-options">
                                <label>
                                    <input type="radio" name="rol" value="Administrador"
                                        <?php echo ($selectUser['rol'] === 'Administrador') ? 'checked' : ''; ?>>
                                    Administrador
                                </label>
                                <br>
                                <label>
                                    <input type="radio" name="rol" value="Pasajero"
                                        <?php echo ($selectUser['rol'] === 'Pasajero') ? 'checked' : ''; ?>>
                                    Pasajero
                                </label>
                                <br>
                                <label>
                                    <input type="radio" name="rol" value="Chofer"
                                        <?php echo ($selectUser['rol'] === 'Chofer') ? 'checked' : ''; ?>>
                                    Chofer
                                </label>
                            </div>
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
</body>
</html>