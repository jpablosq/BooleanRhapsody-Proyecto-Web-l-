<?php
require_once 'database.php';

// Función para crear conexión PDO
function getConnection() {
    global $host, $db, $user, $password;
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Obtener todos los usuarios
function getAllUsers() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM Usuarios ORDER BY fecha_registro DESC');
    return $stmt->fetchAll();
}

// Obtener producto por ID
function getUserById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Eliminar Usuario
function deleteUser($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM Usuarios WHERE id = ?");
    return $stmt->execute([$id]);
}

// Crear nuevo producto
function createUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $fotografia, $telefono, $nombre_usuario, $contrasena, $rol) {
    $pdo = getConnection();

    // Hashear contraseña en PHP
    $password_hash = hash('sha256', $contrasena);

    $stmt = $pdo->prepare("INSERT INTO Usuarios (
        nombre,
        apellidos,
        cedula,
        fecha_nacimiento,
        correo,
        fotografia,
        telefono,
        nombre_usuario,
        contrasena,
        rol
    ) VALUES (
        ?, 
        ?, 
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )");
    return $stmt->execute([$nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $fotografia, $telefono, $nombre_usuario, $password_hash, $rol]);
}

// Validar datos del usuario
function validateUser($nombre, $apellidos, $cedula, $fecha_nacimiento, $correo, $telefono, $nombre_usuario, $contrasena, $confirmar_contrasena) {
    // Obtener todos los usuarios
    $errors = [];

    foreach (getAllUsers() as $usuario){
        if($usuario['cedula'] === $cedula){
            $errors[] = "La cédula ya está registrada";
        } 
        if($usuario['nombre_usuario'] === $nombre_usuario){
            $errors[] = "El usuario ya extiste";
        }
        if($usuario['correo'] === $correo){
            $errors[] = "El correo ya está registrado";
        }
    }
    // Validar nombre
    if (empty(trim($nombre))) {
        $errors[] = "El nombre es requerido";
    } elseif (strlen(trim($nombre)) > 100) {
        $errors[] = "El nombre no puede exceder los 100 caracteres";
    }

    // Validar apellidos
    if (empty(trim($apellidos))) {
        $errors[] = "Los apellidos son requeridos";
    } elseif (strlen(trim($apellidos)) > 100) {
        $errors[] = "Los apellidos no pueden exceder los 100 caracteres";
    }

    // Validar cédula (puedes ajustar la longitud según tu país)
    if (empty(trim($cedula))) {
        $errors[] = "La cédula es requerida";
    } elseif (!preg_match('/^[0-9]{9,12}$/', $cedula)) {
        $errors[] = "La cédula debe contener solo números (9 a 12 dígitos)";
    }

    // Validar fecha de nacimiento
    if (empty($fecha_nacimiento)) {
        $errors[] = "La fecha de nacimiento es requerida";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nacimiento)) {
        $errors[] = "La fecha de nacimiento debe tener el formato YYYY-MM-DD";
    }

    // Validar correo
    if (empty(trim($correo))) {
        $errors[] = "El correo electrónico es requerido";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico no es válido";
    }

    // Validar teléfono
    if (empty(trim($telefono))) {
        $errors[] = "El número de teléfono es requerido";
    } elseif (!preg_match('/^[0-9]{8,15}$/', $telefono)) {
        $errors[] = "El teléfono debe contener solo números (8 a 15 dígitos)";
    }

    // Validar nombre de usuario
    if (empty(trim($nombre_usuario))) {
        $errors[] = "El nombre de usuario es requerido";
    } elseif (strlen(trim($nombre_usuario)) > 50) {
        $errors[] = "El nombre de usuario no puede exceder los 50 caracteres";
    }

    // Validar contraseñas
    if (empty($contrasena) || empty($confirmar_contrasena)) {
        $errors[] = "Debe ingresar y confirmar la contraseña";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $errors[] = "Las contraseñas no coinciden";
    } elseif (strlen($contrasena) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres";
    }

    return $errors;
}