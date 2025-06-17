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

// DEBUG TEMPORAL - Mostramos la sesión (puedes eliminar esto después de probar)
echo "<pre style='color:white;background:black;padding:1em;'>SESSION: ";
print_r($_SESSION);
echo "</pre>";
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

      <?php if (!empty($_SESSION['Jefe_de_Ventas']) && $_SESSION['Jefe_de_Ventas'] == 1): ?>
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
  $sql    = "SELECT ID_Producto, Nombre, Descripcion, Precio_Unitario FROM Producto";
  $result = $connPHP->query($sql);

  if ($result && $result->num_rows > 0): 
    while ($row = $result->fetch_assoc()):
  ?>
      <div class="package-card">
        <div class="package-image">
          <img
            src="https://via.placeholder.com/260x160"
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
<div class="border-bottom-footer">

<div class="tcm-box">
  <ul>
  <li><h4>Paqueviaje en el mundo</h4></li>
  <li> <img src="https://static.vecteezy.com/system/resources/previews/011/571/440/non_2x/circle-flag-of-bolivia-free-png.png" alt=""> <p>Vuelos baratos desde Bolivia</p> </li>
  <li> <img src="https://static.vecteezy.com/system/resources/previews/011/571/494/non_2x/circle-flag-of-argentina-free-png.png" alt=""> <p>Vuelos baratos desde Argentina</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde México</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Chile</p> </li>
  </ul>

  <ul>
    <li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Colombia</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Perú</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Brasil</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Uruguay</p> </li>
  </ul>

  <ul>
    <li></li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Panamá</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Costa Rica</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde República Dominicana</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Francia</p> </li>
  </ul>
  
  <ul>
    <li></li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Ecuador</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Paraguay</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde España</p> </li>
  <li> <img src="" alt=""> <p>Vuelos baratos desde Estados Unidos</p> </li>
  </ul>

  
  <ul>
    <li><h4>Soporte</h4></li>
    <li><p>Sobre nosotros</p></li>
    <li><p>Contacto</p></li>
    <li><p>Preguntas frecuentes</p></li>
  </ul>
</div>

</div>
</footer>

</body>
</html>
