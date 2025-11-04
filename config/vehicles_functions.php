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
function getAllvehiculos() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM vehiculos ORDER BY placa DESC');
    return $stmt->fetchAll();
}

// Obtener vehiculos por ID
function getvehiculosById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Eliminar vehiculos
function deletevehiculos($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM vehiculos WHERE id = ?");
    return $stmt->execute([$id]);
}

// Crear nuevo vehiculo
function createVehiculo($marca, $modelo, $anio, $color, $placa, $foto) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("INSERT INTO vehiculos (
        marca,
        modelo,
        anio,
        color,
        placa,
        foto
    ) VALUES (
        ?, 
        ?, 
        ?,
        ?,
        ?,
        ?
    )");
    return $stmt->execute([$marca, $modelo, $anio, $color, $placa, $foto]);
}

//Modificar Vehiculo
function updateVehiculo($id, $marca, $modelo, $anio, $color, $placa, $foto) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("UPDATE vehiculos SET 
        marca = ?, 
        modelo = ?, 
        anio = ?, 
        color = ?, 
        placa = ?, 
        foto = ?
    WHERE id = ?");

    return $stmt->execute([$marca, $modelo, $anio, $color, $placa, $foto, $id]);
}

// Validar datos del vehiculo
function validateVehiculos($marca, $modelo, $anio, $color, $placa, $foto) {
    // Obtener todos los vehiculo
    $errors = [];

    foreach (getAllvehiculos() as $vehiculo){
        if($vehiculo['placa'] === $placa){
            $errors[] = "Esta placa ya está registrada";
        } 
    }


// Validar placa (puedes ajustar la longitud según tu país)
    if (empty(trim($placa))) {
        $errors[] = "La placa es requerida";
    }   elseif (!preg_match('/^[A-Z0-9-]{3,10}$/i', $placa)) {
        $errors[] = "La placa debe contener numeros o y letras  (9 a 12 dígitos)";
    }

return $errors;
}


