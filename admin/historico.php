<?php
// admin/historico.php
include '../includes/conexBDD.php';

echo '<h2>Histórico de Ventas</h2>';
$result = $connPHP->query("
  SELECT p.ID_Pedido, c.Nombre AS cliente, p.Fecha, p.Estado
  FROM pedido p
  JOIN cliente c ON p.ID_Cliente = c.ID_Cliente
  WHERE p.Estado IN ('Entregado','Anulado')
  ORDER BY p.Fecha DESC
");
echo '<table><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Estado</th></tr>';
while($h = $result->fetch_assoc()){
  echo "<tr>
          <td>{$h['ID_Pedido']}</td>
          <td>{$h['cliente']}</td>
          <td>{$h['Fecha']}</td>
          <td>{$h['Estado']}</td>
        </tr>";
}
echo '</table>';
?>
