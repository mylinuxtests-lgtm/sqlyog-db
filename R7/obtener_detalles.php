<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}


$id_usuario = $_SESSION["id_perfil"] ?? 0;
$permiso_exportar = ($id_usuario == 1 || $id_usuario == 2);

require_once 'conn.php';

$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit();
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
  DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
WHERE s.id_students = $student_id AND s.visible = 1
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Estudiante no encontrado']);
}

$conn->close();
?>