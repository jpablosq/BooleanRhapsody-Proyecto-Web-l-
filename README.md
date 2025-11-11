# **BooleanRhapsody – Proyecto Web 1: Aventones**

## 🧭 **Descripción del Proyecto**
**Aventones** es una aplicación web de **carpooling** que conecta conductores y pasajeros con rutas similares.  
Su propósito es **optimizar los desplazamientos**, **reducir los costos de transporte** y **promover una movilidad más sostenible y colaborativa**.

## 👥 **Integrantes del Proyecto**
- **Jose Pablo Soto Quesada**  
- **Maikel Chaves Salas**

## **Creación del login y register**
Nos basamos en un login previamente trabajado en otros proyectos y lo adaptamos para este, agregándole una imagen y modificando gran parte de su estilo para que fuera único. Además, el formulario de registro también se ajustó a los requerimientos solicitados en el proyecto y al modelo de base de datos.
```sql
CREATE TABLE Usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    fotografia VARCHAR(255),
    telefono VARCHAR(20),
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL DEFAULT 'Pasajero' CHECK (rol IN ('Administrador', 'Pasajero', 'Chofer')),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO Usuarios (nombre, apellidos, cedula, fecha_nacimiento, correo, telefono, nombre_usuario, contrasena)
VALUES ('Pablo', 'Soto', '208680726', '2005-08-11', 'pablosq@gmail.com', '83982270', 'pablosq', SHA2('pablosq123', 256));

INSERT INTO Usuarios (nombre, apellidos, cedula, fecha_nacimiento, correo, telefono, nombre_usuario, contrasena)
VALUES ('Maikel', 'Chaves', '987654321', '2005-03-10', 'maikelch@gmail.com', '88329023', 'mchaves', SHA2('mchaves123', 256));
```
Podemos observar el script para la creación de la tabla Usuarios, donde solicitamos los datos requeridos por el proyecto. Los atributos más destacables son fotografía y rol. Para el primero, optamos por trabajar como de costumbre utilizando la URL de la imagen, de manera que pueda cargarse al usuario correspondiente. En cuanto al rol, teníamos dos opciones: la primera, como se implementó, consiste en definirlo directamente en la base de datos, asignando por defecto el valor 'Pasajero' cuando no se especifica. La segunda opción era gestionarlo mediante una tabla intermedia, lo cual sería útil en caso de requerir nuevos roles en el futuro. Sin embargo, por simplicidad, decidimos utilizar la primera alternativa.

## **Creación del CRUD de vehiculos**
Siguiendo el mismo estilo del apartado de login/register, creamos una sección donde el usuario con rol de “pasajero” puede registrar su vehículo. Una vez que su solicitud sea aceptada, pasará a tener el rol de “conductor”, lo que le permitirá ofrecer futuros aventones.
De acuerdo con el modelo de base de datos, esta será la tabla correspondiente a los vehículos
```sql
CREATE TABLE Vehiculos (
    id_vehiculo SERIAL PRIMARY KEY,
    id_usuario INT REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    anio_fabricacion INT NOT NULL,
    color VARCHAR(30),
    placa VARCHAR(20) UNIQUE NOT NULL,
    fotografia VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
En el campo que referencia al usuario propietario del vehículo, agregamos la cláusula ON DELETE CASCADE. Esto permite que, al eliminar un usuario de la aplicación, se eliminen automáticamente todos los vehículos asociados, evitando así posibles errores de integridad en la base de datos.
Posteriormente, añadimos los campos solicitados en el requerimiento para completar la estructura de la tabla

## **Creación del CRUD de registros**
Siguiendo el mismo estilo del los demas apartados, el usuario que mande la solicitud el vehiculo pasara por esta tabla a espere de que un admin la acepte
```sql
CREATE TABLE Registros (
    id_registro SERIAL PRIMARY KEY,
    id_usuario INT REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    anio_fabricacion INT NOT NULL,
    color VARCHAR(30),
    placa VARCHAR(20) UNIQUE NOT NULL,
    fotografia VARCHAR(255),
    descripcion VARCHAR(300) NOT NULL DEFAULT '',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(30) DEFAULT 'pendiente'
);
```
A diferencia de vehiculos solo se agrega el  y la descripcion nuevos, optamos en hacerlo con tablas diferentes para que alguien de mayor rango pueda ver todas las solicitudes y los motivos del porque fueron aprobadas o rechazadas sin necesidad de tener que ir a la tabla de vehiculos una vez este aceptado se agregara el registro a la tabla vehiculos

```sql
CREATE TABLE Viajes (
    id_viaje SERIAL PRIMARY KEY,
    id_chofer INT NOT NULL REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    id_vehiculo INT NOT NULL REFERENCES Vehiculos(id_vehiculo) ON DELETE CASCADE,
    nombre_viaje VARCHAR(100) NOT NULL,
    lugar_salida VARCHAR(150) NOT NULL,
    hora_salida TIME NOT NULL,
    lugar_llegada VARCHAR(150) NOT NULL,
    hora_llegada TIME NOT NULL,
    dias_semana VARCHAR(100) NOT NULL, 
    tarifa_espacio DECIMAL(10,2) NOT NULL,
    espacios_disponibles INT NOT NULL CHECK (espacios_disponibles >= 0),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
## **Creación del CRUD de raids y inbox**
Siguiendo con el mismo estilo creamos el crud para generar los riads, tambien creamos la pantalla donde podremos visualizar todos los raids disponibles en nuestra base de datos, donde podremos filtrar por lugar de salida, llegada, hora de salida, fecha etc...