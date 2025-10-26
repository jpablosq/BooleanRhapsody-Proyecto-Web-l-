<?php  
require_once '../config/start_app.php';  
require_once '../config/database.php';  

// session_start();
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["username"] ?? '');
    $contrasena = trim($_POST["password"] ?? '');

    // Validar que los campos no estén vacíos
    if (empty($usuario) || empty($contrasena)) {
        $_SESSION["error"] = "Por favor complete todos los campos";
        header("Location: login.php");
        exit();
    }

    // Prueba simple de usuario y contraseña hardcodeados
    if ($usuario == "admin" && $contrasena == "123") {
        $_SESSION["usuario"] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["error"] = "Usuario o contraseña incorrectos";
        header("Location: login.php");
    }

    try {
        // Crear conexión PDO
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        // Preparar consulta para buscar el usuario
        $stmt = $pdo->prepare("SELECT id, username, password_hash, status FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$usuario]);
        $user_data = $stmt->fetch();

        // Verificar si el usuario existe y está activo
        if ($user_data && $user_data['status'] == 'active') {
            // Verificar la contraseña usando SHA256
            $password_hash = hash('sha256', $contrasena);

            if ($password_hash == $user_data['password_hash']) {
                // Login exitoso
                $_SESSION["usuario"] = $user_data['username'];
                $_SESSION["user_id"] = $user_data['id'];

                // Limpiar cualquier error previo
                unset($_SESSION["error"]);

                header("Location: index.php");
                exit();
            } else {
                $_SESSION["error"] = "Usuario o contraseña incorrectos";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION["error"] = "Usuario o contraseña incorrectos";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        // En producción, no mostrar detalles del error
        error_log("Error de base de datos en login: " . $e->getMessage());
        $_SESSION["error"] = "Error del sistema. Intente más tarde.";
        header("Location: login.php");
        exit();
    }
}
?>
