<?php
session_start();
include("includes/conexBDD.php");

// Verificamos si es admin o jefe
$rol = $_SESSION['Rol'] ?? '';
if (!in_array($rol, ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// Manejar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);

    // Subida de imagen (si existe)
    $rutaImagen = '';
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = basename($_FILES['imagen']['name']);
        $rutaDestino = 'images/paquetes/' . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $rutaImagen = $rutaDestino;
        }
    }

    // Insertar en base de datos
    $stmt = $connPHP->prepare("INSERT INTO Producto (Nombre, Descripcion, Precio_Unitario, Imagen_URL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $rutaImagen);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Paquete</title>
  
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2 class="background-anp">Añadir Nuevo Paquete Turístico</h2>
  <form action="" method="POST" enctype="multipart/form-data" class="contenedor-anp">
    <label class="label-form">Nombre:</label>
    <input type="text" name="nombre" required>

    <label class="label-form">Descripción:</label>
    <textarea name="descripcion" required></textarea>

    <label class="label-form">Precio:</label>
    <input type="number" name="precio" step="0.01" required>

    <label class="label-form">Imagen:</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit" class="volver-btn">Guardar Paquete</button>
  </form>

  <div class="centrar-anp">
    <a href="Index.php" class="volver-btn">← Volver </a>
  </div>
</body>
</html>
