<?php
require 'includes/conexBDD.php';
session_start();

// Solo acceso para administrador o jefe
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Procesar entrega
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_entrega'])) {
    $idPedido = intval($_POST['id_pedido']);

    $stmt = $connPHP->prepare("UPDATE Pedido SET Estado = 'Entregado' WHERE ID_Pedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->close();

    $mensaje = "Pedido #$idPedido marcado como entregado.";
}

// Obtener pedidos "En reparto"
$entregas = $connPHP->query("
    SELECT P.ID_Pedido, P.Fecha_Creacion, C.Nombre, C.Email
    FROM Pedido P
    JOIN Cliente C ON C.ID_Cliente = P.ID_Cliente
    WHERE P.Estado = 'En reparto'
    ORDER BY P.Fecha_Creacion DESC
");

// Preparar datos para el menú de usuario
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (!empty($_SESSION['Foto_Perfil'])) ? $_SESSION['Foto_Perfil'] : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin    = in_array($rolUsuario, ['admin', 'jefe']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Entregas</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 3.92vh;
  background: #f9f9f9;
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
  padding: 1.57vh;
  border: 0.13vh solid #ccc;
  text-align: left;
}

th {
  background: #f0f0f0;
}

button {
  padding: 0.78vh 1.57vh;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 0.52vh;
  cursor: pointer;
}

.mensaje {
  margin-bottom: 1.96vh;
  color: green;
  font-weight: bold;
}

/* Menú desplegable */
header {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  background: #fff;
  padding: 1.31vh 2.61vh;
  box-shadow: 0 0.26vh 0.65vh rgba(0,0,0,0.1);
}

.user-menu-container {
  position: relative;
  margin-left: auto;
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
  border: 0.13vh solid #ddd;
  border-radius: 0.78vh;
  box-shadow: 0 0.26vh 1.31vh rgba(0,0,0,0.1);
  padding: 1.31vh;
  width: 23.5vh;
  display: none;
  z-index: 999;
}

.user-dropdown a {
  display: block;
  padding: 0.78vh 1.31vh;
  color: #333;
  text-decoration: none;
}

.user-dropdown a:hover {
  background-color: #f0f0f0;
}
  </style>
</head>
<body>

<header>
  <div class="user-menu-container" id="userMenu">
    <div class="user-avatar">
      <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar">
    </div>
    <div class="user-dropdown" id="userDropdown">
      <p>¡Hola, <?= $userName ?>!</p>
      <a href="perfil.php">Perfil</a>
      <a href="ver_mis_reservas.php">Mis Reservas</a>
      <a href="carrito.php">Carrito</a>
      <?php if ($esAdmin): ?>
        <a href="dashboard.php">Dashboard</a>
      <?php endif; ?>
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>
</header>

<h1>Entregas en curso</h1>

<?php if (!empty($mensaje)): ?>
  <p class="mensaje"><?= $mensaje ?></p>
<?php endif; ?>

<?php if ($entregas && $entregas->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Email</th>
        <th>Fecha Pedido</th>
        <th>Confirmar Entrega</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $entregas->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Pedido'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= htmlspecialchars($row['Email']) ?></td>
          <td><?= date('d/m/Y', strtotime($row['Fecha_Creacion'])) ?></td>
          <td>
            <form method="POST">
              <input type="hidden" name="id_pedido" value="<?= $row['ID_Pedido'] ?>">
              <button type="submit" name="confirmar_entrega">Marcar como Entregado</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No hay pedidos en reparto.</p>
<?php endif; ?>

<script>
  const menu = document.getElementById('userMenu'),
        drop = document.getElementById('userDropdown');
  menu.addEventListener('click', () => {
    drop.style.display = drop.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) drop.style.display = 'none';
  });
</script>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
