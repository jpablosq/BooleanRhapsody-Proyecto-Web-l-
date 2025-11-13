<?php
    require_once '../../config/start_app.php';
    require_once '../../config/database.php';

    if(isset($_SESSION["usuario"])){
        header("Location: ../index.php");
        exit();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $usuario = trim($_POST["username"] ?? '');  //El trim es para borrar los espacios en blanco
        $contrasena = trim($_POST["password"] ?? '');

        // Validar que los espacios no vengan vacios
        if(empty($usuario) || empty($contrasena)){
            $_SESSION["error"] = "Por favor complete los campos!";
            header("Location: login.php");
            exit();
        }
        try{
            // Crear conexión PDO
            $dsn = "mysql:host=$host; dbname=$db; charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            // Prepara consulta para buscar usuario
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? LIMIT 1");
            $stmt->execute([$usuario]);
            $user_data = $stmt->fetch();

            // Verificar la contraseña usando SHA256
            $password_hash = hash('sha256', $contrasena);

            if($password_hash === $user_data['contrasena']){
                // Login exitoso
                $_SESSION["usuario"] = $user_data;

                // Limpiar cualquier error previo
                unset($_SESSION["error"]);

                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION["error"] = "Usuario o contraseña incorrectos";
                header("Location: login.php");
                exit();
            }
               
        } catch(PDOException $e) {
            // En producción, no mostrar detalles del error
            error_log("Error de base de datos en login: " . $e->getMessage());
            $_SESSION["error"] = "Error del sistema. Intente más tarde. ";
            header("Location: login.php");
            exit();
        }
    }