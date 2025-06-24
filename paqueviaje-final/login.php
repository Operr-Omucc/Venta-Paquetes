<?php
// login.php
include("includes/conexBDD.php");  // Debe definir $connPHP
session_start();

$mensajeLogin = "";

// --- LÓGICA DE LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $email = trim($_POST['email_login'] ?? '');
    $pass  = $_POST['password_login'] ?? '';

    if ($email === '' || $pass === '') {
        $mensajeLogin = "Completa email y contraseña.";
    } else {
        $stmt = $connPHP->prepare("
            SELECT ID_Cliente, Nombre, Contraseña, Foto_Perfil, Rol
            FROM cliente
            WHERE Email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['Contraseña'])) {
                // Login OK: guardamos TODOS los datos necesarios en la sesión
                $_SESSION['ID_Cliente']  = (int)$row['ID_Cliente'];
                $_SESSION['Nombre']      = $row['Nombre'];
                $_SESSION['Foto_Perfil'] = $row['Foto_Perfil'];
                $_SESSION['Rol']         = $row['Rol'];  // 👈 ESTA LÍNEA ESTÁ CORREGIDA

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

$connPHP->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login & Registro</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
  <h1 class="titulo">Mi Sitio — Acceso</h1>
  <a href="index.php"><button type="button" class="boton-header">Volver</button></a>
</header>

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
    <div class="button-login">
      <button type="submit">Ingresar</button>
      <a href="registro.php"><button type="button">Registrarse</button></a>
    </div>
  </form>
</div>

</body>
</html>
