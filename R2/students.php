

<?php

$servername = "localhost";
$username   = "root";
$password   = "0000";
$dbname     = "students";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}


$sql = "
SELECT
  s.id_students   AS ID,
  s.nombre        AS Nombre,
  sex.descripcion AS Sexo,
  s.edad          AS Edad,
  s.nacimiento    AS Fecha_Nacimiento,
  p.pais          AS País,
  s.telefono      AS Teléfono,
  s.correo        AS Correo,
  s.domicilio     AS Domicilio,
  s.foto          AS Foto,
  s.lista         AS Lista,
  s.excel         AS Excel
FROM student s
JOIN sexo sex
  ON s.id_sexo = sex.id_sexo
JOIN paises p
  ON s.id_paises = p.id_paises
";


$result = $conn->query($sql);


if ($result->num_rows > 0) {
    
    echo "<table cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Sexo</th>
            <th>Edad</th>
            <th>Fecha Nacimiento</th>
            <th>País</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Domicilio</th>
            <th>Foto</th>
            <th>Lista</th>
            <th>Excel</th>
          </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['ID']."</td>";
        echo "<td>".$row['Nombre']."</td>";
        echo "<td>".$row['Sexo']."</td>";
        echo "<td>".$row['Edad']."</td>";
        echo "<td>".$row['Fecha_Nacimiento']."</td>";
        echo "<td>".$row['País']."</td>";
        echo "<td>".$row['Teléfono']."</td>";
        echo "<td>".$row['Correo']."</td>";
        echo "<td>".$row['Domicilio']."</td>";
        
        
        echo "<td><img src=' ".$row['Foto']."' width='80'></td>";
        
        
        echo "<td><a href=' ".$row['Lista']."'>Mostrar </a></td>";
        
        
        echo "<td><a href=' ".$row['Excel']."'>Descargar</a></td>";
        
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
    
    <link rel="stylesheet" href="style.css" media="screen" />
</html>