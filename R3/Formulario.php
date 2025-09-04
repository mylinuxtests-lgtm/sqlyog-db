<?php
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "students";
$port = "755";

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
switch ($sexo) {
    case 'Hombre':
        $id_sexo = 1;
        break;
    case 'Mujer':
        $id_sexo = 2;
        break;
    case 'Otro':
        $id_sexo = 3;
        break;
}

$id_paises = 0;
$paises_map = [
    'Alemania' => 1,
    'Brazil' => 2,
    'Canada' => 3,
    'China' => 4,
    'Estados Unidos' => 5,
    'India' => 6,
    'Indonesia' => 7,
    'Japon' => 8,
    'Mexico' => 9,
    'Rusia' => 10
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
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" media="screen" />
        <title>Registro Exitoso</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #99968dab;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .success-container {
                background-color: rgba(231, 227, 163, 0.97);
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.959);
                text-align: center;
                max-width: 600px;
                width: 90%;
            }
            .success-message {
                font-size: 28px;
                font-weight: bold;
                color: #921414ff;
                margin-bottom: 30px;
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.8);
                border-radius: 5px;
                border: 2px solid #0e4e08;
            }
            .button-container {
                display: flex;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }
            .btn {
                display: inline-block;
                padding: 15px 30px;
                background-color: #0e4e08;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-size: 18px;
                transition: background-color 0.3s;
                border: none;
                cursor: pointer;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            }
            .btn:hover {
                background-color: #62a045;
            }
            .btn-secondary {
                background-color: #f11515ff;
            }
            .btn-secondary:hover {
                background-color: #ca1414ff;
            }
        </style>
    </head>
    <body>
        <div class="success-container">
            <div class="success-message">
                ¡Registro guardado exitosamente!
            </div>
            <div class="button-container">
                <a href="students.php" class="btn">Ver todos los registros</a>
                <a href="Formulario.html" class="btn btn-secondary">Volver al formulario</a>
            </div>
        </div>
    </body>
    </html>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>