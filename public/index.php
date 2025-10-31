<?php
require_once '../config/start_app.php';

// Si no hay usuario logueado, lo enviamos al login
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Pide Raid - Inicio</title>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a class="navbar-brand" href="index.php">Hellophp</a>
      <ul class="navbar-nav flex-row">
        <li class="nav-item mx-3">
          <a class="nav-link text-white" href="perfil.php">Perfil</a>
        </li>
        <li class="nav-item mx-3">
          <a class="nav-link text-white" href="configuracion.php">Configuración</a>
        </li>
        <li class="nav-item mx-3">
          <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
    </nav>

    <div class="container mt-5">
        <h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?>!</h1>
    </div>
</body>
</html>
