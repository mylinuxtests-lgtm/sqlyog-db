<?php

$servername = "localhost";
$username   = "root";
$password   = "0000";
$dbname     = "students";
$port       = "755";


$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");


if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}


$nombre = $conn->real_escape_string($_POST['nombre']);
$sexo = $conn->real_escape_string($_POST['sexo']);
$edad = intval($_POST['age']);
$nacimiento = $conn->real_escape_string($_POST['bday']);
$pais = $conn->real_escape_string($_POST['country']);
$telefono = $conn->real_escape_string($_POST['phone']);
$correo = $conn->real_escape_string($_POST['correo']);
$domicilio = $conn->real_escape_string($_POST['domicilio']);


$id_sexo = 0;
switch($sexo) {
    case 'Hombre': $id_sexo = 1; break;
    case 'Mujer': $id_sexo = 2; break;
    case 'Otro': $id_sexo = 3; break;
}

$id_paises = 0;
$paises_map = [
    'Alemania' => 1, 'Brazil' => 2, 'Canada' => 3, 'China' => 4,
    'Estados Unidos' => 5, 'India' => 6, 'Indonesia' => 7,
    'Japon' => 8, 'Mexico' => 9, 'Rusia' => 10
];
$id_paises = $paises_map[$pais] ?? 9; 


$upload_dir = "./uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}


$foto_name = "";
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $foto_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $foto_name = "foto_" . time() . "." . $foto_ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $foto_name);
}


$lista_name = "";
if (isset($_FILES['list']) && $_FILES['list']['error'] == 0) {
    $lista_ext = pathinfo($_FILES['list']['name'], PATHINFO_EXTENSION);
    $lista_name = "lista_" . time() . "." . $lista_ext;
    move_uploaded_file($_FILES['list']['tmp_name'], $upload_dir . $lista_name);
}


$excel_name = "";
if (isset($_FILES['excel']) && $_FILES['excel']['error'] == 0) {
    $excel_ext = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);
    $excel_name = "excel_" . time() . "." . $excel_ext;
    move_uploaded_file($_FILES['excel']['tmp_name'], $upload_dir . $excel_name);
}


$sql = "INSERT INTO student (nombre, id_sexo, edad, nacimiento, id_paises, telefono, correo, domicilio, foto, lista, excel)
        VALUES ('$nombre', $id_sexo, $edad, '$nacimiento', $id_paises, '$telefono', '$correo', '$domicilio', '$upload_dir$foto_name', '$upload_dir$lista_name', '$upload_dir$excel_name')";

if ($conn->query($sql) === TRUE) {
    echo "Registro guardado exitosamente!<br>";
    echo "<a href='students.php'>Ver todos los registros</a><br>";
    echo "<a href='Formulario.html'>Volver al formulario</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>