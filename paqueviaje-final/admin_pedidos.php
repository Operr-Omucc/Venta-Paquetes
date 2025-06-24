<?php
require 'includes/conexBDD.php';
session_start();

// Verificar acceso de administrador o jefe
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Cambiar estado si se solicitó
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $nuevoEstado = $_POST['nuevo_estado'];
    $idPedido = intval($_POST['id_pedido']);

    $stmt = $connPHP->prepare("UPDATE Pedido SET Estado = ? WHERE ID_Pedido = ?");
    $stmt->bind_param("si", $nuevoEstado, $idPedido);
    $stmt->execute();
    $stmt->close();

    $mensaje = "Estado del pedido #$idPedido actualizado a '$nuevoEstado'.";
}

// Obtener pedidos pendientes o en reparto
$pedidos = $connPHP->query("
    SELECT P.ID_Pedido, P.Estado, P.Fecha_Creacion, C.Nombre
    FROM Pedido P
    JOIN Cliente C ON C.ID_Cliente = P.ID_Cliente
    WHERE P.Estado IN ('Pendiente', 'En reparto')
    ORDER BY P.Fecha_Creacion DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pedidos Pendientes</title>
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
  border: 0.13vh solid #ddd;
  text-align: left;
}

th {
  background: #f0f0f0;
}

form {
  margin: 0;
  display: inline;
}

button {
  padding: 0.78vh 1.31vh;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 0.52vh;
}

.mensaje {
  color: green;
  margin-bottom: 1.96vh;
  font-weight: bold;
}
  </style>
</head>
<body>

<h1>Pedidos Pendientes y En Reparto</h1>

<?php if (!empty($mensaje)): ?>
  <p class="mensaje"><?= $mensaje ?></p>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Estado actual</th>
      <th>Cambiar estado</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($pedidos && $pedidos->num_rows > 0): ?>
      <?php while ($row = $pedidos->fetch_assoc()): ?>
        <tr>
          <td><?= $row['ID_Pedido'] ?></td>
          <td><?= htmlspecialchars($row['Nombre']) ?></td>
          <td><?= date('d/m/Y', strtotime($row['Fecha_Creacion'])) ?></td>
          <td><?= $row['Estado'] ?></td>
          <td>
            <form method="POST">
              <input type="hidden" name="id_pedido" value="<?= $row['ID_Pedido'] ?>">
              <select name="nuevo_estado">
                <option value="Pendiente" <?= $row['Estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="En reparto" <?= $row['Estado'] == 'En reparto' ? 'selected' : '' ?>>En reparto</option>
                <option value="Entregado">Entregado</option>
              </select>
              <button type="submit" name="cambiar_estado">Actualizar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">No hay pedidos pendientes o en reparto.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
