<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}

require_once 'conn.php';

// Establece una conexion
$conn = new mysqli($servername, $username, $db_password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  die("Error en la conexión: " . $conn->connect_error);
}

// Permisos del usuario
$id_usuario = $_SESSION["id_perfil"] ?? 0;
$permiso_editar = ($id_usuario == 1 || $id_usuario == 2);
$permiso_borrar = ($id_usuario == 1);
$permiso_exportar = ($id_usuario == 1 || $id_usuario == 2);

// Exportar datos completos
if (isset($_GET['export']) && $permiso_exportar) {
    $export_type = $_GET['export'];
    $search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
    
    $where_condition = "WHERE s.visible = 1";
    
    if (!empty($search_query)) {
        $sanitized_query = $conn->real_escape_string($search_query);
        $where_condition .= " AND (s.nombre LIKE '%$sanitized_query%' OR 
                                  sex.descripcion LIKE '%$sanitized_query%' OR 
                                  s.especifique LIKE '%$sanitized_query%' OR 
                                  s.edad LIKE '%$sanitized_query%' OR 
                                  p.pais LIKE '%$sanitized_query%' OR 
                                  s.telefono LIKE '%$sanitized_query%' OR 
                                  s.correo LIKE '%$sanitized_query%' OR 
                                  s.domicilio LIKE '%$sanitized_query%')";
    }
    
    // Obtener todos los datos completos
    $export_sql = "
    SELECT
        s.id_students AS ID,
        s.nombre AS Nombre,
        sex.descripcion AS Sexo,
        s.especifique AS Especifique,
        s.edad AS Edad,
        DATE_FORMAT(s.nacimiento, '%d-%m-%Y') AS Fecha_Nacimiento,
        p.pais AS Pais,
        s.telefono AS Telefono,
        s.correo AS Correo,
        s.domicilio AS Domicilio,
        s.foto AS Foto,
        s.lista AS Lista,
        s.excel AS Excel,
        DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
        DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion,
        s.visible AS Visible
    FROM student s
    JOIN sexo sex ON s.id_sexo = sex.id_sexo
    JOIN paises p ON s.id_paises = p.id_paises
    $where_condition
    ORDER BY s.id_students
    ";
    
    $export_result = $conn->query($export_sql);
    
    if ($export_result->num_rows > 0) {
        $data = array();
        while ($row = $export_result->fetch_assoc()) {
            $data[] = $row;
        }
        
        // Generar el archivo según el tipo solicitado
        switch ($export_type) {
            case 'csv':
                exportToCSV($data, 'estudiantes_completos');
                break;
            case 'excel':
                exportToExcel($data, 'estudiantes_completos');
                break;
            default:
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Formato no válido']);
                exit();
        }
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No hay datos para exportar']);
        exit();
    }
}

// Procesar búsqueda
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$where_condition = "WHERE s.visible = 1";

// Buscar informacion en los campos
if (!empty($search_query)) {
  $sanitized_query = $conn->real_escape_string($search_query);
  $where_condition .= " AND (s.nombre LIKE '%$sanitized_query%' OR 
                            sex.descripcion LIKE '%$sanitized_query%' OR 
                            s.especifique LIKE '%$sanitized_query%' OR 
                            s.edad LIKE '%$sanitized_query%' OR 
                            p.pais LIKE '%$sanitized_query%' OR 
                            s.telefono LIKE '%$sanitized_query%' OR 
                            s.correo LIKE '%$sanitized_query%' OR 
                            s.domicilio LIKE '%$sanitized_query%')";
}

// Extrae la informacion de la base de datos
$sql = "
SELECT
  s.id_students   AS ID,
  s.nombre        AS Nombre,
  DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
  DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion
FROM student s
JOIN sexo sex ON s.id_sexo = sex.id_sexo
JOIN paises p ON s.id_paises = p.id_paises
$where_condition
ORDER BY s.id_students
";

$result = $conn->query($sql);

// Crear un array con los datos
$students_data = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $students_data[] = $row;
  }
}

$conn->close();

// Funciones de exportación
function exportToCSV($data, $filename) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename . '_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Encabezados
    if (count($data) > 0) {
        fputcsv($output, array_keys($data[0]));
    }
    
    // Datos
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
}

function exportToExcel($data, $filename) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=' . $filename . '_' . date('Y-m-d') . '.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo '<table border="1">';
    
    // Encabezados
    if (count($data) > 0) {
        echo '<tr>';
        foreach (array_keys($data[0]) as $header) {
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr>';
    }
    
    // Datos
    foreach ($data as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo '<td>' . htmlspecialchars($cell) . '</td>';
        }
        echo '</tr>';
    }
    
    echo '</table>';
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" media="screen" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Base de datos de Estudiantes</title>
</head>

