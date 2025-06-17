<?php
// carrito.php
require 'includes/conexBDD.php';
session_start();
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';  // imagen por defecto


// 1) Usuario logueado
if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: login.php");
    exit;
}
$clienteId = $_SESSION['ID_Cliente'];

// 2) Leemos el carrito en sesión (ID_Producto => Cantidad)
$sessionCart = $_SESSION['carrito'] ?? [];

// 3) Si envían POST, volcamos a la BD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($sessionCart)) {
    // 3.1) ¿Existe carrito 'Pendiente' para este cliente?
    $stmt = $connPHP->prepare("
      SELECT ID_Carrito 
        FROM Carrito 
       WHERE ID_Cliente = ? 
         AND Estado = 'Pendiente'
    ");
    $stmt->bind_param("i", $clienteId);
    $stmt->execute();
    $stmt->bind_result($idCarrito);
    $found = $stmt->fetch();
    $stmt->close();

    // 3.2) Si no existe, lo creamos
    if (!$found) {
        $ins = $connPHP->prepare("
          INSERT INTO Carrito (Estado, Total_Pagar, ID_Cliente)
          VALUES ('Pendiente', 0, ?)
        ");
        $ins->bind_param("i", $clienteId);
        $ins->execute();
        $idCarrito = $ins->insert_id;
        $ins->close();
    }

    // 3.3) Borramos líneas previas para recalcular
    $del = $connPHP->prepare("
      DELETE FROM Carrito_Item 
       WHERE ID_Carrito = ?
    ");
    $del->bind_param("i", $idCarrito);
    $del->execute();
    $del->close();

    // 3.4) Insertar cada línea y recalcular total
    $total = 0;
    $insItem = $connPHP->prepare("
      INSERT INTO Carrito_Item 
        (ID_Carrito, ID_Producto, Cantidad, Subtotal)
      VALUES (?, ?, ?, ?)
    ");

    foreach ($sessionCart as $prodId => $cant) {
        // obtener precio unitario
        $q = $connPHP->prepare("
          SELECT Precio_Unitario 
            FROM Producto 
           WHERE ID_Producto = ?
        ");
        $q->bind_param("i", $prodId);
        $q->execute();
        $q->bind_result($precio);
        $q->fetch();
        $q->close();

        $subtotal = $precio * $cant;
        $total += $subtotal;

        // insertar línea
        $insItem->bind_param("iiid", $idCarrito, $prodId, $cant, $subtotal);
        $insItem->execute();
    }
    $insItem->close();

    // 3.5) Actualizar el Total_Pagar en la cabecera Carrito
    $upd = $connPHP->prepare("
      UPDATE Carrito 
         SET Total_Pagar = ? 
       WHERE ID_Carrito = ?
    ");
    $upd->bind_param("di", $total, $idCarrito);
    $upd->execute();
    $upd->close();

    $mensaje = "Carrito guardado. Total: $" . number_format($total, 2);
}

// 4) Preparamos datos para mostrar en el HTML
$items = [];
foreach ($sessionCart as $prodId => $cant) {
    $q = $connPHP->prepare("
      SELECT Nombre, Descripcion, Precio_Unitario 
        FROM Producto 
       WHERE ID_Producto = ?
    ");
    $q->bind_param("i", $prodId);
    $q->execute();
    $q->bind_result($nombre, $descr, $precio);
    $q->fetch();
    $q->close();

    $items[] = [
        'Nombre'      => $nombre,
        'Descripcion' => $descr,
        'Precio'      => $precio,
        'Cantidad'    => $cant,
        'Subtotal'    => $precio * $cant
    ];
}

// 5) Datos de sesión para el menú
$userName = htmlspecialchars($_SESSION['Nombre']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrito de Compras</title>
  <link rel="stylesheet" href="style.css">
  <style>
    <head>
  …
  <link rel="stylesheet" href="style.css">
  <style>
    header {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      background-color: #fff;
      padding: 10px 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: relative;
    }
    .user-menu-container {
      position: relative;
      margin-left: auto;
    }
    .user-dropdown {
      position: absolute;
      top: 50px;
      right: 0;
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      width: 180px;
      display: none;
      font-size: 14px;
      color: #555;
      z-index: 1000;
    }
    .user-avatar { cursor: pointer; }
  </style>
</head>

  </style>
</head>
<body>

<header>
  <div class="extra-image">
    <img src="LOGO-removebg-preview.png" alt="Logo PaqueViaje" />
  </div>

  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
      <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar de Usuario" />
    </div>
    <div class="user-dropdown" id="userDropdown">
      <p>¡Bienvenido, <?= $userName ?>!</p>
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

<main class="main-cart">
  <h1>Tu Carrito</h1>

  <?php if (!empty($mensaje)): ?>
    <p class="mensaje-exito"><?= $mensaje ?></p>
  <?php endif; ?>


  <?php if (empty($items)): ?>
    <p>Tu carrito está vacío.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Paquete</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['Nombre']) ?></td>
            <td><?= nl2br(htmlspecialchars($it['Descripcion'])) ?></td>
            <td>$<?= number_format($it['Precio'], 2) ?></td>
            <td><?= $it['Cantidad'] ?></td>
            <td>$<?= number_format($it['Subtotal'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="total">Total general:</td>
          <td>
            $<?= number_format(array_sum(array_column($items, 'Subtotal')), 2) ?>
          </td>
        </tr>
      </tfoot>
    </table>

    <form method="POST">
  <div style="display: flex; gap: 10px; margin-top: 20px;">
    <a href="index.php" class="checkout-btn" style="background:#ccc; color:#222;">← Volver al inicio</a>
    <button type="submit" class="checkout-btn">
    Continuar con la compra
    </button>
  </div>
    </form>
    <form action="confirmar_venta.php" method="POST">
  <button type="submit" class="checkout-btn">Finalizar compra</button>
</form>

  <?php endif; ?>
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
