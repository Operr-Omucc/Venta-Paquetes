<?php
session_start();
include("includes/conexBDD.php");

$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin = ($rolUsuario === 'admin' || $rolUsuario === 'jefe');

// Capturar país desde GET (para el filtro)
$paisSeleccionado = $_GET['pais'] ?? '';
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

      <?php if ($esAdmin): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="admin_nuevo_paquete.php">Añadir paquete</a>
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
<!-- Formulario de filtrado integrado como tarjeta -->
  <div class="package-card filtro-card">
    <form action="index.php" method="GET">
      <label for="pais">Filtrar por país:</label>
      <select name="pais" id="pais">
        <option value="">Todos</option>
        <option value="Argentina">Argentina</option>
        <option value="Brasil">Brasil</option>
        <option value="Perú">Perú</option>
        <option value="Chile">Chile</option>
        <option value="México">México</option>
        <!-- Agregá más países si querés -->
      </select>
      <button type="submit" class="package-button">Buscar</button>
    </form>
  </div>

  <?php
  // Consulta filtrada por país
  if (!empty($paisSeleccionado)) {
    $stmt = $connPHP->prepare("SELECT ID_Producto, Nombre, Descripcion, Precio_Unitario, Imagen_URL FROM Producto WHERE Pais = ?");
    $stmt->bind_param("s", $paisSeleccionado);
    $stmt->execute();
    $result = $stmt->get_result();
  } else {
    $result = $connPHP->query("SELECT ID_Producto, Nombre, Descripcion, Precio_Unitario, Imagen_URL FROM Producto");
  }

  if ($result && $result->num_rows > 0): 
    while ($row = $result->fetch_assoc()):
  ?>
      <div class="package-card">
        <div class="package-image">
          <img
            src="<?= htmlspecialchars($row['Imagen_URL']) ?>"
            alt="<?= htmlspecialchars($row['Nombre']) ?>"
            style="width:100%; height:22vh; object-fit:cover;"
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
    if (!empty($stmt)) $stmt->close();
  else
  ?>
    <p>No mas hay paquetes disponibles en este momento.</p>
  <?php endif; ?>
</main>

<script>
  const userMenu = document.getElementById('userMenu'),
        userDropdown = document.getElementById('userDropdown');

  userMenu.addEventListener('click', () => {
    userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (e) => {
    if (!userMenu.contains(e.target)) {
      userDropdown.style.display = 'none';
    }
  });
</script>

<footer class="footer-simple">
  <div class="footer-links">
    <a href="perfil.php">Ver Perfil</a>
    <a href="ver_mis_reservas.php">Mis Reservas</a>
    <a href="carrito.php">Ver Carrito</a>
    <a href="soporte.php">Soporte</a>
  </div>
</footer>

</body>
</html>