<body>
  <div class="container-fluid">
    <div class="user-header">
      <h2>Usuario: <strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></h2>
      <a href='login.php?action=logout'>Cerrar sesión</a>
    </div>

    <div class="text-center mb-3">
      <a href='Registro.php' class='btn btn-success new-student-btn'>Registrar nuevo estudiante</a>
    </div>

    <?php if (!empty($search_query)): ?>
      <div class="text-center mb-3">
        <p>Mostrando resultados similares a: <strong><?php echo htmlspecialchars($search_query); ?></strong></p>
      </div>
    <?php endif; ?>

    <table id="students-table" data-toggle="table" data-data='<?php echo json_encode($students_data); ?>'
      data-search="true" data-show-refresh="true" data-show-fullscreen="true" data-show-columns="true"
      data-show-columns-toggle-all="true" data-show-export="<?php echo $permiso_exportar ? 'true' : 'false'; ?>"
      data-export-types="['csv', 'excel']" data-export-options='{"fileName": "estudiantes"}'
      data-pagination="true" data-page-size="10" data-page-list="[10, 25, 50, 100, all]" data-locale="es-ES"
      data-buttons-class="primary" data-icons-prefix="bi">
      <thead>
        <tr>
          <th data-field="ID" data-sortable="true">ID</th>
          <th data-field="Nombre" data-sortable="true">Nombre</th>
          <th data-field="Fecha_Registro" data-sortable="true">Fecha Registro</th>
          <th data-field="Fecha_Edicion" data-sortable="true">Fecha Edición</th>
          <th data-field="ver" data-formatter="verFormatter" data-class="text-center">Ver</th>
          <th data-field="editar" data-formatter="editarFormatter" data-class="text-center">Editar</th>
          <th data-field="eliminar" data-formatter="eliminarFormatter" data-class="text-center">Eliminar</th>
        </tr>
      </thead>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/locale/bootstrap-table-es-ES.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>

  <script>
    // Columnas de acción
    function verFormatter(value, row) {
      return `
        <button class='btn-action view-btn' data-id='${row.ID}' title='Ver detalles'>
          <img src='./templates/magnifying.png' width='20' alt='Ver detalles'>
        </button>
      `;
    }

    function editarFormatter(value, row) {
      <?php if ($permiso_editar): ?>
        return `
          <a href='editar_registro.php?id=${row.ID}' class='btn-action' title='Editar registro'>
            <img src='./templates/edit.svg' width='20' alt='Editar registro'>
          </a>
        `;
      <?php else: ?>
        return `
          <span class='btn-action disabled' title='Sin permisos para editar'>
            <img src='./templates/edit.svg' width='20' alt='Sin permisos para editar'>
          </span>
        `;
      <?php endif; ?>
    }

    function eliminarFormatter(value, row) {
      <?php if ($permiso_borrar): ?>
        return `
          <button class='btn-action delete-btn' data-id='${row.ID}' title='Eliminar registro'>
            <img src='./templates/delete.svg' width='20' alt='Eliminar registro'>
          </button>
        `;
      <?php else: ?>
        return `
          <span class='btn-action disabled' title='Sin permisos para eliminar'>
            <img src='./templates/delete.svg' width='20' alt='Sin permisos para eliminar'>
          </span>
        `;
      <?php endif; ?>
    }

    // Eliminar registro con AJAX
    function eliminarRegistro(id) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {

          Swal.fire({
            title: 'Eliminando registro',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          //  AJAX para eliminar
          fetch(`gestion.php?action=eliminar&id=${id}&confirm=true`)
            .then(response => response.json())
            .then(data => {
              if (data.success) {

                Swal.fire({
                  title: '¡Eliminado!',
                  text: data.message || 'El registro ha sido eliminado correctamente.',
                  icon: 'success',
                  confirmButtonText: 'Aceptar'
                }).then(() => {
                  // Recargar la tabla
                  $('#students-table').bootstrapTable('refresh', {
                    silent: true,
                    url: window.location.href
                  });
                });
              } else {

                Swal.fire({
                  title: 'Error',
                  text: data.message || 'Ocurrió un error al eliminar el registro.',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
                });
              }
            })
            .catch(error => {
              Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al eliminar el registro.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
              });
            });
        }
      });
    }

    // Función para ver detalles del estudiante
    function verDetalles(id) {
      // Ajax para ver detalles
      fetch(`obtener_detalles.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            return;
          }

          let especifique = data.Especifique ? `<br><strong>Especifique:</strong> ${data.Especifique}` : '';

          Swal.fire({
            title: `Detalles del Estudiante: ${data.Nombre}`,
            html: `
              <div style="text-align: left;">
                <p><strong>ID:</strong> ${data.ID}</p>
                <p><strong>Nombre:</strong> ${data.Nombre}</p>
                <p><strong>Sexo:</strong> ${data.Sexo}${especifique}</p>
                <p><strong>Edad:</strong> ${data.Edad}</p>
                <p><strong>Fecha de Nacimiento:</strong> ${data.Fecha_Nacimiento}</p>
                <p><strong>País:</strong> ${data.Pais}</p>
                <p><strong>Teléfono:</strong> ${data.Telefono}</p>
                <p><strong>Correo:</strong> ${data.Correo}</p>
                <p><strong>Domicilio:</strong> ${data.Domicilio}</p>
                <p><strong>Fecha de Registro:</strong> ${data.Fecha_Registro}</p>
                <div style="text-align: center; margin-top: 15px;">
                  <img src="${data.Foto}" width="120" height="150" style="object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                  <div>
                    <a href="${data.Lista}" download class="action-btn">Descargar Lista</a>
                    <a href="${data.Excel}" download class="action-btn">Descargar Excel</a>
                    <a href="student_information.php?id=${data.ID}&export=csv" download class="action-btn">Descargar Registro</a>
                  </div>
                </div>
              </div>
            `,
            width: 600,
            showCloseButton: true,
            showConfirmButton: false
          });
        })
        .catch(error => {
          Swal.fire('Error', 'No se pudieron cargar los detalles del estudiante', 'error');
        });
    }


    $(document).on('post-body.bs.table', '#students-table', function () {
      // Botones de eliminar
      $('.delete-btn').on('click', function () {
        const id = $(this).data('id');
        eliminarRegistro(id);
      });

      // Botones de ver
      $('.view-btn').on('click', function () {
        const id = $(this).data('id');
        verDetalles(id);
      });
    });

    // Recarga la tabla
    $(document).on('click', '.fixed-table-toolbar .refresh', function () {
      $('#students-table').bootstrapTable('refresh', {
        url: window.location.href
      });
    });
  </script>
</body>

</html>