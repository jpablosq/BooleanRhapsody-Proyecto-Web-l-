<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Aventones</title>
    <link rel="stylesheet" href="../styles/styles_login_register.css">
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

                    <form id="registerForm">
                        <div class="input-row">
                            <input type="text" class="input" placeholder="Nombre" required>
                            <input type="text" class="input" placeholder="Apellidos" required>
                        </div>

                        <div class="input-group">
                            <input type="text" class="input" placeholder="Cédula" required>
                        </div>

                        <div class="input-group">
                            <input type="date" class="input" placeholder="Fecha de nacimiento" required>
                        </div>

                        <div class="input-group">
                            <input type="email" class="input" placeholder="Correo electrónico" required>
                        </div>

                        <div class="input-group">
                            <input type="tel" class="input" placeholder="Número de teléfono" required>
                        </div>

                        <div class="input-group">
                            <input type="text" class="input" placeholder="Nombre de usuario" required>
                        </div>

                        <div class="input-group">
                            <label for="photo" class="lblPhoto">
                                <i class="bi bi-camera-fill"></i> Fotografía (opcional)
                                <input type="file" id="photo" class="photo" accept="image/*">
                            </label>
                        </div>

                        <div class="input-group password-group">
                            <input type="password" class="input" id="password" placeholder="Contraseña" required>
                            <button type="button" class="password-toggle">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>

                        <div class="input-group password-group">
                            <input type="password" class="input" id="confirmPassword" placeholder="Confirmar contraseña" required>
                            <button type="button" class="password-toggle">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>

                        <button type="submit" class="submit-btn">
                            Crear cuenta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
