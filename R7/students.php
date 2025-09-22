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

// Exportación a CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv' && $permiso_exportar) {
  $export_sql = "
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
      DATE_FORMAT(s.fecha_registro, '%d-%m-%Y %H:%i') AS Fecha_Registro,
      DATE_FORMAT(s.fecha_edicion, '%d-%m-%Y %H:%i') AS Fecha_Edicion
    FROM student s
    JOIN sexo sex ON s.id_sexo = sex.id_sexo
    JOIN paises p ON s.id_paises = p.id_paises
    WHERE s.visible = 1
    ORDER BY s.id_students
    ";

  $export_result = $conn->query($export_sql);

  if ($export_result->num_rows > 0) {
    // Nombre del archivo
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=estudiantes_' . date('Y-m-d') . '.csv');

    // Crear archivo
    $output = fopen('php://output', 'w');

    fputcsv($output, [
      'ID',
      'Nombre',
      'Sexo',
      'Especifique',
      'Edad',
      'Fecha de Nacimiento',
      'País',
      'Teléfono',
      'Correo',
      'Domicilio',
      'Fecha de Registro',
      'Fecha de Edición'
    ]);

    while ($row = $export_result->fetch_assoc()) {
      fputcsv($output, $row);
    }

    fclose($output);
    exit();
  }
}

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

