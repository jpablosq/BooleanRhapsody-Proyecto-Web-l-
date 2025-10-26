<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - Aventones</title>
  <link rel="stylesheet" href="style_registro.css">
</head>
<body>
  <div class="card">
    <h2>Crear cuenta</h2>
    <form>
      <!-- Nombre y Apellidos -->
      <div class="row">
        <div class="field half">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" placeholder="Ej. Juan">
        </div>
        <div class="field half">
          <label for="apellido1">Apellido 1</label>
          <input type="text" id="apellido1" placeholder="Ej. Pérez">
        </div>
      </div>
      <div class="row">
        <div class="field half">
          <label for="apellido2">Apellido 2</label>
          <input type="text" id="apellido2" placeholder="Ej. López">
        </div>
        <div class="field half">
          <label for="cedula">Cédula de identidad</label>
          <input type="text" id="cedula" placeholder="Sólo números">
        </div>
      </div>

      <!-- Fecha de nacimiento y correo -->
      <div class="row">
        <div class="field half">
          <label for="fecha_nac">Fecha de nacimiento</label>
          <input type="date" id="fecha_nac">
        </div>
        <div class="field half">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" placeholder="tu@correo.com">
        </div>
      </div>

      <!-- Teléfono y usuario -->
      <div class="row">
        <div class="field half">
          <label for="telefono">Número telefónico</label>
          <input type="tel" id="telefono" placeholder="Ej. 50685911345">
        </div>
        <div class="field half">
          <label for="username">Nombre de usuario</label>
          <input type="text" id="username" placeholder="usuario123">
        </div>
      </div>

      <!-- Foto -->
      <div class="row">
        <div class="field half">
          <label for="foto">Subir fotografía</label>
          <input type="file" id="foto" accept="image/*">
        </div>
        <div class="field half">
          <div class="photo-circle" id="preview"></div>
        </div>
      </div>

      <!-- Contraseña -->
      <div class="row">
        <div class="field half">
          <label for="password">Contraseña</label>
          <input type="password" id="password" placeholder="Mínimo 8 caracteres">
        </div>
        <div class="field half">
          <label for="confirm_password">Confirmar contraseña</label>
          <input type="password" id="confirm_password" placeholder="Repite la contraseña">
        </div>
      </div>

      <!-- Botones -->
      <div class="actions">
        <button type="reset" class="btn secondary">Limpiar</button>
        <button type="submit" class="btn">Registrarme</button>
      </div>
    </form>
  </div>
</body>
</html>
