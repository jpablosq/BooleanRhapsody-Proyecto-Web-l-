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

// Obtener todos los vehiculos
function getAllVehicles() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM vehiculos ORDER BY placa DESC');
    return $stmt->fetchAll();
}

// Obtener todos los vehiculos de un usuario
function getAllUserVehicles($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT v.*, u.nombre, u.apellidos, u.nombre_usuario FROM vehiculos v INNER JOIN Usuarios u ON v.id_usuario = u.id_usuario WHERE v.id_usuario = ?');
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Obtener vehiculos por ID
function getVehiclesById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT v.*, u.nombre, u.apellidos, u.nombre_usuario FROM vehiculos v INNER JOIN Usuarios u ON v.id_usuario = u.id_usuario WHERE v.id_vehiculo = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Eliminar vehiculos
function deleteVehicles($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM vehiculos WHERE id_vehiculo = ?");
    return $stmt->execute([$id]);
}

// Crear nuevo vehiculo
function createVehicle($idUsuario, $marca, $modelo, $anio, $color, $placa, $foto) {
    $pdo = getConnection();
 
    $stmt = $pdo->prepare("INSERT INTO Vehiculos (
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

//Modificar Vehiculo
function updateVehicle($id, $marca, $modelo, $anio, $color, $placa, $foto) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("UPDATE vehiculos SET 
        marca = ?, 
        modelo = ?, 
        anio_fabricacion = ?, 
        color = ?, 
        placa = ?, 
        fotografia = ?
    WHERE id_vehiculo = ?");

    return $stmt->execute([$marca, $modelo, $anio, $color, $placa, $foto, $id]);
}

// Validar datos del vehiculo
function validateVehicles($marca, $modelo, $anio, $color, $placa) {
    // Obtener todos los vehiculo
    $errors = [];

    foreach (getAllVehicles() as $vehiculo){    
        if($vehiculo['placa'] === $placa){
            $errors[] = "Esta placa ya está registrada";
        } 
    }

    // Validar placa
    if (empty(trim($placa))) {
        $errors[] = "La placa es requerida";
    }   elseif (!preg_match('/^[A-Z0-9-]{3,10}$/i', $placa)) {
        $errors[] = "La placa debe contener numeros o y letras  (9 a 12 dígitos)";
    }

    // Validar marca
    if (empty(trim($marca))) {
        $errors[] = "La marca es requerida";
    }

    // Validar modelo
    if (empty(trim($modelo))) {
        $errors[] = "El modelo es requerido";
    }

    // Validar fabricacion
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