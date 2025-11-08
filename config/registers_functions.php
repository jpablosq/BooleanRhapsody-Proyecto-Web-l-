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

// Obtener todos las solicitudes 
function getAllRegisters() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT r.*, u.nombre, u.apellidos, u.nombre_usuario FROM Registros r INNER JOIN Usuarios u ON r.id_usuario = u.id_usuario');
    return $stmt->fetchAll();
}

// Obtener todos las solicitudes por estado 
function getAllRegistersForStatus($estado) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM Registros WHERE estado = ? ORDER BY id_usuario DESC');
    $stmt->execute([$estado]);
    return $stmt->fetch();
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



function AsignarRolChofer($id) {
    $pdo = getConnection();
    $registro = getRegistersById($id);
    if (!$registro) {
        return false;
    }

    $stmt = $pdo->prepare('UPDATE Usuarios SET rol = "Chofer" WHERE id_usuario = ?');
    return $stmt->execute([$registro['id_usuario']]);
}

function AgregarRegisterVehiculos($id) {
    $pdo = getConnection();
    $registro = getRegistersById($id);
    if (!$registro) {
        return false;
    }

    return createVehicle($registro['id_usuario'], $registro['marca'], $registro['modelo'], $registro['anio_fabricacion'], $registro['color'], $registro['placa'], $registro['fotografia']);
}


// Modificar el estado a 'aceptado'
function aceptarRegister($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE Registros SET estado = "aceptado", descripcion = "Cumple con todos los requisitos" WHERE id_registro = ?');
    // Primero actualizamos el estado
    $stmt->execute([$id]);

    // Luego agregamos el vehículo solo si el UPDATE fue exitoso
    if ($stmt->rowCount() > 0) {
        AgregarRegisterVehiculos($id);
        AsignarRolChofer($id);
        return true;
    }

    return false;

}

// Modificar el estado a 'rechazado'
function rechazarRegister($id, $motivo) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE Registros SET estado = "rechazado", descripcion = ? WHERE id_registro = ?');
    return $stmt->execute([$motivo, $id]);
}
?>
