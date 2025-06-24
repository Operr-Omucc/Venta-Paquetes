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
</head>
<body>

<header>
  <div class="extra-image">
    <img src="LOGO-removebg-preview.png" alt="Logo PaqueViaje" />
  </div>
  
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

<div class="contenedor-perfil">
  <div class="nivelar-perfil">
    <a href="index.php" class="volver-btn">← Volver al inicio</a>
    <h2>Mi Perfil</h2>
  </div>

  <?php if ($mensaje): ?>
    <p style="color: green;"><?= $mensaje ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="formulario-usuario">
    <label for="nombre" class="label-form">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" required>

    <label for="email" class="label-form">Email:</label>
    <input type="email" id="email" value="<?= $email ?>" disabled>

    <label for="foto" class="label-form">Cambiar foto de perfil:</label>
    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto actual">
    <input class="import-user-img" type="file" name="foto" accept="image/*">

    <button type="submit" class="guardar">Guardar cambios</button>
  </form>
</div>


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
