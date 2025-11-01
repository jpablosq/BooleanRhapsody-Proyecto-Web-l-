<?php
    require_once '../config/start_app.php';

    // Si el usuario no ha iniciado sesión, redirigir al login
    if(!isset($_SESSION["usuario"])){
        header("Location: login.php");
        exit();
    }

    $usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aventones - Inicio</title>
    <link rel="stylesheet" href="../styles/styles_index.css">
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
                        <?php if(!empty($usuario['fotografia'])): ?>
                          <img src="uploads/<?php echo htmlspecialchars($usuario['fotografia']); ?>" alt="Foto de perfil">
                        <?php else: ?>
                          <i class="bi bi-person-circle"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn" title="Cerrar sesión">
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
            <div class="card">
                <div class="card-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Buscar Aventones</h3>
                <p>Encuentra viajes disponibles a tu destino</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <h3>Publicar Viaje</h3>
                <p>Comparte tu viaje y ahorra en gasolina</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3>Mis Viajes</h3>
                <p>Revisa tu historial de aventones</p>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="bi bi-person"></i>
                </div>
                <h3>Mi Perfil</h3>
                <p>Actualiza tu información personal</p>
            </div>
        </div>
    </main>
</body>
</html>
