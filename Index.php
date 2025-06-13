<?php
include("includes/conexBDD.php");
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
  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
      <img src="https://i.pravatar.cc/40" alt="User Avatar" />
    </div>
   <div class="user-dropdown" id="userDropdown">
     <p>User Name</p>
      <a href="#">Profile</a>
     <a href="#">My Bookings</a>
     <a href="carrito.php">Carrito</a>
      <a href="login.php">Login</a>
    </div>
  </div>
</header>

<main>
  <div class="package-card">
    <div class="package-image">Placeholder Image</div>
    <div class="package-content">
      <h3 class="package-title">Paquete Aventura en la Selva</h3>
      <p class="package-description">Disfruta de una expedición única explorando la selva tropical con guías expertos y actividades emocionantes.</p>
      <button class="package-button">Ver detalles</button>
    </div>
  </div>

  <div class="package-card">
    <div class="package-image">Placeholder Image</div>
    <div class="package-content">
      <h3 class="package-title">Escapada a la Playa Paradisiaca</h3>
      <p class="package-description">Relájate en las playas más hermosas con todo incluido y actividades acuáticas para toda la familia.</p>
      <button class="package-button">Ver detalles</button>
    </div>
  </div>

  <div class="package-card">
    <div class="package-image">Placeholder Image</div>
    <div class="package-content">
      <h3 class="package-title">Tour Cultural por Europa</h3>
      <p class="package-description">Visita las ciudades más emblemáticas de Europa y sumérgete en su historia, arte y gastronomía.</p>
      <button class="package-button">Ver detalles</button>
    </div>
  </div>

  <div class="package-card">
    <div class="package-image">Placeholder Image</div>
    <div class="package-content">
      <h3 class="package-title">Aventura en la Montaña</h3>
      <p class="package-description">Escala picos, haz senderismo y disfruta de la naturaleza en su máximo esplendor.</p>
      <button class="package-button">Ver detalles</button>
    </div>
  </div>
</main>

<script>
  const userMenu = document.getElementById('userMenu');
  const userDropdown = document.getElementById('userDropdown');

  userMenu.addEventListener('click', () => {
    userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (e) => {
    if (!userMenu.contains(e.target)) {
      userDropdown.style.display = 'none';
    }
  });
</script>

</body>
</html>
