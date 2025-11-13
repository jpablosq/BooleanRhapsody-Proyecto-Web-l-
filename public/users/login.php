<?php
    require_once '../../config/start_app.php';

    // Si el usuario ya inició sesión lo redireccionamos al index
    if(isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio sesión - Aventones</title>
    <link rel="stylesheet" href="../assets/styles/styles_login_register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
                    <span>
                        <?php if(isset($_SESSION["error"])): ?>
                            <div class= "alert alert-danger alert-diamissible fade show mt-3" role="alert">
                                <?php echo $_SESSION["error"]; ?>
                            </div>
                            <?php unset($_SESSION["error"]); ?>
                        <?php endif;?>
                    </span>
                    <span>
                        <?php if(isset($_SESSION["success"])): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <?php echo $_SESSION["success"]; ?>
                            </div>
                            <?php unset($_SESSION["success"]); ?>
                        <?php endif;?>
                    </span>
                    <h2 class="form-title">Bienvenido de nuevo</h2>
                    <p class="form-subtitle">
                        ¿No tienes una cuenta? <a href="register.php" class="link">Regístrate</a>
                    </p>

                    <form action="login_procces.php" method="POST" class="mt-4" id="loginForm">
                        <div class="input-group">
                            <input type="text" class="input" id="username" name="username" placeholder="Usuario">
                        </div>

                        <div class="input-group password-group">
                            <input type="password" class="input" id="password" name="password" placeholder="Ingresa tu contraseña">
                            <button type="button" class="password-toggle">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>

                        <div class="remember-forgot">
                            <div class="checkbox-group">
                                <input type="checkbox" id="remember">
                                <label for="remember">Recuérdame</label>
                            </div>
                            <a href="#" class="link">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="submit-btn">Iniciar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Funcionalidad para ver la password
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
    </script>
</body>
</html>

