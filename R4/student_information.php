<?php
require_once 'conn.php';

$conn = new mysqli($servername, $username, $password, $dbname, $port);
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
  s.excel         AS Excel
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
WHERE s.id_students = $student_id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo "<h2>ID Seleccionada</h2>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 50%; margin: 0 auto;'>";

    // Muestra todos los campos en orden
    $fields = [
        'ID' => 'ID',
        'Nombre' => 'Nombre',
        'Sexo' => 'Sexo',
        'Edad' => 'Edad',
        'Fecha_Nacimiento' => 'Fecha de Nacimiento',
        'Pais' => 'País',
        'Telefono' => 'Teléfono',
        'Correo' => 'Correo',
        'Domicilio' => 'Domicilio'
    ];

    foreach ($fields as $key => $label) {
        echo "<tr>";
        echo "<td>$label</td>";

        if ($key === 'Sexo') {
            // Mostrar sexo con especificación si es "Otro" y tiene valor
            if ($row['Sexo'] == 'Otro' && !empty($row['Especifique'])) {
                echo "<td>" . htmlspecialchars($row['Sexo'] . " - " . $row['Especifique']) . "</td>";
            } else {
                echo "<td>" . htmlspecialchars($row[$key]) . "</td>";
            }
        } else {
            echo "<td>" . htmlspecialchars($row[$key]) . "</td>";
        }

        echo "</tr>";
    }

    // Archivos
    echo "<tr>";
    echo "<td>Foto</td>";
    echo "<td><img src='./" . $row['Foto'] . "' width='120' height='150' style='object-fit: cover;'></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>Lista</td>";
    echo "<td><a href='./" . $row['Lista'] . "' download class='btn'>Descargar</a></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>Archivo Excel</td>";
    echo "<td><a href='./" . $row['Excel'] . "' class='btn'>Descargar</a></td>";
    echo "</tr>";

    echo "</table>";

    echo "<a href='students.php' class='btn'>
        <img src='./uploads/return-svgrepo-com.svg' width='80' alt='Volver a los resultados'>
        </a><br><br>";

} else {
    echo "ID incorrecta: $student_id";
    echo "<br><a href='students.php' class='btn'>Volver a los resultados</a>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="sstyle.css" media="screen" />
    <title>Detalles</title>
</head>

<body>
</body>

</html>