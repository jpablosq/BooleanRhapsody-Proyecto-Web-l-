<?php
require_once '../config/start_app.php';
require_once '../config/functions.php';
require_once '../config/my_registers_functions.php';

// Verificar autenticación
checkAuth();
$usuario = $_SESSION["usuario"];

// Obtener todos los registros
$registers = getAllUserRegisters($usuario['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Solicitudes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles_vehiclesAndRegisters.css">
</head>
<body>
    <div class="container">
        <span>
        <?php if(isset($_SESSION["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION["success"]; ?>
            </div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif;?>
        </span>

        <span>
            <?php if(isset($_SESSION["error"])): ?>
                <div class= "alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <?php echo $_SESSION["error"]; ?>
                </div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif;?>
        </span>

        <div class="header">
            <div class="header-title">
                <i class="bi bi-car-front-fill"></i>
                <h1>Gestión de Registros</h1>
            </div>
            <button class="btn-back" onclick="window.location.href='registers_create.php'">
                <i class="bi bi-arrow-left"></i>
                Volver
            </button>
        </div>

        <div class="table-container">
    <table class="vehicles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Placa</th>
                <th>Color</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registers as $register): ?>
            <tr>
                <td><?php echo $register['id_registro']; ?></td>
                <td><?php echo $register['nombre_usuario']; ?></td>
                <td><?php echo $register['marca']; ?></td>
                <td><?php echo $register['modelo']; ?></td>
                <td><?php echo $register['anio_fabricacion']; ?></td>
                <td><span class="badge-plate"><?php echo $register['placa']; ?></span></td>
                <td><?php echo $register['color']; ?></td>
                <td><?php echo $register['estado']; ?></td>
                <td><?php echo $register['fecha_registro']; ?></td>
                <td>
                <div class="action-buttons">
                    <a href="my_registers_view.php?id=<?php echo $register['id_registro']; ?>" 
                        class="btn-action btn-view" title="Ver">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
        <div class="empty-state" style="display: none;">
            <i class="bi bi-car-front"></i>
            <h3>No hay solicitudes registradas</h3>
            <p>Comienza agregando tu primer solicitud</p>
            <button class="btn-add-first" onclick="window.location.href='registers_create.php'">
                <i class="bi bi-plus-circle"></i>
                Agregar solicitud
            </button>
        </div>
    </div>
</body>
</html>
