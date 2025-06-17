<?php
// admin/usuarios.php
include '../includes/conexBDD.php';

echo '<h2>Gestión de Usuarios Internos</h2>';
$result = $connPHP->query("
  SELECT ID_Cliente, Nombre, Email, Jefe_de_Ventas
  FROM cliente
");
echo '<table><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Admin</th><th>Acciones</th></tr>';
while($u = $result->fetch_assoc()){
  $checked = $u['Jefe_de_Ventas'] ? 'checked' : '';
  echo "<tr>
          <td>{$u['ID_Cliente']}</td>
          <td>{$u['Nombre']}</td>
          <td>{$u['Email']}</td>
          <td><input type=\"checkbox\" disabled $checked></td>
          <td>
            <a href=\"admin.php?seccion=usuarios&toggle={$u['ID_Cliente']}\">Toggle Admin</a>
          </td>
        </tr>";
}
echo '</table>';

// Toggle flag
if(isset($_GET['toggle'])){
  $id = (int)$_GET['toggle'];
  // invertir flag
  $connPHP->query("
    UPDATE cliente
    SET Jefe_de_Ventas = 1 - Jefe_de_Ventas
    WHERE ID_Cliente = $id
  ");
  header('Location: admin.php?seccion=usuarios');
  exit;
}
?>
