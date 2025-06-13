<?php
include("includes/conexBDD.php");
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Carrito de Compras</title>
  <style>
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

    main {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #f90;
      color: white;
    }

    td img {
      width: 80px;
      border-radius: 6px;
    }

    .total {
      text-align: right;
      font-weight: bold;
      padding: 15px;
      background: #f0f0f0;
    }

    .checkout-btn {
      display: block;
      width: 100%;
      max-width: 300px;
      margin: 20px auto;
      padding: 12px;
      font-size: 16px;
      background-color: #f90;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .checkout-btn:hover {
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
