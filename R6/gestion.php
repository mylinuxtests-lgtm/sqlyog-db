<?php
session_start();
require_once 'conn.php';

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

$id_usuario = $_SESSION["id_perfil"] ?? 0;
$permiso_editar = ($id_usuario == 1 || $id_usuario == 2);
$permiso_borrar = ($id_usuario == 1);

// Eliminación de registro
if ($action == 'eliminar') {
    if (!$permiso_borrar) {
        die("No tienes permisos para eliminar registros.");
    }
    $student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($student_id <= 0) {
        die("ID inválido");
    }

    // Verificar confirmación
    if (!isset($_GET['confirm']) || $_GET['confirm'] != 'true') {
        ?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmar Eliminación</title>
            <link rel="stylesheet" href="style.css" media="screen" />
        </head>

        <body>
            <div class="confirmation-container">
                <h2>¿Estás seguro de eliminar este registro?</h2>
                <p>Esta acción no se puede deshacer.</p>
                <div class="buttons">
                    <a href="gestion.php?action=eliminar&id=<?php echo $student_id; ?>&confirm=true" class="btn btn-danger">Sí,
                        eliminar</a>
                    <a href="students.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </body>

        </html>
        <?php
        exit();
    }

    $id_usuario_borrador = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : null;
    $sql = "UPDATE student SET visible = 0, fecha_borrado = NOW(), id_usuario_borrador = $id_usuario_borrador WHERE id_students = $student_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error al eliminar: " . $sql . "<br>" . $conn->error;
    }
}

// Procesar actualización de registro
elseif ($action == 'actualizar') {
    if (!$permiso_editar) {
        die("No tienes permisos para editar registros.");
    }
    // Obtener el ID del estudiante
    $student_id = intval($_POST['id']);

    if ($student_id <= 0) {
        die("ID inválido");
    }

    // Obtener los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $sexo = $conn->real_escape_string($_POST['sexo']);
    $especifique = "";
    if ($sexo == 'Otro' && isset($_POST['especifique'])) {
        $especifique = $conn->real_escape_string($_POST['especifique']);
    }
    $edad = intval($_POST['age']);
    $nacimiento = $conn->real_escape_string($_POST['bday']);
    $pais = $conn->real_escape_string($_POST['country']);
    $telefono = $conn->real_escape_string($_POST['phone']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $domicilio = $conn->real_escape_string($_POST['domicilio']);

    // Relacionar sexo a ID
    $id_sexo = 0;
    switch ($sexo) {
        case 'Masculino':
            $id_sexo = 1;
            break;
        case 'Femenino':
            $id_sexo = 2;
            break;
        case 'Otro':
            $id_sexo = 3;
            break;
    }

    // Relacionar país a ID
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

    // Procesar archivos
    $upload_dir = "./uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Obtener información actual
    $sql_actual = "SELECT foto, lista, excel FROM student WHERE id_students = $student_id";
    $result_actual = $conn->query($sql_actual);
    $actual = $result_actual->fetch_assoc();

    $foto_path = $actual['foto'];
    $lista_path = $actual['lista'];
    $excel_path = $actual['excel'];

    // Procesar foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $foto_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $foto_name = "foto_" . time() . "." . $foto_ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $foto_name);
        $foto_path = $upload_dir . $foto_name;
    }

    // Procesar lista
    if (isset($_FILES['list']) && $_FILES['list']['error'] == 0) {
        $lista_ext = pathinfo($_FILES['list']['name'], PATHINFO_EXTENSION);
        $lista_name = "lista_" . time() . "." . $lista_ext;
        move_uploaded_file($_FILES['list']['tmp_name'], $upload_dir . $lista_name);
        $lista_path = $upload_dir . $lista_name;
    }

    // Procesar excel
    if (isset($_FILES['excel']) && $_FILES['excel']['error'] == 0) {
        $excel_ext = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);
        $excel_name = "excel_" . time() . "." . $excel_ext;
        move_uploaded_file($_FILES['excel']['tmp_name'], $upload_dir . $excel_name);
        $excel_path = $upload_dir . $excel_name;
    }

    // Actualizar en la base de datos
    $id_usuario_editor = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : null;
    $sql = "UPDATE student SET 
            nombre = '$nombre', 
            id_sexo = $id_sexo,
            especifique = '$especifique', 
            edad = $edad, 
            nacimiento = '$nacimiento', 
            id_paises = $id_paises, 
            telefono = '$telefono', 
            correo = '$correo', 
            domicilio = '$domicilio', 
            foto = '$foto_path', 
            lista = '$lista_path', 
            excel = '$excel_path',
            fecha_edicion = NOW(),
            id_usuario_editor = $id_usuario_editor
            WHERE id_students = $student_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error al actualizar: " . $sql . "<br>" . $conn->error;
    }
}

// Nuevo registro
elseif ($action == 'crear') {
    // Obtener los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $sexo = $conn->real_escape_string($_POST['sexo']);
    $especifique = "";
    if ($sexo == 'Otro' && isset($_POST['especifique'])) {
        $especifique = $conn->real_escape_string($_POST['especifique']);
    }
    $edad = intval($_POST['age']);
    $nacimiento = $conn->real_escape_string($_POST['bday']);
    $pais = $conn->real_escape_string($_POST['country']);
    $telefono = $conn->real_escape_string($_POST['phone']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $domicilio = $conn->real_escape_string($_POST['domicilio']);

    // Relacionar sexo a ID
    $id_sexo = 0;
    switch ($sexo) {
        case 'Masculino':
            $id_sexo = 1;
            break;
        case 'Femenino':
            $id_sexo = 2;
            break;
        case 'Otro':
            $id_sexo = 3;
            break;
    }

    // Relacionar país a ID
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

    // Procesar archivos
    $upload_dir = "./uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $foto_path = "";
    $lista_path = "";
    $excel_path = "";

    // Procesar foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $foto_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $foto_name = "foto_" . time() . "." . $foto_ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $foto_name);
        $foto_path = $upload_dir . $foto_name;
    }

    // Procesar lista
    if (isset($_FILES['list']) && $_FILES['list']['error'] == 0) {
        $lista_ext = pathinfo($_FILES['list']['name'], PATHINFO_EXTENSION);
        $lista_name = "lista_" . time() . "." . $lista_ext;
        move_uploaded_file($_FILES['list']['tmp_name'], $upload_dir . $lista_name);
        $lista_path = $upload_dir . $lista_name;
    }

    // Procesar excel
    if (isset($_FILES['excel']) && $_FILES['excel']['error'] == 0) {
        $excel_ext = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);
        $excel_name = "excel_" . time() . "." . $excel_ext;
        move_uploaded_file($_FILES['excel']['tmp_name'], $upload_dir . $excel_name);
        $excel_path = $upload_dir . $excel_name;
    }

    // Insertar en la base de datos
    $id_usuario_registro = isset($_SESSION["id_usuario"]) ? $_SESSION["id_usuario"] : null;
    $sql = "INSERT INTO student (nombre, id_sexo, especifique, edad, nacimiento, id_paises, telefono, correo, domicilio, foto, lista, excel, fecha_registro, id_usuario_registro, visible) 
            VALUES ('$nombre', $id_sexo, '$especifique', $edad, '$nacimiento', $id_paises, '$telefono', '$correo', '$domicilio', '$foto_path', '$lista_path', '$excel_path', NOW(), $id_usuario_registro, 1)";

    if ($conn->query($sql) === TRUE) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error al crear: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Acción no válida.";
}

$conn->close();
?>