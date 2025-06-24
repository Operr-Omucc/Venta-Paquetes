<?php
session_start();
if (!isset($_SESSION['ID_Cliente']) || !in_array($_SESSION['Rol'], ['admin', 'jefe'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Administrativo</title>
  <link rel="stylesheet" href="style.css">
  <style>
    

    .h1-dashboard{
      background-color: #012638;
      color: white;
      margin:0px;
      padding: 5vh;
      border-bottom: solid black 2px;
    }

    ul{
      margin: 0px;
      display: flex;
      flex-wrap: wrap;
      align-content: center;
      justify-content: center;
      list-style: none;
      background-color: #023047;
      padding: 2vh;
      max-height: 80vh;
      border-bottom: solid black 2px;
    }

    li{
      border: 1px solid hsla(0, 0%, 0%, 0.5);
      border-radius: 8px;
      margin: 1vh;
      display: flex;
      justify-content: center;
      align-content: center;
    }

    li a{
      color: white;
      text-align: center;
      padding: 1vh;
    }

    /*administracion- productos*/
    
    .ap-centrar{
      display: flex;
      justify-content: space-around;
      align-content: center;
      flex-wrap: wrap;
      width: 100%;
    }

    main {
      padding: 1vh; 
      background:
       #f5f5f5; 
      max-width: 100vh;
      }

    .volver-btn{
      margin-left: 5vh;
    }

  </style>
</head>
<body>
  <h1 class="h1-dashboard">Panel de Administración</h1>
  <ul>
    <li><a href="admin_productos.php">Carga y gestión de productos</a></li>
    <li><a href="admin_catalogo.php">Consulta de catálogo de productos</a></li>
    <li><a href="admin_pedidos.php">Estado de pedidos pendientes</a></li>
    <li><a href="admin_entregas.php">Proceso de entrega de pedidos</a></li>
    <li><a href="admin_anulaciones.php">Anulación de pedidos</a></li>
    <li><a href="admin_cuentas.php">Consulta de estados de cuenta</a></li>
    <li><a href="admin_cobros.php">Cálculo y gestión de cobros</a></li>
    <li><a href="admin_ventas.php">Registro histórico de ventas</a></li>
    <li><a href="admin_usuarios.php">Administración de usuarios internos</a></li>
  </ul>
      <a href="index.php" class="volver-btn">← Volver al inicio</a>
</body>
</html>
