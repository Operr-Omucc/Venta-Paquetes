<?php
// admin/cobros.php
include '../includes/conexBDD.php';

echo '<h2>Cobros y Facturas</h2>';
$result = $connPHP->query("
  SELECT f.ID_Factura, c.Nombre AS cliente, f.Fecha, f.Total, f.Estado
  FROM factura f
  JOIN cliente c ON f.ID_Cliente = c.ID_Cliente
  ORDER BY f.Fecha DESC
");
echo '<table><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>';
while($f = $result->fetch_assoc()){
  echo "<tr>
          <td>{$f['ID_Factura']}</td>
          <td>{$f['cliente']}</td>
          <td>{$f['Fecha']}</td>
          <td>\${$f['Total']}</td>
          <td>{$f['Estado']}</td>
          <td>
            <a href=\"factura_pdf.php?id={$f['ID_Factura']}\" target=\"_blank\">Ver PDF</a>
          </td>
        </tr>";
}
echo '</table>';
?>
