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
  <style>
    .detail-container { max-width:800px; margin:40px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .detail-info h1 { margin:0 0 10px; }
    .detail-info p { line-height:1.5; color:#555; }
    .detail-info .price { font-size:24px; font-weight:bold; margin:15px 0; }
    .detail-form { margin-top:20px; }
    .detail-form input[type=number] { width:60px; padding:5px; }
    .detail-form button { margin-left:10px; }
    .mensaje { margin:15px 0; color:green; }
  </style>
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

<main>
  <div class="detail-container">
    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="detail-info">
      <h1><?= htmlspecialchars($prod['Nombre']) ?></h1>
      <p><?= nl2br(htmlspecialchars($prod['Descripcion'])) ?></p>
      <div class="price">$<?= number_format($prod['Precio_Unitario'], 2) ?></div>

      <form method="POST" class="detail-form">
        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" value="1" min="1">
        <button type="submit" class="package-button">Agregar al carrito</button>
      </form>
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

</body>
</html>
