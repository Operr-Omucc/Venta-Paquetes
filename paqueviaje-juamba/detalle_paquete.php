<?php
// detalle_paquete.php
include("includes/conexBDD.php");
session_start();
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';  // imagen por defecto


// 1) Validar y capturar el ID de producto desde GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$productoId = intval($_GET['id']);

// 2) Procesar “Agregar al carrito”
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidad'])) {
    $cantidad = max(1, intval($_POST['cantidad']));
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    if (isset($_SESSION['carrito'][$productoId])) {
        $_SESSION['carrito'][$productoId] += $cantidad;
    } else {
        $_SESSION['carrito'][$productoId] = $cantidad;
    }
    $mensaje = "Se agregaron $cantidad unidad(es) al carrito.";
}

// 3) Obtener datos del producto desde la tabla `Producto`
$stmt = $connPHP->prepare("
    SELECT 
      ID_Producto, 
      Nombre, 
      Descripcion, 
      Precio_Unitario 
    FROM Producto 
    WHERE ID_Producto = ?
");
$stmt->bind_param("i", $productoId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header("Location: index.php");
    exit;
}
$prod = $result->fetch_assoc();
$stmt->close();

// 4) Datos de usuario para el menú
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detalle: <?= htmlspecialchars($prod['Nombre']) ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="extra-image"><img src="LOGO-removebg-preview.png" alt="Logo"></div>
  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
        <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar de Usuario">
    </div>

    <div class="user-dropdown" id="userDropdown">
      <p><?= $userName ?></p>
      <a href="perfil.php">Perfil</a>
      <a href="ver_mis_reservas.php">Mis Reservas</a>
      <a href="carrito.php">Carrito</a>
      <?php if ($isLoggedIn): ?>
        <a href="logout.php">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<main class="detail-main">
  <div class="detail-container">
    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="detail-info">
      <h1><?= htmlspecialchars($prod['Nombre']) ?></h1>
      <img src="https://floresevt.tur.ar/wp-content/uploads/2025/01/Europa-Clasica-foto-uno.jpg" alt="">
      <p><?= nl2br(htmlspecialchars($prod['Descripcion'])) ?></p>
      <div class="price">$<?= number_format($prod['Precio_Unitario'], 2) ?></div>

    <div class="nivelar-dp">
      
    <div >
      <a href="index.php" class="checkout-btn" style="background:#ccc; color:#222;">← Volver al inicio</a>
    </div>

      <form method="POST" class="detail-form">
        <label for="cantidad">Cantidad:</label>
        <div class="cant-pers-cont">
        <input type="number" id="cantidad" name="cantidad" value="1" min="1">
        <button type="submit" class="package-button">Agregar al carrito</button>
        </div>
      </form>

    </div>

    </div>
  </div>
</main>

<script>
  const um = document.getElementById('userMenu'),
        ud = document.getElementById('userDropdown');
  um.addEventListener('click', ()=> ud.style.display = ud.style.display==='block'?'none':'block');
  document.addEventListener('click', e=>{
    if (!um.contains(e.target)) ud.style.display='none';
  });
</script>


<footer>

  <ul>
  <li> <a href="perfil.php"><h4>Ver Perfil</h4></a> </li>
  </ul>

  <ul>
  <li> <a href="ver_mis_reservas.php"><h4>Mis Reservas</h4></a> </li>
  </ul>

  <ul>
  <li> <a href="carrito.php"><h4>Ver Carrito</h4></a> </li>
  </ul>

  
  <ul>
    <li> <a href=""><h4>Ir a Soporte</h4></a> </li>
    <li> <a href=""><p>Sobre nosotros</p></a> </li>
    <li> <a href=""><p>Contacto</p></a> </li>
    <li> <a href=""><p>Preguntas frecuentes</p></a> </li>
  </ul>

</footer>

</body>
</html>
