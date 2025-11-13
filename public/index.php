<?php
require_once '../config/start_app.php';

// Si el usuario no ha iniciado sesión, redirigir al login
if (!isset($_SESSION["usuario"])) {
    header("Location: /users/login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$rol = $usuario['rol'] ?? 'pasajero'; // Por si acaso no está definido
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aventones - Inicio</title>
    <link rel="stylesheet" href="/assets/styles/styles_index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h1>Aventones</h1>
            </div>
            
            <div class="navbar-user">
                <div class="user-info">
                    <span class="username"><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                    <div class="user-avatar">
                        <?php if (!empty($usuario['fotografia'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($usuario['fotografia']); ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <i class="bi bi-person-circle"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="/users/logout.php" class="logout-btn" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="welcome-section">
            <h2>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?>!</h2>
            <p>Encuentra tu próximo aventón o comparte tu viaje con otros.</p>
        </div>

        <div class="content-grid">
            <?php if ($rol === 'Administrador'): ?>
                <!-- ADMIN puede ver todo -->
                <div class="card">
                    <a href="/rides/rides_create.php" class="card-link">
                        <div class="card-icon"><i class="bi bi-plus-circle"></i></div>
                        <h3>Publicar Viaje</h3>
                        <p>Comparte tu viaje </p>
                    </a>
                </div>

                <div class="card">
                    <a href="/rides/search_rides.php" class="card-link">
                        <div class="card-icon"><i class="bi bi-search"></i></div>
                        <h3>Viajes en linea</h3>
                        <p>Revisa los viajes disponibles </p>
                    </a>
                </div>

                <a href="/vehicles/my_vehicles.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Mis vehiculos</h3>
                        <p>¿Quires ver tus vehiculos registrados?</p>
                    </div>
                </a>

                <a href="/vehicles/vehicles_create.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Registra otro vehiculo</h3>
                        <p>¿Quieres registrar otro vehiculo en nuestra app?</p>
                    </div>
                </a>

                <a href="/rides/rides_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Aceptar pasajeros</h3>
                        <p>¿Decidi que pasajeros van o no en tu ride?</p>
                    </div>
                </a>

                <a href="/rides/my_rides_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Ver mis rides</h3>
                        <p>Puedes ver tus rides antiguos o futuros y darte baja</p>
                    </div>
                </a>

                <a href="/vehicles/vehicles_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Aprobar solicitudes</h3>
                        <p>Aprueba o rechaza solicitudes de conductores</p>
                    </div>
                </a>

                <a href="/users/users.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-person"></i></div>
                        <h3>Administrador de Usuarios</h3>
                        <p>Administra y ve todos los usuarios de la app</p>
                    </div>
                </a>

            <?php elseif ($rol === 'Chofer'): ?>
                <!-- CHOFER puede ver todo excepto "Aprobar solicitudes" -->

                <div class="card">
                    <a href="/rides/rides_create.php" class="card-link">
                        <div class="card-icon"><i class="bi bi-plus-circle"></i></div>
                        <h3>Publicar Viaje</h3>
                        <p>Comparte tu viaje </p>
                    </a>
                </div>


                <div class="card">
                    <a href="/rides/search_rides.php" class="card-link">
                        <div class="card-icon"><i class="bi bi-search"></i></div>
                        <h3>Viajes en linea</h3>
                        <p>Revisa los viajes disponibles </p>
                    </a>
                </div>

                <a href="/vehicles/my_vehicles.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Mis vehiculos</h3>
                        <p>¿Quires ver tus vehiculos registrados?</p>
                    </div>
                </a>

                <a href="/vehicles/vehicles_create.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Registra otro vehiculo</h3>
                        <p>¿Quieres registrar otro vehiculo en nuestra app?</p>
                    </div>
                </a>

                <a href="/rides/rides_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Aceptar pasajeros</h3>
                        <p>¿Decidi que pasajeros van o no en tu ride?</p>
                    </div>
                </a>

                <a href="/rides/my_rides_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Ver mis rides</h3>
                        <p>Puedes ver tus rides antiguos o futuros y darte baja</p>
                    </div>
                </a>


            <?php else: ?>
                <!-- PASAJERO solo puede ver Buscar, Mis viajes y Mi perfil -->
                 
                <div class="card">
                    <a href="/rides/search_rides.php" class="card-link">
                        <div class="card-icon"><i class="bi bi-search"></i></div>
                        <h3>Viajes en linea</h3>
                        <p>Revisa los viajes disponibles </p>
                    </a>
                </div>

                <a href="/vehicles/vehicles_create.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Aplica para chofer</h3>
                        <p>¿Quieres convertirte en Conductor en nuestra app?</p>
                    </div>
                </a>

                <a href="/rides/my_rides_request.php" class="card-link">
                    <div class="card">
                        <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        <h3>Ver mis rides</h3>
                        <p>Puedes ver tus rides antiguos o futuros y darte baja</p>
                    </div>
                </a>
                
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
