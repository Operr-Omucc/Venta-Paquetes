<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Paquetes de Viajes</title>
  <style>
    /* Reset simple */
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f0f2f5;
      color: #333;
    }
    header {
      background-color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: relative;
    }

    /* Menú de usuario */
    .user-menu-container {
      position: relative;
      cursor: pointer;
    }
    .user-avatar img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #f90;
    }
    .user-dropdown {
      position: absolute;
      top: 50px;
      right: 0;
      background: white;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      width: 180px;
      display: none;
      font-size: 14px;
      color: #555;
      z-index: 1000;
    }
    .user-dropdown p {
      margin: 10px;
      font-weight: bold;
    }
    .user-dropdown a {
      display: block;
      padding: 10px;
      color: #333;
      text-decoration: none;
      border-top: 1px solid #eee;
    }
    .user-dropdown a:hover {
      background: #f90;
      color: white;
    }

    /* Paquetes de viajes */
    main {
      max-width: 1100px;
      margin: 30px auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 20px;
    }
    .package-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 1px 6px rgba(0,0,0,0.1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.2s ease;
    }
    .package-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    .package-image {
      width: 100%;
      height: 160px;
      background: #ddd;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #999;
      font-size: 18px;
      font-weight: bold;
      user-select: none;
    }
    .package-content {
      padding: 15px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .package-title {
      font-size: 18px;
      margin: 0 0 10px;
      color: #222;
    }
    .package-description {
      font-size: 14px;
      flex-grow: 1;
      color: #666;
    }
    .package-button {
      margin-top: 15px;
      background-color: #f90;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 10px;
      font-weight: bold;
      cursor: pointer;
      text-align: center;
    }
    .package-button:hover {
      background-color: #e68300;
    }
  </style>
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
      <a href="#">Logout</a>
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
