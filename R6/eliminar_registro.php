<?php
require_once 'conn.php';

$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    die("ID inválido");
}

$sql = "UPDATE student SET visible = 0, fecha_borrado = NOW() WHERE id_students = $student_id";

if ($conn->query($sql) === TRUE) {
    header("Location: students.php");
} else {
    echo "Error al eliminar: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>