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
  <div class="extra-image">
    <img src="LOGO-removebg-preview.png" alt="" />
  </div>

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

<main class="main-index">
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
