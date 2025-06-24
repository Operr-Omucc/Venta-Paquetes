<?php
require 'includes/conexBDD.php';
session_start();

if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Manejar inserción
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $desc = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);

    $stmt = $connPHP->prepare("INSERT INTO Producto (Nombre, Descripcion, Precio_Unitario,) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $nombre, $desc, $precio);
    $stmt->execute();
    $stmt->close();
}

// Manejar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $connPHP->query("DELETE FROM Producto WHERE ID_Producto = $id");
}

// Obtener productos
$productos = $connPHP->query("SELECT * FROM Producto ORDER BY ID_Producto DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Productos</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; }

    form { margin-bottom: 3vh; background: #fff; padding: 2vh; border-radius: 0.8vh; }

    input, textarea { width: 100%; padding: 1vh; margin-bottom: 1vh; }

    button { padding: 1vh 2vh; background: #28a745; color: white; border: none; border-radius: 0.4vh; }

    table { width: 100%; border-collapse: collapse; background: #fff; }

    th, td { padding: 1vh; border: 0.5vh solid #ddd; text-align: left; }

    .eliminar { color: red; text-decoration: none; }

    .ap-centrar{
      margin: 5vh;
    }
  </style>
</head>
<body>

<!-- Sidebar -->

<div class="ap-centrar">

<main>
  <h1>Gestión de Productos</h1>

  <form method="POST">
    <h3>Agregar nuevo producto</h3>
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <textarea name="descripcion" placeholder="Descripción" required></textarea>
    <input type="number" name="precio" step="0.01" placeholder="Precio unitario" required>
    <button type="submit" name="agregar">Agregar producto</button>
  </form>

  <h3>Listado de productos</h3>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $productos->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Producto'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= htmlspecialchars($row['Descripcion']) ?></td>
          <td>$<?= number_format($row['Precio_Unitario'], 2) ?></td>
          <td>
            <a class="eliminar" href="?eliminar=<?= $row['ID_Producto'] ?>" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

    <a href="dashboard.php" class="volver-btn">← Volver </a>

  
</body>
</html>
