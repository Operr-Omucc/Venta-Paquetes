<?php
require 'includes/conexBDD.php';
session_start();

if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Datos del usuario
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin    = in_array($rolUsuario, ['admin', 'jefe']);

// Filtros
$fechaDesde = $_GET['desde'] ?? '';
$fechaHasta = $_GET['hasta'] ?? '';
$idCliente  = $_GET['cliente'] ?? '';

$where = "P.Estado = 'Entregado'";
$params = [];

if (!empty($fechaDesde)) {
    $where .= " AND P.Fecha_Creacion >= ?";
    $params[] = $fechaDesde . " 00:00:00";
}
if (!empty($fechaHasta)) {
    $where .= " AND P.Fecha_Creacion <= ?";
    $params[] = $fechaHasta . " 23:59:59";
}
if (!empty($idCliente)) {
    $where .= " AND P.ID_Cliente = ?";
    $params[] = $idCliente;
}

$sql = "
SELECT P.ID_Pedido, P.Total_Pagar, P.Fecha_Creacion, C.Nombre
FROM Pedido P
JOIN Cliente C ON C.ID_Cliente = P.ID_Cliente
WHERE $where
ORDER BY P.Fecha_Creacion DESC
";

$stmt = $connPHP->prepare($sql);
if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

// Clientes para el filtro
$clientes = $connPHP->query("SELECT ID_Cliente, Nombre FROM Cliente ORDER BY Nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Ventas</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 3.92vh;
  background: #f7f7f7;
}

h1 {
  margin-bottom: 2.61vh;
}

form {
  margin-bottom: 3.26vh;
  background: #fff;
  padding: 2.61vh;
  border-radius: 1.04vh;
  max-width: 78.34vh;
}

label {
  display: inline-block;
  margin-top: 1.31vh;
}

input, select {
  padding: 1.04vh;
  margin: 0.65vh 0;
  width: 100%;
}

button {
  background: #007bff;
  color: white;
  padding: 1.31vh 2.61vh;
  border: none;
  margin-top: 1.31vh;
  border-radius: 0.65vh;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  margin-top: 2.61vh;
}

th, td {
  padding: 1.31vh;
  border: 0.13vh solid #ccc;
}

th {
  background: #f0f0f0;
}

/* Header y menú */
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
  <h2 style="margin:0;">Historial de Ventas</h2>
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

<form method="GET">
  <label>Desde:</label>
  <input type="date" name="desde" value="<?= htmlspecialchars($fechaDesde) ?>">

  <label>Hasta:</label>
  <input type="date" name="hasta" value="<?= htmlspecialchars($fechaHasta) ?>">

  <label>Cliente:</label>
  <select name="cliente">
    <option value="">-- Todos --</option>
    <?php while ($c = $clientes->fetch_assoc()): ?>
      <option value="<?= $c['ID_Cliente'] ?>" <?= $idCliente == $c['ID_Cliente'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['Nombre']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  <button type="submit">Filtrar</button>
</form>

<?php if ($resultado->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Pedido'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= date('d/m/Y', strtotime($row['Fecha_Creacion'])) ?></td>
          <td>$<?= number_format($row['Total_Pagar'], 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No se encontraron ventas con los criterios seleccionados.</p>
<?php endif; ?>

<script>
  const um = document.getElementById('userMenu'),
        ud = document.getElementById('userDropdown');
  um.addEventListener('click', ()=> ud.style.display = ud.style.display==='block'?'none':'block');
  document.addEventListener('click', e => {
    if (!um.contains(e.target)) ud.style.display = 'none';
  });
</script>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
