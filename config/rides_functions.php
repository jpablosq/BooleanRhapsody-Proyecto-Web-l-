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

// Obtener todos los rides
function getAllRides() {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM Viajes ORDER BY id_viaje DESC');
    return $stmt->fetchAll();
}

// Obtener todos los rides disponibles
function getAllAvailableRides() {
    $pdo = getConnection();
    $stmt = $pdo->query("
        SELECT 
            v. *,
            u.nombre AS nombre_chofer,
            u.apellidos AS apellidos_chofer,
            u.fotografia,
            ve.marca,
            ve.modelo
        FROM Viajes v
        INNER JOIN Usuarios u ON v.id_chofer = u.id_usuario
        INNER JOIN Vehiculos ve ON v.id_vehiculo = ve.id_vehiculo
        WHERE v.espacios_disponibles > 0 
    ");
    return $stmt->fetchAll();
}

// Obtener rides por ID
function getRidesById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT v. *, u.nombre, u.apellidos, u.cedula, u.fotografia as fotoUsuario, u.telefono, ve.marca, ve.modelo, ve.placa, ve.color, ve.fotografia
                            FROM Viajes v
                            INNER JOIN Usuarios u ON v.id_chofer = u.id_usuario 
                            INNER JOIN Vehiculos ve ON v.id_vehiculo = ve.id_vehiculo 
                            WHERE id_viaje = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Obtener rides del usuario
function getRidesByUserId($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT v. *, u.nombre, u.apellidos, ve.marca, ve.modelo 
    FROM Viajes v 
    INNER JOIN Usuarios u ON v.id_chofer = u.id_usuario 
    INNER JOIN Vehiculos ve ON v.id_vehiculo = ve.id_vehiculo 
    WHERE v.id_chofer = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Obtener los vehículos de un usuario
function getAllUserVehicles($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT v.*, u.nombre, u.apellidos, u.nombre_usuario FROM vehiculos v INNER JOIN Usuarios u ON v.id_usuario = u.id_usuario WHERE v.id_usuario = ? AND v.estado = "aceptado"');
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Eliminar ride
function deleteRides($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM Viajes WHERE id_viaje = ?");
    return $stmt->execute([$id]);
}

// Crear nuevo ride
function createRide($id_chofer, $id_vehiculo, $nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("INSERT INTO Viajes (
        id_chofer,
        id_vehiculo,
        nombre_viaje,
        lugar_salida,
        hora_salida,
        lugar_llegada,
        hora_llegada,
        dias_semana,
        tarifa_espacio,
        espacios_disponibles
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

    return $stmt->execute([$id_chofer,$id_vehiculo,$nombre_viaje,$lugar_salida,$hora_salida,$lugar_llegada,$hora_llegada,$dias_semana,$tarifa_espacio,$espacios_disponibles]);
}

// Actualizar un ride existente
function updateRide($id_vehiculo, $nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles, $id_viaje) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("UPDATE Viajes SET
        id_vehiculo = ?,
        nombre_viaje = ?,
        lugar_salida = ?,
        hora_salida = ?,
        lugar_llegada = ?,
        hora_llegada = ?,
        dias_semana = ?,
        tarifa_espacio = ?,
        espacios_disponibles = ?
    WHERE id_viaje = ?");

    return $stmt->execute([$id_vehiculo, $nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles, $id_viaje]);
}



// Validar datos del ride
function validateRides($nombre_viaje, $lugar_salida, $hora_salida, $lugar_llegada, $hora_llegada, $dias_semana, $tarifa_espacio, $espacios_disponibles) {
    $errors = [];

    // Validar nombre del viaje
    if (empty(trim($nombre_viaje))) {
        $errors[] = "El nombre del viaje es requerido.";
    } elseif (strlen(trim($nombre_viaje)) > 100) {
        $errors[] = "El nombre del viaje no puede exceder los 100 caracteres.";
    }

    // Validar lugar de salida
    if (empty(trim($lugar_salida))) {
        $errors[] = "El lugar de salida es requerido.";
    } elseif (strlen(trim($lugar_salida)) > 150) {
        $errors[] = "El lugar de salida no puede exceder los 150 caracteres.";
    }

    // Validar hora de salida
    if (empty($hora_salida)) {
        $errors[] = "La hora de salida es requerida.";
    } elseif (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $hora_salida)) {
        $errors[] = "La hora de salida debe tener el formato HH:MM (24 horas).";
    }

    // Validar lugar de llegada
    if (empty(trim($lugar_llegada))) {
        $errors[] = "El lugar de llegada es requerido.";
    } elseif (strlen(trim($lugar_llegada)) > 150) {
        $errors[] = "El lugar de llegada no puede exceder los 150 caracteres.";
    }

    // Validar hora de llegada 
    if (empty($hora_llegada)) {
        $errors[] = "La hora de llegada es requerida.";
    } elseif (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $hora_llegada)) {
        $errors[] = "La hora de llegada debe tener el formato HH:MM (24 horas).";
    }

    // Validar relación entre horas
    if (!empty($hora_salida) && !empty($hora_llegada)) {
        if (strtotime($hora_llegada) < strtotime($hora_salida)) {
            $errors[] = "La hora de llegada debe ser posterior a la hora de salida.";
        }
    }

   // Validar día de la semana 
    if (empty(trim($dias_semana))) {
        $errors[] = "Debe seleccionar un día de la semana.";
    } elseif (!preg_match('/^(Lunes|Martes|Miércoles|Jueves|Viernes|Sábado|Domingo)$/u', trim($dias_semana))) {
        $errors[] = "El día seleccionado no es válido. Debe ser UNO de: Lunes, Martes, Miércoles, Jueves, Viernes, Sábado o Domingo.";
    }

    // Validar tarifa por espacio
    if (!is_numeric($tarifa_espacio) || $tarifa_espacio < 0) {
        $errors[] = "Tiene que agregar una tarifa";
    }

    // Validar cantidad de espacios disponibles
    if (!is_numeric($espacios_disponibles) || $espacios_disponibles < 1) {
        $errors[] = "Debe agregar una cantidad";
    }

    return $errors;
}


// Crear solicitud de ride
function createRideRequest($id_viaje, $id_pasajero, $metodo, $cantidad_espacios, $estado) {
    $pdo = getConnection();

    $stmt = $pdo->prepare("INSERT INTO SolicitudesViaje (
        id_viaje,
        id_pasajero,
        metodo,
        cantidad_espacios,
        estado
    ) VALUES (
        ?,
        ?,
        ?,
        ?,
        ?
    )");

    return $stmt->execute([$id_viaje, $id_pasajero, $metodo, $cantidad_espacios, $estado]);
}

// Obtener solicitudes de rides de un chofer especifico
function getDriverRidesRequest($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT sv. *, u.nombre, u.apellidos, u.cedula, u.fotografia, u.telefono, u.fecha_registro, v.nombre_viaje, v.lugar_salida, v.hora_salida, v.lugar_llegada, v.hora_llegada, v.dias_semana, v.tarifa_espacio, v.espacios_disponibles 
    FROM SolicitudesViaje sv
    INNER JOIN Viajes v ON v.id_viaje = sv.id_viaje 
    INNER JOIN Usuarios u ON u.id_usuario = sv.id_pasajero 
    WHERE v.id_chofer = ? ORDER BY CASE sv.estado WHEN 'pendiente' THEN 1 WHEN 'aceptado' THEN 2 WHEN 'cancelado' THEN 3 WHEN 'rechazado' THEN 4 ELSE 5 END" );
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Obtener solicitudes de rides del usuario especifico
function getUserRidesRequest($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT sv. *, u.nombre, u.apellidos, u.cedula, u.fotografia, u.telefono, u.fecha_registro, v.nombre_viaje, v.lugar_salida, v.hora_salida, v.lugar_llegada, v.hora_llegada, v.dias_semana, v.tarifa_espacio, v.espacios_disponibles 
    FROM SolicitudesViaje sv
    INNER JOIN Viajes v ON v.id_viaje = sv.id_viaje 
    INNER JOIN Usuarios u ON u.id_usuario = sv.id_pasajero 
    WHERE u.id_usuario = ? ORDER BY CASE sv.estado WHEN 'aceptado' THEN 1 WHEN 'pendiente' THEN 2 WHEN 'rechazado' THEN 3 WHEN 'cancelado' THEN 4 ELSE 5 END" );
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Obtener solicitudes de rides con datos del pasajero por id
function getRideRequestById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT sv.*, u.nombre, u.apellidos, u.cedula, u.fotografia, u.telefono, u.fecha_registro, v.nombre_viaje, v.lugar_salida, v.hora_salida, v.lugar_llegada, v.hora_llegada, v.dias_semana, v.tarifa_espacio, v.espacios_disponibles, v.fecha_publicacion
    FROM SolicitudesViaje sv
    INNER JOIN Viajes v ON v.id_viaje = sv.id_viaje 
    INNER JOIN Usuarios u ON u.id_usuario = sv.id_pasajero 
    WHERE sv.id_solicitud = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Obtener solicitudes de rides con datos del chofer por id
function getRideDriverRequestById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT sv.*, v.*, c.nombre, c.apellidos, c.cedula, c.telefono, c.fotografia, c.fecha_registro
    FROM SolicitudesViaje sv
    JOIN Viajes v ON sv.id_viaje = v.id_viaje
    JOIN Usuarios c ON v.id_chofer = c.id_usuario 
    WHERE sv.id_solicitud = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function subtractSpaces($id_viaje, $cantidad) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE Viajes SET espacios_disponibles = espacios_disponibles - ? WHERE id_viaje = ?');
    return $stmt->execute([$cantidad, $id_viaje]);

}
// Modificar el estado a 'aceptado'
function acceptRide($id, $cantidad) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE SolicitudesViaje SET estado = "aceptado" WHERE id_solicitud = ?');

    $solicitud = getRideRequestById($id);
    if (!empty($solicitud)) {
        $viaje = getRidesById($solicitud['id_viaje']);
        $id_viaje = $viaje['id_viaje'];
        $espacios_disponibles = $viaje['espacios_disponibles'];
        if(!empty($viaje) && $espacios_disponibles >= $cantidad){
            // actualizamos el estado
            $stmt->execute([$id]);

            // Restar los espacios del viaje
            subtractSpaces($id_viaje, $cantidad);
            return true;
        }
    }
    return false;
}

// Modificar el estado a 'rechazado'
function declineRide($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE SolicitudesViaje SET estado = "rechazado" WHERE id_solicitud = ?');
    return $stmt->execute([$id]);
}

function addSpaces($id_viaje, $cantidad) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE Viajes SET espacios_disponibles = espacios_disponibles + ? WHERE id_viaje = ?');
    return $stmt->execute([$cantidad, $id_viaje]);

}

// Modificar el estado a 'aceptado'
function leaveRide($id, $cantidad) {
    $pdo = getConnection();
    $stmt = $pdo->prepare('UPDATE SolicitudesViaje SET estado = "cancelado" WHERE id_solicitud = ?');
    // Primero actualizamos el estado
    $stmt->execute([$id]);

    $solicitud = getRideRequestById($id);
    if ($stmt->rowCount() > 0) {
        if (!empty($solicitud)) {
            $viaje = getRidesById($solicitud['id_viaje']);
            $id_viaje = $viaje['id_viaje'];
            if(!empty($viaje)){
                // Agregar los espacios de vuelta al viaje
                addSpaces($id_viaje, $cantidad);
                return true;
            }
        }
    }
    return false;
}