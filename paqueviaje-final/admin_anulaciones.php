<?php
require 'includes/conexBDD.php';
session_start();

// Verificación de permisos
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Procesar anulación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anular_pedido'])) {
    $idPedido = intval($_POST['id_pedido']);
    $motivo = trim($_POST['motivo']);

    $stmt = $connPHP->prepare("UPDATE Pedido SET Estado = 'Anulado', Motivo = ? WHERE ID_Pedido = ?");
    $stmt->bind_param("si", $motivo, $idPedido);
    $stmt->execute();
    $stmt->close();

    $mensaje = "Pedido #$idPedido fue anulado exitosamente.";
}

// Traer pedidos pendientes
$pendientes = $connPHP->query("
    SELECT P.ID_Pedido, P.Fecha_Creacion, C.Nombre, C.Email
    FROM Pedido P
    JOIN Cliente C ON P.ID_Cliente = C.ID_Cliente
    WHERE P.Estado = 'Pendiente'
    ORDER BY P.Fecha_Creacion ASC
");

// Preparar datos para el menú
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
  <title>Anulación de Pedidos</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: sans-serif;
  padding: 3.92vh;
  background: #f0f0f0;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  margin-top: 2.61vh;
}

th, td {
  padding: 1.31vh;
  border: 0.13vh solid #ccc;
}

th {
  background: #f8f8f8;
}

form {
  margin: 0;
  display: inline-block;
}

.mensaje {
  color: green;
  font-weight: bold;
  margin-bottom: 1.31vh;
}

.btn-anular {
  background: #dc3545;
  color: white;
  padding: 0.78vh 1.57vh;
  border: none;
  border-radius: 0.52vh;
  cursor: pointer;
}

.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2.61vh;
  border-radius: 1.04vh;
  width: 39.17vh;
}

.modal-content textarea {
  width: 100%;
  height: 10.45vh;
  margin-bottom: 1.31vh;
}

/* Menú */
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
    <div class="user-avatar"><img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar"></div>
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

<h1>Anulación de Pedidos</h1>

<?php if (!empty($mensaje)): ?>
  <p class="mensaje"><?= $mensaje ?></p>
<?php endif; ?>

<?php if ($pendientes && $pendientes->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Email</th>
        <th>Fecha de Pedido</th>
        <th>Anular</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $pendientes->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Pedido'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= htmlspecialchars($row['Email']) ?></td>
          <td><?= date('d/m/Y', strtotime($row['Fecha_Creacion'])) ?></td>
          <td>
            <button class="btn-anular" onclick="abrirModal(<?= $row['ID_Pedido'] ?>)">Anular</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No hay pedidos pendientes para anular.</p>
<?php endif; ?>

<!-- Modal -->
<div class="modal" id="modalAnular">
  <div class="modal-content">
    <form method="POST">
      <input type="hidden" name="id_pedido" id="id_pedido">
      <label>Motivo de anulación:</label>
      <textarea name="motivo" required></textarea>
      <button type="submit" name="anular_pedido">Confirmar anulación</button>
      <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
  </div>
</div>

<script>
  function abrirModal(id) {
    document.getElementById('id_pedido').value = id;
    document.getElementById('modalAnular').style.display = 'flex';
  }
  function cerrarModal() {
    document.getElementById('modalAnular').style.display = 'none';
  }

  const menu = document.getElementById('userMenu'),
        drop = document.getElementById('userDropdown');
  menu.addEventListener('click', () => {
    drop.style.display = drop.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', (e) => {
    if (!menu.contains(e.target)) {
      drop.style.display = 'none';
    }
  });
</script>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
