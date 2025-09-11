<?php
require_once 'conn.php';

$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    die("ID invalido");
}

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
  s.excel         AS Excel,
  DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
  s.visible       AS Visible,
  DATE_FORMAT(s.fecha_borrado, '%d-%m-%Y %H:%i') AS Fecha_Borrado
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
WHERE s.id_students = $student_id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo "<div class='user-header'>";
    echo "<h2>Detalles del Estudiante</h2>";
    echo "<a href='students.php'>Volver a registros</a>";
    echo "</div>";

    echo "<div class='table-container'>";
    echo "<table class='students-table'>";

    // Campos principales
    $fields = [
        'ID' => 'ID',
        'Nombre' => 'Nombre',
        'Sexo' => 'Sexo',
        'Edad' => 'Edad',
        'Fecha_Nacimiento' => 'Fecha de Nacimiento',
        'Pais' => 'País',
        'Telefono' => 'Teléfono',
        'Correo' => 'Correo',
        'Domicilio' => 'Domicilio',
        'Fecha_Registro' => 'Fecha de Registro'
    ];

    foreach ($fields as $key => $label) {
        echo "<tr>";
        echo "<td><strong>$label</strong></td>";

        if ($key === 'Sexo') {
            if ($row['Sexo'] == 'Otro' && !empty($row['Especifique'])) {
                echo "<td>" . htmlspecialchars($row['Sexo'] . " - " . $row['Especifique']) . "</td>";
            } else {
                echo "<td>" . htmlspecialchars($row[$key]) . "</td>";
            }
        } else {
            echo "<td>" . htmlspecialchars($row[$key] ?? 'N/A') . "</td>";
        }
        echo "</tr>";
    }

    // Archivos
    echo "<tr>";
    echo "<td><strong>Foto</strong></td>";
    echo "<td><img src='./" . $row['Foto'] . "' width='120' height='150' style='object-fit: cover; border-radius: 4px;'></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td><strong>Lista</strong></td>";
    echo "<td><a href='./" . $row['Lista'] . "' download class='action-btn'>Descargar Lista</a></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td><strong>Archivo Excel</strong></td>";
    echo "<td><a href='./" . $row['Excel'] . "' class='action-btn'>Descargar Excel</a></td>";
    echo "</tr>";

    echo "</table>";
    echo "</div>";

    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<a href='students.php' class='action-btn'>Volver a Estudiantes</a>";
    echo "</div>";

} else {
    echo "<div class='user-header'>";
    echo "<h2>Error</h2>";
    echo "<a href='students.php'>Volver a estudiantes</a>";
    echo "</div>";

    echo "<div style='text-align: center; margin: 40px;'>";
    echo "<p>ID incorrecta: $student_id</p>";
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
    <title>Detalles del Estudiante</title>
</head>

<body>
</body>

</html>