<?php
// admin/productos.php
include '../includes/conexBDD.php';

echo '<h2>Gestión de Productos</h2>';
// Listado
$result = $connPHP->query("SELECT ID_Producto, Nombre, Precio_Unitario FROM Producto");
echo '<table><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Acciones</th></tr>';
while($row = $result->fetch_assoc()){
  echo "<tr>
          <td>{$row['ID_Producto']}</td>
          <td>{$row['Nombre']}</td>
          <td>\${$row['Precio_Unitario']}</td>
          <td>
            <a href=\"admin.php?seccion=productos&editar={$row['ID_Producto']}\">Editar</a> |
            <a href=\"admin.php?seccion=productos&borrar={$row['ID_Producto']}\">Borrar</a>
          </td>
        </tr>";
}
echo '</table>';

// Formulario de alta/edición (simplificado)
if(isset($_GET['editar'])){
  // carga datos y muestra formulario de edición
} else {
  // muestra formulario de creación
}
?>
