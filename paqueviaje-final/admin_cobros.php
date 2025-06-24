<?php
require 'includes/conexBDD.php';
session_start();

// --- Autenticación ---
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// --- Datos del usuario para el menú ---
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (!empty($_SESSION['Foto_Perfil'])) ? $_SESSION['Foto_Perfil'] : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin = in_array($rolUsuario, ['admin', 'jefe']);

// --- Registrar pago ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_pago'])) {
    $idCliente = intval($_POST['id_cliente']);
    $monto = floatval($_POST['monto']);
    $fecha = date('Y-m-d H:i:s');

    if ($idCliente > 0 && $monto > 0) {
        $stmt = $connPHP->prepare("INSERT INTO Pago (ID_Cliente, Monto, Fecha) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $idCliente, $monto, $fecha);
        $stmt->execute();
        $stmt->close();
        $mensaje = "Pago registrado correctamente.";
    } else {
        $error = "Debe seleccionar un cliente y un monto válido.";
    }
}

// --- Obtener clientes y pagos ---
$clientes = $connPHP->query("SELECT ID_Cliente, Nombre FROM Cliente ORDER BY Nombre ASC");
$pagos = $connPHP->query("
    SELECT P.ID_Pago, C.Nombre, P.Monto, P.Fecha
    FROM Pago P
    JOIN Cliente C ON C.ID_Cliente = P.ID_Cliente
    ORDER BY P.Fecha DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Cobros</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 3vh;
  background: #f8f8f8;
}

h1, h2 {
  margin-bottom: 2vh;
}

h2 {
  width: 33vh;
}

form {
  background: #fff;
  padding: 2vh;
  border-radius: 0.8vh;
  margin-bottom: 3vh;
  max-width: 80vh;
}

select, input {
  width: 100%;
  padding: 1vh;
  margin: 1vh 0;
}

button {
  background: #28a745;
  color: white;
  padding: 1vh 2vh;
  border: none;
  border-radius: 0.6vh;
}

table {
  width: 100%;
  background: white;
  border-collapse: collapse;
}

th, td {
  padding: 1vh;
  border: 0.13vh solid #ddd;
}

.mensaje {
  color: green;
  font-weight: bold;
}

.error {
  color: red;
  font-weight: bold;
}

header {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  background-color: #fff;
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
  display: none;
  position: absolute;
  top: 6.53vh;
  right: 0;
  background: white;
  border: 0.13vh solid #ccc;
  border-radius: 0.78vh;
  box-shadow: 0 0.26vh 1.31vh rgba(0,0,0,0.1);
  width: 23.5vh;
  z-index: 1000;
  padding: 1.31vh;
}

.user-dropdown a {
  display: block;
  padding: 1.04vh;
  color: #333;
  text-decoration: none;
}

.user-dropdown a:hover {
  background: #f0f0f0;
}
  </style>
</head>
<body>

<!-- HEADER -->
<header>
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
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>
</header>

<main>
  <h1>Registrar un nuevo cobro</h1>

  <?php if (!empty($mensaje)): ?>
    <p class="mensaje"><?= $mensaje ?></p>
  <?php elseif (!empty($error)): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Cliente:</label>
    <select name="id_cliente" required>
      <option value="">-- Seleccionar cliente --</option>
      <?php while ($c = $clientes->fetch_assoc()): ?>
        <option value="<?= $c['ID_Cliente'] ?>"><?= htmlspecialchars($c['Nombre']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Monto ($):</label>
    <input type="number" name="monto" min="0.01" step="0.01" required>

    <button type="submit" name="registrar_pago">Registrar Pago</button>
  </form>

  <h2>Historial de Pagos</h2>

  <?php if ($pagos->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID Pago</th>
          <th>Cliente</th>
          <th>Monto</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = $pagos->fetch_assoc()): ?>
          <tr>
            <td><?= $p['ID_Pago'] ?></td>
            <td><?= htmlspecialchars($p['Nombre']) ?></td>
            <td>$<?= number_format($p['Monto'], 2) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($p['Fecha'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay pagos registrados aún.</p>
  <?php endif; ?>
</main>

<script>
  const userMenu = document.getElementById('userMenu'),
        userDropdown = document.getElementById('userDropdown');

  userMenu.addEventListener('click', () => {
    userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
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
