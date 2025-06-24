<?php
require 'includes/conexBDD.php';
session_start();

if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Datos del usuario para el menú
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin    = in_array($rolUsuario, ['admin', 'jefe']);

// Consulta para obtener total de compras por cliente
$sql = "
SELECT 
  C.ID_Cliente,
  C.Nombre,
  COUNT(P.ID_Pedido) AS Total_Pedidos,
  COALESCE(SUM(P.Total_Pagar), 0) AS Total_Comprado
FROM Cliente C
LEFT JOIN Pedido P ON C.ID_Cliente = P.ID_Cliente
GROUP BY C.ID_Cliente
ORDER BY Total_Comprado DESC
";

$resultado = $connPHP->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estado de Cuenta de Clientes</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 3.92vh;
  background: #f5f5f5;
}

h1 {
  margin-bottom: 2.61vh;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
}

th, td {
  padding: 1.31vh;
  border: 0.13vh solid #ccc;
}

th {
  background: #f0f0f0;
}

.total-alto {
  color: green;
  font-weight: bold;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  padding: 1.31vh 2.61vh;
  box-shadow: 0 0.26vh 0.65vh rgba(0,0,0,0.1);
}

.user-menu-container {
  position: relative;
}

.user-avatar img {
  width: 5.22vh;
  height: 5.22vh;
  border-radius: 50%;
  cursor: pointer;
}

.user-dropdown {
  position: absolute;
  top: 6.53vh;
  right: 0;
  background: #fff;
  border-radius: 0.78vh;
  box-shadow: 0 0.26vh 1.04vh rgba(0,0,0,0.15);
  width: 23.5vh;
  display: none;
  font-size: 1.83vh;
  color: #555;
  z-index: 1000;
}

.user-dropdown a {
  display: block;
  padding: 1.04vh 1.57vh;
  text-decoration: none;
  color: #333;
}

.user-dropdown a:hover {
  background: #f5f5f5;
}

.user-dropdown p {
  margin: 0;
  padding: 1.31vh;
  font-weight: bold;
}
  </style>
</head>
<body>

<header>
  <h2 style="margin:0;">Estado de Cuenta</h2>
  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
      <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar">
    </div>
    <div class="user-dropdown" id="userDropdown">
      <p>¡Bienvenido, <?= $userName ?>!</p>
      <a href="perfil.php">Perfil</a>
      <a href="ver_mis_reservas.php">Mis Reservas</a>
      <a href="carrito.php">Carrito</a>
      <?php if ($esAdmin): ?>
        <a href="dashboard.php">Dashboard</a>
      <?php endif; ?>
      <a href="index.php">Inicio</a>
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>
</header>

<h1>Estado de Cuenta de Clientes</h1>

<table>
  <thead>
    <tr>
      <th>ID Cliente</th>
      <th>Nombre</th>
      <th>Total Pedidos</th>
      <th>Total Comprado</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Cliente'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= $row['Total_Pedidos'] ?></td>
          <td class="<?= $row['Total_Comprado'] > 10000 ? 'total-alto' : '' ?>">
            $<?= number_format($row['Total_Comprado'], 2) ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4">No hay datos de clientes.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
  const userMenu = document.getElementById('userMenu');
  const userDropdown = document.getElementById('userDropdown');

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

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
