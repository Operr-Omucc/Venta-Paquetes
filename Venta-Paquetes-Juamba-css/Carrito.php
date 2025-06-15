<?php
include("includes/conexBDD.php");
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Carrito de Compras</title>
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
      <a href="#">Logout</a>
    </div>
  </div>
</header>

<main class="main-cart">
  <h1>Tu Carrito</h1>

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
      <tr>
        <td><img src="https://via.placeholder.com/80" alt="Paquete 1" /></td>
        <td>Tour por Europa</td>
        <td>$1200</td>
        <td>1</td>
        <td>$1200</td>
      </tr>
      <tr>
        <td><img src="https://via.placeholder.com/80" alt="Paquete 2" /></td>
        <td>Playa Cancún</td>
        <td>$900</td>
        <td>2</td>
        <td>$1800</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4" class="total">Total general:</td>
        <td>$3000</td>
      </tr>
    </tfoot>
  </table>

  <button class="checkout-btn">Continuar con la compra</button>
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
