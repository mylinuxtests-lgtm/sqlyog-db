<?php
session_start();

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
  // Obtener los datos
  $export_sql = "
    SELECT
      s.id_students AS ID,
      s.nombre AS Nombre,
      sex.descripcion AS Sexo,
      s.especifique AS Especifique,
      s.edad AS Edad,
      DATE_FORMAT(s.nacimiento, '%d-%m-%Y') AS Fecha_Nacimiento,
      p.pais AS Pais,
      s.telefono AS Telefono,
      s.correo AS Correo,
      s.domicilio AS Domicilio,
      s.foto AS Foto,
      s.lista AS Lista,
      s.excel AS Excel,
      DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
      DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion,
      DATE_FORMAT(s.fecha_acceso, '%d-%m-%Y %H:%i') AS Fecha_Acceso,
      s.id_usuario_registro AS ID_Usuario_Registro,
      s.id_usuario_editor AS ID_Usuario_Editor,
      s.visible AS Visible,
      DATE_FORMAT(s.fecha_borrado, '%d-%m-%Y %H:%i') AS Fecha_Borrado,
      s.id_usuario_borrador AS ID_Usuario_Borrador
    FROM student s
    JOIN sexo sex ON s.id_sexo = sex.id_sexo
    JOIN paises p ON s.id_paises = p.id_paises
    WHERE s.visible = 1
    ";

  $export_result = $conn->query($export_sql);

  // Nombre del archivo
  if ($export_result->num_rows > 0) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=registros_estudiantes_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, array(
      'ID',
      'Nombre',
      'Sexo',
      'Especifique',
      'Edad',
      'Fecha_Nacimiento',
      'Pais',
      'Telefono',
      'Correo',
      'Domicilio',
      'Foto',
      'Lista',
      'Excel',
      'Fecha_Registro',
      'Fecha_Edicion',
      'Fecha_Acceso',
      'ID_Usuario_Registro',
      'ID_Usuario_Editor',
      'Visible',
      'Fecha_Borrado',
      'ID_Usuario_Borrador'
    ));

    while ($row = $export_result->fetch_assoc()) {
      fputcsv($output, $row);
    }

    fclose($output);
    exit();
  }
}

// Procesar búsqueda
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$where_condition = "WHERE s.visible = 1";

// Buscar en los campos
if (!empty($search_query)) {
  $sanitized_query = $conn->real_escape_string($search_query);
  $where_condition .= " AND (s.nombre LIKE '%$sanitized_query%' OR 
                            sex.descripcion LIKE '%$sanitized_query%' OR 
                            s.especifique LIKE '%$sanitized_query%' OR 
                            s.edad LIKE '%$sanitized_query%' OR 
                            p.pais LIKE '%$sanitized_query%' OR 
                            s.telefono LIKE '%$sanitized_query%' OR 
                            s.correo LIKE '%$sanitized_query%' OR 
                            s.domicilio LIKE '%$sanitized_query%')";
}

// Extrae la informacion de la base de datos
$sql = "
SELECT
  s.id_students   AS ID,
  s.nombre        AS Nombre,
  DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
  DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
$where_condition
ORDER BY s.id_students
";

$result = $conn->query($sql);

// Crea r un nuevo registro
if ($result->num_rows > 0) {
  echo "<div class='user-header'>";
  echo "<h2>Usuario: <strong>" . htmlspecialchars($_SESSION["usuario"]) . "</strong></h2>";
  echo "<a href='login.php?action=logout'>Cerrar sesión</a>";
  echo "</div>";

  echo "<div style='text-align: center;'>";
  echo "<a href='Registro.php' class='new-student-btn'>Registrar nuevo estudiante</a>";

  if ($permiso_exportar) {
    echo "<a href='students.php?export=csv" . (!empty($search_query) ? "&search_query=" . urlencode($search_query) : "") . "' class='export-csv-btn'>Exportar a CSV</a>";
  }

  // Formulario de búsqueda
  echo "<div class='search-container'>";
  echo "<form action='students.php' method='GET' class='search-form'>";
  echo "<input type='text' name='search_query' placeholder='Buscar en registros' value='" . htmlspecialchars($search_query) . "'>";
  echo "<button type='submit'>Buscar</button>";

  if (!empty($search_query)) {
    echo "<a href='students.php'>Limpiar</a>";
  }
  
  echo "</form>";
  echo "</div>";
  
  echo "</div>";

  // Resultados de búsqueda
  if (!empty($search_query)) {
    echo "<div style='text-align: center; margin: 10px 0;'>";
    echo "<p>Mostrando resultados para: <strong>" . htmlspecialchars($search_query) . "</strong></p>";
    echo "</div>";
  }

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
    
    if (!empty($search_query)) {
      $highlighted_name = preg_replace("/(" . preg_quote($search_query, '/') . ")/i", "<mark>$1</mark>", htmlspecialchars($row['Nombre']));
      echo "<td>" . $highlighted_name . "</td>";
    } else {
      echo "<td>" . htmlspecialchars($row['Nombre']) . "</td>";
    }
    
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

  // En caso de que no existan resultados similares
  if (!empty($search_query)) {
    echo "<p>No se encontraron resultados para: <strong>" . htmlspecialchars($search_query) . "</strong></p>";
    echo "<a href='students.php'>Ver todos los registros</a><br><br>";
  } else {
    echo "<p>No se encontraron resultados.</p>";
  }
  
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
</head>

<body>
</body>

</html>