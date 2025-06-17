<?php
// login.php
include("includes/conexBDD.php");  // Debe definir $connPHP
session_start();

$mensajeRegistro = "";


// --- LÓGICA DE REGISTRO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    $nombre = trim($_POST['nombre_reg']  ?? '');
    $email  = trim($_POST['email_reg']   ?? '');
    $p1     = $_POST['password_reg']     ?? '';
    $p2     = $_POST['password2_reg']    ?? '';

    if ($nombre === '' || $email === '' || $p1 === '' || $p2 === '') {
        $mensajeRegistro = "Todos los campos son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensajeRegistro = "Email no es válido.";
    } elseif ($p1 !== $p2) {
        $mensajeRegistro = "Las contraseñas no coinciden.";
    } else {
        // Comprueba existencia
        $stmt = $connPHP->prepare("
            SELECT 1
              FROM `cliente`
             WHERE `Email` = ?
             LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensajeRegistro = "Ya existe un usuario con ese email.";
        } else {
            // Inserta nuevo usuario con hash
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            $stmt2 = $connPHP->prepare("
                INSERT INTO `cliente` (`Nombre`, `Email`, `Contraseña`)
                VALUES (?, ?, ?)
            ");
            $stmt2->bind_param("sss", $nombre, $email, $hash);
            if ($stmt2->execute()) {
                $mensajeRegistro = "Registro exitoso. Ya puedes iniciar sesión.";
            } else {
                $mensajeRegistro = "Error al registrar. Intenta nuevamente.";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}

$connPHP->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  
  <header class="header">
    <h1 class="titulo">Mi Sitio — Acceso</h1>
    <a href="Index.php"><button type="button" class="boton-header">Volver</button></a>
  </header>

  <div class="container">

    <!-- FORMULARIO DE REGISTRO -->
    <form method="post" action="registro.php">
      <h2>Registro</h2>
      <?php if ($mensajeRegistro): ?>
        <p class="error"><?= htmlspecialchars($mensajeRegistro) ?></p>
      <?php endif; ?>
      <label>
        Nombre:
        <input type="text" name="nombre_reg" required>
      </label>
      <label>
        Email:
        <input type="email" name="email_reg" required>
      </label>
      <label>
        Contraseña:
        <input type="password" name="password_reg" required>
      </label>
      <label>
        Repite contraseña:
        <input type="password" name="password2_reg" required>
      </label>
      <input type="hidden" name="action" value="register">
      <div class="button-login">
      <button type="submit">Registrarse</button>
      <a href="login.php"><button type="button">Iniciar Sesion</button></a>
      </div>
    </form>
  </div>
    
</body>
</html>