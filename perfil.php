<?php
require 'includes/conexBDD.php';
session_start();

if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: login.php");
    exit;
}

$idCliente = $_SESSION['ID_Cliente'];
$mensaje = "";

// Procesar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = trim($_POST['nombre']);
    $fotoNombre = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoNombre = 'uploads/perfil_' . $idCliente . '_' . time() . '.jpg';
        move_uploaded_file($fotoTmp, $fotoNombre);
    }

    if ($fotoNombre) {
        $stmt = $connPHP->prepare("UPDATE Cliente SET Nombre = ?, Foto_Perfil = ? WHERE ID_Cliente = ?");
        $stmt->bind_param("ssi", $nuevoNombre, $fotoNombre, $idCliente);
        $_SESSION['Foto_Perfil'] = $fotoNombre; // <- ACTUALIZA LA SESIÓN
    } else {
        $stmt = $connPHP->prepare("UPDATE Cliente SET Nombre = ? WHERE ID_Cliente = ?");
        $stmt->bind_param("si", $nuevoNombre, $idCliente);
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['Nombre'] = $nuevoNombre;
    $mensaje = "Perfil actualizado correctamente.";
}

// Obtener datos actuales
$stmt = $connPHP->prepare("SELECT Nombre, Email, Foto_Perfil FROM Cliente WHERE ID_Cliente = ?");
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$stmt->bind_result($nombre, $email, $fotoPerfil);
$stmt->fetch();
$stmt->close();

$nombre = htmlspecialchars($nombre);
$email = htmlspecialchars($email);
$fotoPerfil = $fotoPerfil ?: "https://i.pravatar.cc/100";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .perfil-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
    }
    .perfil-container img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }
    .perfil-container label {
      font-weight: bold;
    }
    .perfil-container input {
      width: 100%;
      margin-bottom: 15px;
      padding: 8px;
    }
    .perfil-container .guardar {
      background: #f37021;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 6px;
    }
    .perfil-container .volver-btn {
      display: inline-block;
      margin-top: 15px;
      background: #ccc;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 6px;
      color: black;
    }
  </style>
</head>
<body>

<header>
  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar"><img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Avatar"></div>
    <div class="user-dropdown" id="userDropdown">
      <p><?= $nombre ?></p>
      <a href="perfil.php">Perfil</a>
      <a href="ver_mis_reservas.php">Mis Reservas</a>
      <a href="carrito.php">Carrito</a>
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>
</header>

<main class="perfil-container">
  <h2>Mi Perfil</h2>

  <?php if ($mensaje): ?>
    <p style="color: green;"><?= $mensaje ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" value="<?= $email ?>" disabled>

    <label for="foto">Cambiar foto de perfil:</label><br>
    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto actual"><br><br>
    <input type="file" name="foto" accept="image/*">

    <button type="submit" class="guardar">Guardar cambios</button>
  </form>

  <a href="index.php" class="volver-btn">← Volver al inicio</a>
</main>

<script>
  const um = document.getElementById('userMenu'),
        ud = document.getElementById('userDropdown');
  um.addEventListener('click', ()=> ud.style.display = ud.style.display==='block'?'none':'block');
  document.addEventListener('click', e => {
    if (!um.contains(e.target)) ud.style.display = 'none';
  });
</script>

</body>
</html>