// Crear un nuevo registro
if ($result->num_rows > 0) {
  echo "<div class='user-header'>";
  echo "<h2>Usuario: <strong>" . htmlspecialchars($_SESSION["usuario"]) . "</strong></h2>";
  echo "<a href='login.php?action=logout'>Cerrar sesión</a>";
  echo "</div>";

  echo "<div style='text-align: center; margin-bottom: 20px;'>";
  echo "<a href='Registro.php' class='new-student-btn'>Registrar nuevo estudiante</a>";

  if ($permiso_exportar) {
    echo "<a href='students.php?export=csv' class='export-csv-btn'>Exportar a CSV</a>";
  }

  echo "</div>";

  // Resultados de búsqueda
  if (!empty($search_query)) {
    echo "<div style='text-align: center; margin: 10px 0;'>";
    echo "<p>Mostrando resultados similares a: <strong>" . htmlspecialchars($search_query) . "</strong></p>";
    echo "</div>";
  }
} else {
  echo "<div class='user-header'>";
  echo "<h2>Usuario: <strong>" . htmlspecialchars($_SESSION["usuario"]) . "</strong></h2>";
  echo "<a href='login.php?action=logout'>Cerrar sesión</a>";
  echo "</div>";

  echo "<div style='text-align: center; margin: 40px;'>";

  // En caso de que no existan resultados similares
  if (!empty($search_query)) {
    echo "<p>No se encontraron resultados para: <strong>" . htmlspecialchars($search_query) . "</strong></p>";
    echo "<a href='students.php'>Ver todos los registros</a><br><br>";
  } else {
    echo "<p>No se encontraron resultados.</p>";
  }

  echo "<a href='Registro.php' class='new-student-btn'>Registrar nuevo estudiante</a>";
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Base de datos de Estudiantes</title>
</head>

<body>
  <?php if ($result->num_rows > 0): ?>
    <div class="container-fluid">
      <div id="toolbar">
        <button id="refreshBtn" class="refresh-btn">
          <i class="bi bi-arrow-clockwise"></i> Actualizar Tabla
        </button>
      </div>
      <table id="studentsTable" data-toggle="table" data-url="gestion.php?action=obtener_datos" data-pagination="true"
        data-page-size="10" data-search="true" data-show-refresh="false" data-show-columns="true" data-toolbar="#toolbar"
        data-query-params="queryParams" data-side-pagination="server" data-response-handler="responseHandler">
        <thead>
          <tr>
            <th data-field="ID" data-sortable="true">ID</th>
            <th data-field="Nombre" data-sortable="true">Nombre</th>
            <th data-field="Fecha_Registro" data-sortable="true">Fecha Registro</th>
            <th data-field="Fecha_Edicion" data-sortable="true">Fecha Edición</th>
            <th data-field="ver" data-formatter="verFormatter" data-width="100">Ver</th>
            <th data-field="editar" data-formatter="editarFormatter" data-width="100">Editar</th>
            <th data-field="eliminar" data-formatter="eliminarFormatter" data-width="100">Eliminar</th>
          </tr>
        </thead>
      </table>
    </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/locale/bootstrap-table-es-ES.min.js"></script>

  <script>
    function queryParams(params) {
      return {
        limit: params.limit,
        offset: params.offset,
        search: params.search,
        sort: params.sort,
        order: params.order
      };
    }

    function responseHandler(res) {
      if (res.rows) {
        return {
          total: res.total,
          rows: res.rows
        };
      }

      return {
        total: res.length,
        rows: res
      };
    }

    // VBotón Ver
    function verFormatter(value, row, index) {
      return `<button class="btn-table view-btn" data-id="${row.ID}" title="Ver detalles">
                 <img src="./templates/magnifying.png" width="20" alt="Ver detalles">
               </button>`;
    }

    // Botón Editar
    function editarFormatter(value, row, index) {
      const permisoEditar = <?php echo $permiso_editar ? 'true' : 'false'; ?>;

      if (permisoEditar) {
        return `<a href="editar_registro.php?id=${row.ID}" class="btn-table" title="Editar registro">
                   <img src="./templates/edit.svg" width="20" alt="Editar registro">
                 </a>`;
      } else {
        return `<span class="btn-table disabled" title="Sin permisos para editar">
                   <img src="./templates/edit.svg" width="20" alt="Sin permisos para editar">
                 </span>`;
      }
    }

    // Botón Eliminar
    function eliminarFormatter(value, row, index) {
      const permisoBorrar = <?php echo $permiso_borrar ? 'true' : 'false'; ?>;

      if (permisoBorrar) {
        return `<button class="btn-table delete-btn" data-id="${row.ID}" title="Eliminar registro">
                   <img src="./templates/delete.svg" width="20" alt="Eliminar registro">
                 </button>`;
      } else {
        return `<span class="btn-table disabled" title="Sin permisos para eliminar">
                   <img src="./templates/delete.svg" width="20" alt="Sin permisos para eliminar">
                 </span>`;
      }
    }

    // Eliminar registro
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
          // AJAX para eliminar
          fetch(`gestion.php?action=eliminar&id=${id}&confirm=true`)
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Mostrar confirmación
                Swal.fire({
                  title: 'Eliminado',
                  text: 'El registro ha sido eliminado correctamente',
                  icon: 'success',
                  confirmButtonText: 'Aceptar'
                });

                // Actualizar la tabla
                $('#studentsTable').bootstrapTable('refresh');
              } else {
                Swal.fire('Error', data.error || 'Error al eliminar el registro', 'error');
              }
            })
            .catch(error => {
              Swal.fire('Error', 'Error al eliminar el registro', 'error');
            });
        }
      });
    }

    // Ver detalles del estudiante
    function verDetalles(id) {
      // AJAX para obtener detalles
      fetch(`gestion.php?action=obtener_detalles&id=${id}`)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            Swal.fire('Error', data.error, 'error');
            return;
          }

          let especifique = data.Especifique ? `<br><strong>Especifique:</strong> ${data.Especifique}` : '';

          let downloadBtn = `<a href="gestion.php?id=${data.ID}&export=csv" class="download-btn">Exportar Registro</a>`;

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
                    <a href="${data.Lista}" download class="download-btn">Descargar Lista</a>
                    <a href="${data.Excel}" download class="download-btn">Descargar Excel</a>
                    ${downloadBtn}
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

    // Event listeners
    $(document).ready(function () {
      $('#studentsTable').bootstrapTable({
        locale: 'es-ES'
      });

      // Botón de actualizar tabla
      $('#refreshBtn').on('click', function () {
        $('#studentsTable').bootstrapTable('refresh');
        Swal.fire({
          title: 'Tabla actualizada',
          icon: 'success',
          timer: 1000,
          showConfirmButton: false
        });
      });

      $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        eliminarRegistro(id);
      });

      $(document).on('click', '.view-btn', function () {
        const id = $(this).data('id');
        verDetalles(id);
      });
    });
  </script>
</body>

</html>