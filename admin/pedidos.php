<?php
// admin/pedidos.php
include '../includes/conexBDD.php';

echo '<h2>Pedidos Pendientes</h2>';
$result = $connPHP->query("
  SELECT p.ID_Pedido, c.Nombre AS cliente, p.Fecha, p.Estado
  FROM pedido p
  JOIN cliente c ON p.ID_Cliente = c.ID_Cliente
  WHERE p.Estado <> 'Entregado'
  ORDER BY p.Fecha DESC
");
echo '<table><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr>';
while($r = $result->fetch_assoc()){
  echo "<tr>
          <td>{$r['ID_Pedido']}</td>
          <td>{$r['cliente']}</td>
          <td>{$r['Fecha']}</td>
          <td>{$r['Estado']}</td>
          <td>
            <a href=\"admin.php?seccion=pedidos&accion=entregar&id={$r['ID_Pedido']}\">Marcar Entregado</a> |
            <a href=\"admin.php?seccion=pedidos&accion=anular&id={$r['ID_Pedido']}\">Anular</a>
          </td>
        </tr>";
}
echo '</table>';

// Procesar acciones
if(isset($_GET['accion'], $_GET['id'])){
  $id = (int)$_GET['id'];
  if($_GET['accion'] === 'entregar'){
    $connPHP->query("UPDATE pedido SET Estado='Entregado' WHERE ID_Pedido=$id");
  } elseif($_GET['accion'] === 'anular'){
    $connPHP->query("UPDATE pedido SET Estado='Anulado' WHERE ID_Pedido=$id");
  }
  header('Location: admin.php?seccion=pedidos');
  exit;
}
?>
