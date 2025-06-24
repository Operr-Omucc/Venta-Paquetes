<?php
require 'includes/conexBDD.php';
session_start();
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';  // imagen por defecto


// Verificar que el usuario esté logueado
if (!isset($_SESSION['ID_Cliente'])) {
    header("Location: login.php");
    exit;
}

$clienteId = $_SESSION['ID_Cliente'];
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    echo "<h2>Tu carrito está vacío.</h2>";
    echo '<a href="index.php">Volver al inicio</a>';
    exit;
}

// 1. Calcular total
$total = 0;
$detalles = [];

foreach ($carrito as $idProducto => $cantidad) {
    $stmt = $connPHP->prepare("SELECT Precio_Unitario, Nombre FROM Producto WHERE ID_Producto = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $stmt->bind_result($precio, $nombre);
    $stmt->fetch();
    $stmt->close();

    $subtotal = $precio * $cantidad;
    $total += $subtotal;

    $detalles[] = [
        'ID_Producto'    => $idProducto,
        'Cantidad'       => $cantidad,
        'Precio_Unitario'=> $precio,
        'Subtotal'       => $subtotal,
        'Nombre'         => $nombre
    ];
}

// 2. Insertar la venta en la tabla Venta
$ventaStmt = $connPHP->prepare("INSERT INTO Venta (ID_Cliente, Total) VALUES (?, ?)");
$ventaStmt->bind_param("id", $clienteId, $total);
$ventaStmt->execute();
$idVenta = $ventaStmt->insert_id;
$ventaStmt->close();

// 3. Insertar detalles de la venta en Venta_Item
$detalleStmt = $connPHP->prepare("
    INSERT INTO Venta_Item 
    (ID_Venta, ID_Producto, Cantidad, Precio_Unitario, Subtotal)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($detalles as $item) {
    $detalleStmt->bind_param(
        "iiidd",
        $idVenta,
        $item['ID_Producto'],
        $item['Cantidad'],
        $item['Precio_Unitario'],
        $item['Subtotal']
    );
    $detalleStmt->execute();
}
$detalleStmt->close();

// 4. Limpiar el carrito en sesión
unset($_SESSION['carrito']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Compra realizada</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="confirmacion">
  <h2>¡Gracias por tu compra!</h2>
  <p>Tu número de venta es: <strong>#<?= $idVenta ?></strong></p>
  <p>Total pagado: <strong>$<?= number_format($total, 2) ?></strong></p>

  <h3>Detalle de tu compra:</h3>
  <ul class="lista">
    <?php foreach ($detalles as $item): ?>
      <li>
        <?= htmlspecialchars($item['Nombre']) ?> x<?= $item['Cantidad'] ?>
        — $<?= number_format($item['Subtotal'], 2) ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <a href="index.php" class="volver-btn">← Volver al inicio</a>
</div>

</body>
</html>
