<?php
// login.php
include("includes/conexBDD.php");  // Debe definir $connPHP
session_start();

$mensajeLogin    = "";
$mensajeRegistro = "";

// --- LÓGICA DE LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $email = trim($_POST['email_login'] ?? '');
    $pass  = $_POST['password_login'] ?? '';

    if ($email === '' || $pass === '') {
        $mensajeLogin = "Completa email y contraseña.";
    } else {
        $stmt = $connPHP->prepare("
            SELECT `ID_Cliente`, `Nombre`, `Contraseña`
              FROM `cliente`
             WHERE `Email` = ?
             LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verifica hash
            if (password_verify($pass, $row['Contraseña'])) {
                // Login OK
                $_SESSION['usuario_id']     = $row['ID_Cliente'];
                $_SESSION['usuario_nombre'] = $row['Nombre'];

                // Redirección
                if (!empty($_SESSION['destino'])) {
                    $dest = $_SESSION['destino'];
                    unset($_SESSION['destino']);
                    header("Location: $dest");
                } else {
                    header("Location: index.php");
                }
                $stmt->close();
                $connPHP->close();
                exit;
            }
        }

        $mensajeLogin = "Email o contraseña incorrectos.";
        $stmt->close();
    }
}

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
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login & Registro</title>
  <style>
    .container { display: flex; gap: 2rem; }
    form { border: 1px solid #ccc; padding: 1rem; border-radius: 4px; width: 300px; }
    label { display: block; margin: .5rem 0; }
    .error { color: red; font-size: .9rem; }
  </style>
</head>
<body>
  <h1>Mi Sitio — Acceso</h1>
  <div class="container">
    <!-- FORMULARIO DE LOGIN -->
    <form method="post" action="login.php">
      <h2>Login</h2>
      <?php if ($mensajeLogin): ?>
        <p class="error"><?= htmlspecialchars($mensajeLogin) ?></p>
      <?php endif; ?>
      <label>
        Email:
        <input type="email" name="email_login" required>
      </label>
      <label>
        Contraseña:
        <input type="password" name="password_login" required>
      </label>
      <input type="hidden" name="action" value="login">
      <button type="submit">Entrar</button>
    </form>

    <!-- FORMULARIO DE REGISTRO -->
    <form method="post" action="login.php">
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
      <button type="submit">Registrar</button>
    </form>
  </div>
</body>
</html>
