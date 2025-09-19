<?php
session_start();
require_once 'conn.php';

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error en la conexión: ' . $conn->connect_error
    ]);
    exit();
}

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$id_usuario = $_SESSION["id_perfil"] ?? 0;
$permiso_editar = ($id_usuario == 1 || $id_usuario == 2);
$permiso_borrar = ($id_usuario == 1);

// Enviar respuestas
function sendJsonResponse($success, $message, $httpCode = 200)
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit();
}

// Eliminación de registro
if ($action == 'eliminar') {
    if (!$permiso_borrar) {
        sendJsonResponse(false, 'No tienes permisos para eliminar registros.', 403);
    }

    $student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($student_id <= 0) {
        sendJsonResponse(false, 'ID inválido', 400);
    }

    // Verificar confirmación
    if (!isset($_GET['confirm']) || $_GET['confirm'] != 'true') {
        sendJsonResponse(false, 'Confirmación requerida', 400);
    }

    $id_usuario_borrador = $_SESSION["id_usuario"] ?? null;
    $sql = "UPDATE student SET visible = 0, fecha_borrado = NOW(), id_usuario_borrador = $id_usuario_borrador WHERE id_students = $student_id";

    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            sendJsonResponse(true, 'Registro eliminado correctamente');
        } else {
            sendJsonResponse(false, 'No se encontró el registro o ya estaba eliminado');
        }
    } else {
        sendJsonResponse(false, 'Error al eliminar: ' . $conn->error, 500);
    }
}

// Procesar actualización de registro
elseif ($action == 'actualizar') {
    if (!$permiso_editar) {
        sendJsonResponse(false, 'No tienes permisos para editar registros.', 403);
    }

    $student_id = intval($_POST['id']);
    if ($student_id <= 0) {
        sendJsonResponse(false, 'ID inválido', 400);
    }

    // Obtener los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $sexo = $conn->real_escape_string($_POST['sexo']);
    $especifique = ($sexo == 'Otro' && isset($_POST['especifique'])) ? $conn->real_escape_string($_POST['especifique']) : "";
    $edad = intval($_POST['age']);
    $nacimiento = $conn->real_escape_string($_POST['bday']);
    $pais = $conn->real_escape_string($_POST['country']);
    $telefono = $conn->real_escape_string($_POST['phone']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $domicilio = $conn->real_escape_string($_POST['domicilio']);

    // Relacionar sexo a ID
    $id_sexo = match ($sexo) {
        'Masculino' => 1,
        'Femenino' => 2,
        'Otro' => 3,
        default => 1
    };

    // Relacionar país a ID
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
    $upload_dir = "uploads/";
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

    // Procesar archivos subidos
    $archivos = [
        'photo' => ['prefix' => 'foto', 'max_size' => 5 * 1024 * 1024],
        'excel' => ['prefix' => 'excel']
    ];

    foreach ($archivos as $fileInput => $config) {
        if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] == 0) {
            // Validar tamaño máximo para la foto
            if (isset($config['max_size']) && $_FILES[$fileInput]['size'] > $config['max_size']) {
                sendJsonResponse(false, 'Error: La imagen es demasiado grande. El tamaño máximo permitido es 5MB.', 400);
            }

            $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
            $name = "{$config['prefix']}_" . time() . ".$ext";
            move_uploaded_file($_FILES[$fileInput]['tmp_name'], $upload_dir . $name);
            ${$config['prefix'] . '_path'} = $upload_dir . $name;
        } elseif (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] == UPLOAD_ERR_INI_SIZE) {
            sendJsonResponse(false, 'Error: El archivo es demasiado grande. El tamaño máximo permitido es 5MB.', 400);
        }
    }

    // Actualizar en la base de datos
    $id_usuario_editor = $_SESSION["id_usuario"] ?? null;
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
        sendJsonResponse(false, 'Error al actualizar: ' . $conn->error, 500);
    }
} else {
    sendJsonResponse(false, 'Acción no válida.', 400);
}

?>