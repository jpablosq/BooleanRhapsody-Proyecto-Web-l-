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

// Obtener todos las Solicitudes 
function getAllRegisters() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM Registros ORDER BY id_usuario DESC');
    return $stmt->fetchAll();
}

// Obtener todos las solicitudes de un usuario
function getAllUserRegisters($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('
        SELECT r.*, u.nombre, u.apellidos, u.nombre_usuario 
        FROM Registros r 
        INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
        WHERE r.id_usuario = ?
    ');
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Obtener registros por ID de registro
function getRegistersByid($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT r.*, u.nombre, u.apellidos, u.nombre_usuario 
        FROM Registros r 
        INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario 
        WHERE r.id_registro = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Eliminar registro
function deleteRegisters($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM Registros WHERE id_registro = ?");
    return $stmt->execute([$id]);
}

// Crear nuevo registro
function createRegisters($idUsuario, $marca, $modelo, $anio, $color, $placa, $foto) {
    $pdo = getConnection();
 
    $stmt = $pdo->prepare("INSERT INTO Registros (
        id_usuario,
        marca,
        modelo,
        anio_fabricacion,
        color,
        placa,
        fotografia
    ) VALUES (
        ?, 
        ?, 
        ?,
        ?,
        ?,
        ?,
        ?
    )");
    return $stmt->execute([$idUsuario, $marca, $modelo, $anio, $color, $placa, $foto]);
}

// Validar datos del registro
function validateRegisters($marca, $modelo, $anio, $color, $placa) {
    $errors = [];

    foreach (getAllRegisters() as $vehiculo){    
        if($vehiculo['placa'] === $placa){
            $errors[] = "Esta placa ya está registrada";
        } 
    }

    // Validar placa
    if (empty(trim($placa))) {
        $errors[] = "La placa es requerida";
    } elseif (!preg_match('/^[A-Z0-9-]{3,10}$/i', $placa)) {
        $errors[] = "La placa debe contener números o letras (9 a 12 dígitos)";
    }

    // Validar marca
    if (empty(trim($marca))) {
        $errors[] = "La marca es requerida";
    }

    // Validar modelo
    if (empty(trim($modelo))) {
        $errors[] = "El modelo es requerido";
    }

    // Validar año de fabricación
    if (empty(trim($anio))) {
        $errors[] = "El año de fabricación es requerido";
    } elseif ($anio < 2004 || $anio > date('Y') + 1) {
        $errors[] = "El año de fabricación no es válido";
    }

    if (empty(trim($color))) {
        $errors[] = "El color es requerido";
    }

    return $errors;
}
?>
