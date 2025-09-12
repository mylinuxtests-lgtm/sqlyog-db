<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}

require_once 'conn.php';

// Establece una conexion
$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  die("Error en la conexión: " . $conn->connect_error);
}

// Permisos del usuario
$id_usuario = $_SESSION["id_perfil"] ?? 0;
$permiso_editar = ($id_usuario == 1 || $id_usuario == 2);
$permiso_borrar = ($id_usuario == 1);
$permiso_exportar = ($id_usuario == 1 || $id_usuario == 2);

// Exportar CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv' && $permiso_exportar) {
  // Obtener todos los datos de estudiantes
  $export_sql = "
    SELECT
      s.id_students   AS ID,
      s.nombre        AS Nombre,
      DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
      DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion
    FROM student s
    WHERE s.visible = 1
    ";

  $export_result = $conn->query($export_sql);

  if ($export_result->num_rows > 0) {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=estudiantes_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, array('ID', 'Nombre', 'Fecha Registro', 'Fecha Edición'));

    while ($row = $export_result->fetch_assoc()) {
      fputcsv($output, $row);
    }

    fclose($output);
    exit();
  }
}

// Extrae la informacion de la base de datos
$sql = "
SELECT
  s.id_students   AS ID,
  s.nombre        AS Nombre,
  DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
  DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion
FROM student s
WHERE s.visible = 1
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<div class='user-header'>";
  echo "<h2>Usuario: <strong>" . htmlspecialchars($_SESSION["usuario"]) . "</strong></h2>";
  echo "<a href='login.php?action=logout'>Cerrar sesión</a>";
  echo "</div>";

  echo "<div style='text-align: center;'>";
  echo "<a href='Registro.php' class='new-student-btn'>Registrar nuevo estudiante</a>";

  if ($permiso_exportar) {
    echo "<a href='students.php?export=csv' class='export-csv-btn'>Exportar a CSV</a>";
  }

  echo "</div>";

  echo "<div class='table-container'>";
  echo "<table class='students-table'>";
  echo "<tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Fecha Registro</th>
        <th>Fecha Edición</th>
        <th>Ver</th>
        <th>Editar</th>
        <th>Eliminar</th>
      </tr>";

  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['ID'] . "</td>";
    echo "<td>" . htmlspecialchars($row['Nombre']) . "</td>";
    echo "<td>" . $row['Fecha_Registro'] . "</td>";
    echo "<td>" . ($row['Fecha_Edicion'] ? $row['Fecha_Edicion'] : '') . "</td>";


    echo "<td>
            <a href='student_information.php?id=" . $row['ID'] . "' class='btn-table'>
                <img src='./templates/magnifying.png' width='30' alt='Ver detalles' title='Ver detalles'>
            </a>
          </td>";


    if ($permiso_editar) {
      echo "<td>
                <a href='editar_registro.php?id=" . $row['ID'] . "' class='btn-table'>
                    <img src='./templates/edit.svg' width='30' alt='Editar registro' title='Editar registro'>
                </a>
              </td>";
    } else {
      echo "<td>
                <span class='btn-table disabled' style='opacity: 0.5; cursor: not-allowed;'>
                    <img src='./templates/edit.svg' width='30' alt='Sin permisos para editar' title='Sin permisos para editar'>
                </span>
              </td>";
    }


    if ($permiso_borrar) {
      echo "<td>
                <a href='gestion.php?action=eliminar&id=" . $row['ID'] . "' class='btn-table' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\")'>
                    <img src='./templates/delete.svg' width='30' alt='Eliminar registro' title='Eliminar registro'>
                </a>
              </td>";
    } else {
      echo "<td>
                <span class='btn-table disabled' style='opacity: 0.5; cursor: not-allowed;'>
                    <img src='./templates/delete.svg' width='30' alt='Sin permisos para eliminar' title='Sin permisos para eliminar'>
                </span>
              </td>";
    }

    echo "</tr>";
  }
  echo "</table>";
  echo "</div>";
} else {
  echo "<div class='user-header'>";
  echo "<h2>Usuario: <strong>" . htmlspecialchars($_SESSION["usuario"]) . "</strong></h2>";
  echo "<a href='login.php?action=logout'>Cerrar sesión</a>";
  echo "</div>";

  echo "<div style='text-align: center; margin: 40px;'>";
  echo "<p>No se encontraron resultados.</p>";
  echo "<a href='Registro.php' class='new-student-btn'>Registrar nuevo estudiante</a>";
  echo "</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" media="screen" />
  <title>Base de datos de Estudiantes</title>
  <style>
    .export-csv-btn {
      background-color: #274472;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 4px;
      display: inline-block;
      margin-left: 15px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .export-csv-btn:hover {
      background-color: #1a3152;
      color: white;
    }
  </style>
</head>

<body>
</body>

</html>