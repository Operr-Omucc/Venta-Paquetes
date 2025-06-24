<?php
require 'includes/conexBDD.php';
session_start();

if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin    = in_array($rolUsuario, ['admin', 'jefe']);


// Búsqueda
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$sql = "SELECT * FROM Producto WHERE 1";

if ($busqueda !== '') {
    $busquedaSql = "%" . $connPHP->real_escape_string($busqueda) . "%";
    $sql .= " AND (Nombre LIKE '$busquedaSql' OR Descripcion LIKE '$busquedaSql')";
}

$sql .= " ORDER BY ID_Producto DESC";
$productos = $connPHP->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Productos</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 2.61vh;
  background: #f7f7f7;
}

h1 {
  margin-bottom: 2.61vh;
}

form {
  margin-bottom: 2.61vh;
}

input[type="text"] {
  padding: 1.04vh;
  width: 39.17vh;
  border: 0.13vh solid #ccc;
  border-radius: 0.52vh;
}

button {
  padding: 1.04vh 2.09vh;
  background: #007bff;
  border: none;
  color: white;
  border-radius: 0.52vh;
}

.producto-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(39.17vh, 1fr));
  gap: 2.61vh;
}

.producto-card {
  background: #fff;
  border: 0.13vh solid #ddd;
  padding: 1.96vh;
  border-radius: 1.04vh;
  box-shadow: 0 0.13vh 0.52vh rgba(0,0,0,0.1);
}

.producto-card h3 {
  margin-top: 0;
  font-size: 2.35vh;
}

.producto-card p {
  font-size: 1.83vh;
  color: #555;
}

.producto-card .precio {
  font-weight: bold;
  color: #28a745;
}
  </style>
</head>
<body>

<h1>Catálogo de Productos</h1>

<form method="GET">
  <input type="text" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>">
  <button type="submit">Buscar</button>
</form>

<div class="producto-grid">
  <?php if ($productos->num_rows > 0): ?>
    <?php while ($row = $productos->fetch_assoc()): ?>
      <div class="producto-card">
        <h3><?= htmlspecialchars($row['Nombre']) ?></h3>
        <p><?= nl2br(htmlspecialchars(mb_strimwidth($row['Descripcion'], 0, 150, '…'))) ?></p>
        <p class="precio">Precio: $<?= number_format($row['Precio_Unitario'], 2) ?></p>
        <p>ID Producto: <?= $row['ID_Producto'] ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No se encontraron productos.</p>
  <?php endif; ?>
</div>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
