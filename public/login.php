<?php
    require_once '../config/start_app.php';

    // Si el usuario ya inició sesión lo redireccionamos al index
    if(isset($_SESSION["usuario"])){
        header("Location: index.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pide Raid - Iniciar Sesión</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Pide Raid</h2>
            <samp>
                    <?php  if(isset($_SESSION["error"])):?>
                        <div class= " alert alert-danger alert-diamissible fade show mt-3"role="alert">
                         <?php echo $_SESSION["error"];?>
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION["error"]); ?>
                        <?php endif;?>
            </samp>
        <form action="procesos_login.php" method="POST" class="mt-4">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
            <samp>
                    <?php
                    if(isset($_SESSION["usuario"])){
                         echo $_SESSION["error"];
                    }
                    ?>

            </samp>
        <div class="footer">
            ¿No tienes cuenta? <span><a href="registro.php">Regístrate</a></span>
        </div>
    </div>
</body>
</html>

