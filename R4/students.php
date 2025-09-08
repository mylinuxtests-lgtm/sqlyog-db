<?php
require_once 'conn.php';

// Establece una conexion
$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  die("Error en la conexión: " . $conn->connect_error);
}

// Extrae la informacion de la base de datos
$sql = "
SELECT
  s.id_students   AS ID,
  s.nombre        AS Nombre,
  sex.descripcion AS Sexo,
  s.especifique   AS Especifique,
  s.edad          AS Edad,
  DATE_FORMAT(s.nacimiento, '%d-%m-%Y') AS Fecha_Nacimiento,
  p.pais          AS Pais,
  s.telefono      AS Telefono,
  s.correo        AS Correo,
  s.domicilio     AS Domicilio,
  s.foto          AS Foto,
  s.lista         AS Lista,
  s.excel         AS Excel
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<h2>Registros</h2>";

  echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
  echo "<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Sexo</th>
            <th>Edad</th>
            <th>Fecha Nacimiento</th>
            <th>Pais</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Domicilio</th>
            <th>Foto</th>
            <th>Lista</th>
            <th>Excel</th>
            <th>Ver esta ID</th>
            <th>Editar</th>
          </tr>";

  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['ID'] . "</td>";
    echo "<td>" . $row['Nombre'] . "</td>";
    echo "<td>";
    if ($row['Sexo'] == 'Otro' && !empty($row['Especifique'])) {
      echo htmlspecialchars($row['Sexo'] . " - " . $row['Especifique']);
    } else {
      echo htmlspecialchars($row['Sexo']);
    }
    echo "</td>";
    echo "<td>" . $row['Edad'] . "</td>";
    echo "<td>" . $row['Fecha_Nacimiento'] . "</td>";
    echo "<td>" . $row['Pais'] . "</td>";
    echo "<td>" . $row['Telefono'] . "</td>";
    echo "<td>" . $row['Correo'] . "</td>";
    echo "<td>" . $row['Domicilio'] . "</td>";

    // Muestra la foto subida
    echo "<td><img src='./" . $row['Foto'] . "' width='80' height='100' style='object-fit: cover;'></td>";

    // Columna ver lista
    echo "<td><a href='./" . $row['Lista'] . "''>
        <img src='./uploads/notes-svgrepo-com.svg' width='80' alt='Mostrar Lista'>
        </a>
        </td>";

    // Columna descargar excel
    echo "<td>
                <a href='./" . $row['Excel'] . "''>
                    <img src='./uploads/excel-svgrepo-com.svg' width='80' alt='Descargar Excel'>
                </a>
              </td>";

    // Columna ver informacion
    echo "<td>
                <a href='student_information.php?id=" . $row['ID'] . "''>
                   <img src='./uploads/magnifying.png' width='80' alt='Ver detalles'>
                </a>
              </td>";

    // Columna editar
    echo "<td>
                <a href='editar_formulario.php?id=" . $row['ID'] . "'>
                   <img src='./uploads/edit.svg' width='80' alt='Editar registro'>
                </a>
              </td>";

    echo "</tr>";
  }
  echo "</table>";

  echo "<a href='Formulario.html' class='btn'>
    <img src='./uploads/return-svgrepo-com.svg' width='80' alt='Volver al Formulario'></a><br><br>";
} else {
  echo "No se encontraron resultados.";
  echo "<br><a href='Formulario.html' class='btn'>Regresar al Formulario</a>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="stylesheet" href="sstyle.css" media="screen" />
  <title>Base de datos</title>
</head>

<body>
</body>

</html>