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