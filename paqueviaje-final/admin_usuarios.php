<?php
require 'includes/conexBDD.php';
session_start();

// --- Autenticación y autorización ---
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}

// --- Datos de usuario para menú ---
$isLoggedIn = isset($_SESSION['ID_Cliente']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['Nombre']) : 'Invitado';
$userAvatar = (isset($_SESSION['Foto_Perfil']) && !empty($_SESSION['Foto_Perfil']))
    ? $_SESSION['Foto_Perfil']
    : 'https://i.pravatar.cc/40';
$rolUsuario = $_SESSION['Rol'] ?? 'cliente';
$esAdmin = in_array($rolUsuario, ['admin', 'jefe']);

// --- Cambiar rol o estado ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente'])) {
    $id = intval($_POST['id_cliente']);
    $nuevoRol = $_POST['nuevo_rol'] ?? null;
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($nuevoRol) {
        $stmt = $connPHP->prepare("UPDATE Cliente SET Rol = ?, Activo = ? WHERE ID_Cliente = ?");
        $stmt->bind_param("sii", $nuevoRol, $activo, $id);
        $stmt->execute();
        $stmt->close();
        $mensaje = "Usuario #$id actualizado.";
    }
}

// --- Obtener usuarios internos ---
$usuarios = $connPHP->query("
    SELECT ID_Cliente, Nombre, Email, Rol, Activo 
    FROM Cliente 
    WHERE Rol IN ('admin', 'jefe', 'cliente') 
    ORDER BY Rol DESC, Nombre ASC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administración de Usuarios</title>
  <link rel="stylesheet" href="style.css">
  <style>body {
  font-family: Arial, sans-serif;
  padding: 3.92vh;
  background: #f3f3f3;
}

h1 {
  margin-bottom: 2.61vh;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

th, td {
  padding: 1.31vh;
  border: 0.13vh solid #ccc;
}

th {
  background: #eee;
}

form {
  margin: 0;
  display: flex;
  gap: 1.31vh;
  align-items: center;
}

select, input[type="checkbox"] {
  padding: 0.65vh;
}

button {
  padding: 0.78vh 1.31vh;
  background: #28a745;
  color: white;
  border: none;
  border-radius: 0.52vh;
  cursor: pointer;
}

.mensaje {
  margin: 1.31vh 0;
  color: green;
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

<!-- HEADER CON MENÚ -->
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
  <h1>Administración de Usuarios</h1>

  <?php if (!empty($mensaje)): ?>
    <p class="mensaje"><?= $mensaje ?></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Activo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($u = $usuarios->fetch_assoc()): ?>
        <tr>
          <td><?= $u['ID_Cliente'] ?></td>
          <td><?= htmlspecialchars($u['Nombre']) ?></td>
          <td><?= htmlspecialchars($u['Email']) ?></td>
          <td><?= $u['Rol'] ?></td>
          <td><?= $u['Activo'] ? 'Sí' : 'No' ?></td>
          <td>
            <form method="POST">
              <input type="hidden" name="id_cliente" value="<?= $u['ID_Cliente'] ?>">
              <select name="nuevo_rol">
                <option value="cliente" <?= $u['Rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                <option value="jefe" <?= $u['Rol'] === 'jefe' ? 'selected' : '' ?>>Jefe</option>
                <option value="admin" <?= $u['Rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
              </select>
              <label>
                <input type="checkbox" name="activo" <?= $u['Activo'] ? 'checked' : '' ?>> Activo
              </label>
              <button type="submit">Guardar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<script>
  const userMenu = document.getElementById('userMenu'),
        userDropdown = document.getElementById('userDropdown');
  userMenu.addEventListener('click', () => {
    userDropdown.style.display = (userDropdown.style.display === 'block') ? 'none' : 'block';
  });
  document.addEventListener('click', e => {
    if (!userMenu.contains(e.target)) {
      userDropdown.style.display = 'none';
    }
  });
</script>

    <a href="dashboard.php" class="volver-btn">← Volver </a>
</body>
</html>
