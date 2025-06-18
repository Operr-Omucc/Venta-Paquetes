<?php
// index.php
session_start();
include("includes/conexBDD.php");

// 1) Datos de sesión
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Paquetes de Viajes</title>
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

  <?php if (isset($_SESSION['Jefe_de_Ventas']) && (int)$_SESSION['Jefe_de_Ventas'] === 1): ?>
    <a href="admin.php">Dashboard</a>
  <?php endif; ?>

  <?php if ($isLoggedIn): ?>
    <a href="logout.php">Cerrar sesión</a>
  <?php else: ?>
    <a href="login.php">Iniciar sesión</a>
  <?php endif; ?>
</div>
  </div>
</header>

<main class="main-index">
  <?php
  // 2) Traer todos los paquetes (productos)
  $sql    = "SELECT ID_Producto, Nombre, Descripcion, Precio_Unitario FROM Producto";
  $result = $connPHP->query($sql);

  if ($result && $result->num_rows > 0): 
    while ($row = $result->fetch_assoc()):
  ?>
      <div class="package-card">
        <div class="package-image">
          <img
            src="https://floresevt.tur.ar/wp-content/uploads/2025/01/Europa-Clasica-foto-uno.jpg"
            alt="<?= htmlspecialchars($row['Nombre']) ?>"
            style="width:100%; height:160px; object-fit:cover;"
          >
        </div>
        <div class="package-content">
          <h3 class="package-title"><?= htmlspecialchars($row['Nombre']) ?></h3>
          <p class="package-description">
            <?= nl2br(htmlspecialchars(mb_strimwidth($row['Descripcion'], 0, 100, '…'))) ?>
          </p>
          <p><strong>Precio:</strong> $<?= number_format($row['Precio_Unitario'], 2) ?></p>
          <a
            href="detalle_paquete.php?id=<?= $row['ID_Producto'] ?>"
            class="package-button"
          >Ver detalles</a>
        </div>
      </div>
  <?php
    endwhile;
  else:
  ?>
    <p>No hay paquetes disponibles en este momento.</p>
  <?php endif; ?>
</main>

<script>
  const userMenu     = document.getElementById('userMenu'),
        userDropdown = document.getElementById('userDropdown');

  userMenu.addEventListener('click', () => {
    userDropdown.style.display =
      userDropdown.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (e) => {
    if (!userMenu.contains(e.target)) {
      userDropdown.style.display = 'none';
    }
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