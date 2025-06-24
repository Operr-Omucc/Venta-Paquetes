<?php
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soporte - PaqueViaje</title>
  <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body>

<header>
  <div class="extra-image">
    <img src="LOGO-removebg-preview.png" alt="Logo PaqueViaje" />
  </div>

  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
      <img src="<?= $userAvatar ?>" alt="Avatar de Usuario" />
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

<div class="centrar-soporte">
  <main class="soporte-container">
    <section class="soporte-sobre-nosotros" id="sobre-nosotros">
      <h2>Sobre nosotros</h2>
      <p>PaqueViaje nació con la misión de ofrecer viajes organizados, accesibles y sin complicaciones. Con paquetes armados previamente y atención personalizada, buscamos que cada viajero disfrute sin estrés ni sobrecostos. Confianza, claridad y comodidad son nuestros pilares.</p>
    </section>

    <section class="soporte-faq" id="faq">
      <h2>Preguntas frecuentes</h2>
      <ul>
        <li><strong>¿Los paquetes incluyen vuelos?</strong> Sí, todos los paquetes incluyen vuelos ida y vuelta.</li>
        <li><strong>¿Puedo pagar en cuotas?</strong> Aceptamos todos los medios de pago, incluidos planes en cuotas con tarjeta.</li>
        <li><strong>¿Puedo cambiar la fecha del viaje?</strong> Los cambios están sujetos a condiciones de cada paquete y disponibilidad. Consultanos antes de realizar el pago.</li>
        <li><strong>¿Qué documentación necesito?</strong> Para viajes internacionales, necesitás pasaporte vigente. Algunos destinos pueden requerir visa.</li>
      </ul>
    </section>

    <section class="soporte-contacto" id="contacto">
      <h2>Contacto</h2>
      <p>Podés enviarnos tu consulta completando el formulario de contacto en la página principal o escribiendo a <a href="mailto:info@paqueviaje.com">info@paqueviaje.com</a>.</p>
    </section>
  </main>
</div>

<footer class="footer-simple">
  <div class="footer-links">
    <a href="perfil.php">Ver Perfil</a>
    <a href="ver_mis_reservas.php">Mis Reservas</a>
    <a href="carrito.php">Ver Carrito</a>
    <a href="soporte.php" class="soporte-titulo">Soporte</a>
  </div>
</footer>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const userMenu = document.getElementById('userMenu');
    const userDropdown = document.getElementById('userDropdown');

    userMenu.addEventListener('click', function () {
      userDropdown.style.display = (userDropdown.style.display === 'block') ? 'none' : 'block';
    });

    document.addEventListener('click', function (e) {
      if (!userMenu.contains(e.target)) {
        userDropdown.style.display = 'none';
      }
    });
  });
</script>

</body>
</html>