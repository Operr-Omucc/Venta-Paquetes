<?php
require 'includes/conexBDD.php';
session_start();
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';  // imagen por defecto


if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: login.php");
    exit;
}

$clienteId = $_SESSION['ID_Cliente'];
$userName = htmlspecialchars($_SESSION['Nombre']);

// Traer todas las ventas del usuario
$stmt = $connPHP->prepare("
  SELECT V.ID_Venta, V.Fecha, V.Total
    FROM Venta V
   WHERE V.ID_Cliente = ?
   ORDER BY V.Fecha DESC
");
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$ventas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Reservas</title>
  <link rel="stylesheet" href="style.css">
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

<main class="reservas-container">
  <h2>Mis Reservas</h2>

  <?php if ($ventas->num_rows === 0): ?>
    <p>No tenés reservas registradas.</p>
  <?php else: ?>
    <?php while ($venta = $ventas->fetch_assoc()): ?>
      <div class="venta">
        <h3>Venta #<?= $venta['ID_Venta'] ?> — <?= date('d/m/Y H:i', strtotime($venta['Fecha'])) ?></h3>
        <p><strong>Total pagado:</strong> $<?= number_format($venta['Total'], 2) ?></p>

        <?php
        // Traer los productos asociados a esta venta
        $stmt2 = $connPHP->prepare("
          SELECT P.Nombre, VI.Cantidad, VI.Subtotal
            FROM Venta_Item VI
            JOIN Producto P ON P.ID_Producto = VI.ID_Producto
           WHERE VI.ID_Venta = ?
        ");
        $stmt2->bind_param("i", $venta['ID_Venta']);
        $stmt2->execute();
        $productos = $stmt2->get_result();
        ?>

        <table>
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($p = $productos->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($p['Nombre']) ?></td>
                <td><?= $p['Cantidad'] ?></td>
                <td>$<?= number_format($p['Subtotal'], 2) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <?php $stmt2->close(); ?>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

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
