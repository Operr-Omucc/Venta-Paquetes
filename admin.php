<?php
// File: admin.php
session_start();

// Acceso restringido: solo jefes de ventas (flag = 1)
if (!isset($_SESSION['ID_Cliente']) || ($_SESSION['Jefe_de_Ventas'] ?? 0) != 1) {
    header('Location: index.php');
    exit;
}

include 'includes/conexBDD.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin - PaqueViaje</title>
  <!-- Incluye tu CSS principal y el adicional para admin -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="admin-dashboard">
  <aside class="admin-sidebar">
    <ul>
      <li><a href="admin.php?seccion=productos">Productos</a></li>
      <li><a href="admin.php?seccion=pedidos">Pedidos</a></li>
      <li><a href="admin.php?seccion=cobros">Cobros/Facturas</a></li>
      <li><a href="admin.php?seccion=historico">Histórico</a></li>
      <li><a href="admin.php?seccion=usuarios">Usuarios</a></li>
    </ul>
  </aside>

  <main class="admin-content">
    <?php
      // Determinamos la sección a mostrar
      $sec = $_GET['seccion'] ?? 'productos';
      $ruta = __DIR__ . "/admin/{$sec}.php";
      if (file_exists($ruta)) {
        include $ruta;
      } else {
        echo '<h2>Sección no encontrada</h2>';
      }
    ?>
  </main>
</div>

</body>
</html>
