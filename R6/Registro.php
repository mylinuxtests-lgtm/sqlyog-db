<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conn.php';

    $conn = new mysqli($servername, $username, $db_password, $dbname, $port);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Obtener datos del formulario
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
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes</title>
    <link rel="stylesheet" href="style.css" media="screen" />
</head>

<body>
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="user-header">
            <h2>Usuario: <strong><?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong></h2>
            <a href="login.php?action=logout">Cerrar sesión</a>
        </div>

        <form action="Registro.php" method="post" enctype="multipart/form-data" autocomplete="off"
            class="needs-validation" novalidate>
            <div class="form-container">
                <h2>Por favor, completa todos los campos</h2>
                <h2><span class="error">* Campo Obligatorio</span></h2>
            </div>

            <div class="form-box">
                <label for="nombre">Nombre completo: <span class="error">*</span><br /></label>
                <input type="text" class="form-control" id="nombre" name="nombre" required /><br /><br />
            </div>

            <div class="form-box">
                <label for="sexo"> Sexo: <span class="error">*</span><br /></label>
                <br /><br />

                <label class="radio-label">
                    <input type="radio" id="S1" name="sexo" value="Masculino" required />
                    <span class="radio-text">Masculino</span> </label><br />

                <label class="radio-label">
                    <input type="radio" id="S2" name="sexo" value="Femenino" />
                    <span class="radio-text">Femenino</span> </label><br />

                <label class="radio-label">
                    <input type="radio" id="S3" name"sexo" value="Otro" />
                    <span class="radio-text">Otro</span> </label><br />

                <label id="labelOtro" for="especifique" class="oculto">
                    Especifique:
                </label>
                <input type="text" class="oculto" id="especifique" name="especifique" /><br />
            </div>

            <div class="form-box">
                <label for="age"> Edad: <span class="error">*</span><br /></label>
                <input type="number" id="age" name="age" min="1" max="99" required /><br /><br />
            </div>

            <div class="form-box">
                <label for="bday">Fecha de Nacimiento: <span class="error">*</span><br /></label>
                <input type="date" id="bday" name="bday" required /><br /><br />
            </div>

            <div class="form-box">
                <label for="country" class="col-sm-3 control-label">Nacionalidad: <span
                        class="error">*</span><br /></label>
                <div class="col-12">
                    <select id="country" name="country" class="form-control" onchange="actualizarFormatoTelefono()">
                        <option value="Alemania">Alemania</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Canada">Canada</option>
                        <option value="China">China</option>
                        <option value="Estados Unidos">Estados Unidos</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Japon">Japon</option>
                        <option value="Mexico" selected>Mexico</option>
                        <option value="Rusia">Rusia</option>
                    </select>
                </div>
            </div>

            <div class="form-box">
                <label for="photo">Sube tu foto: <span class="error">*</span><br /></label>
                <div class="photo-container">
                    <div class="photo-upload">
                        <input type="file" id="photo" name="photo" accept="image/*" required />
                    </div>
                    <div class="photo-example">
                        <label for="example"> Ejemplo </label> <br />
                        <img src="templates/images.jpg" alt="Ejemplo" width="150" height="200" />
                    </div>
                </div>
            </div>

            <div class="form-box">
                <label for="phone"> Teléfono: <span class="error">*</span><br /></label>
                <input type="tel" id="phone" name="phone" placeholder="00-0000000000" pattern="[0-9]{1,2}-[0-9]{5,13}"
                    maxlength="15" required /><br /><br />
                <small id="phoneHelp">Debe seguir el siguiente formato: extensión-número (ej:
                    00-123456789)</small><br /><br />
            </div>

            <div class="form-box">
                <label for="correo"> Correo: <span class="error">*</span><br /></label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@hotmail.com" aria-label="correo"
                    aria-describedby="basic-addon1" required /><br /><br />
            </div>

            <div class="form-box">
                <label for="domicilio">Domicilio: <span class="error">*</span><br /></label>
                <textarea id="domicilio" name="domicilio" rows="3" cols="30" required></textarea><br /><br />
            </div>

            <div class="form-box">
                <label for="list"> Listado <span class="error">*</span><br /> </label>
                <input type="file" id="list" name="list" accept=".txt" required /><br /><br />
                <small>Extensiones admitidas: .txt</small><br /><br />
            </div>

            <div class="form-box">
                <label for="excel">Archivo Excel <span class="error">*</span><br /></label>
                <input type="file" id="excel" name="excel" accept=".xlsx, .csv" required /><br /><br />
                <small>Extensiones admitidas: .csv, .xlsx</small><br /><br />
            </div>

            <div class="col-12">
                <div class="form-check">
                    <label class="form-check-label" style="color:rgba(0, 0, 0, 1);" for="invalidCheck">
                        Confirmas que la informacion es veraz y correcta.
                        <span class="error">*</span>
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required />
                    </label>
                    <small style="color:rgba(0, 0, 0, 1);">Debes aceptar antes de finalizar</small>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" value="Registrar">
                <input type="reset" value="Restablecer">
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="students.php" class="form-group">Ver estudiantes registrados</a>
            </div>
    </div>
    </form>




    <script>
        // Mostrar campo de especificación cuando se selecciona "Otro"
        document.querySelectorAll('input[name="sexo"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const campoEspecifique = document.getElementById('especifique');
                const labelEspecifique = document.getElementById('labelOtro');

                if (this.value === 'Otro') {
                    campoEspecifique.classList.remove('oculto');
                    labelEspecifique.classList.remove('oculto');
                    campoEspecifique.setAttribute('required', 'true');
                } else {
                    campoEspecifique.classList.add('oculto');
                    labelEspecifique.classList.add('oculto');
                    campoEspecifique.removeAttribute('required');
                }
            });
        });

        // Validación del formulario
        (function () {
            "use strict";

            const forms = document.querySelectorAll(".needs-validation");

            Array.from(forms).forEach(function (form) {
                form.addEventListener(
                    "submit",
                    function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        })();

        // Formatos de teléfono por país
        const phoneFormats = {
            Alemania: {
                pattern: "[0-9]{2}-[0-9]{6,13}",
                placeholder: "49-12345678",
                example: "Código de área-número (ex: 49-12345678)",
            },
            Brazil: {
                pattern: "[0-9]{2}-[0-9]{10}",
                placeholder: "55-912345678",
                example: "Código de área-número (ex: 55-912345678)",
            },
            Canada: {
                pattern: "[0-9]{1}-[0-9]{10}",
                placeholder: "1-1234567",
                example: "Código de área-número (ex: 1-1234567)",
            },
            China: {
                pattern: "[0-9]{2}-[0-9]{5,12}",
                placeholder: "86-12345678",
                example: "Código de área-número (ex: 86-12345678)",
            },
            "Estados Unidos": {
                pattern: "[0-9]{1}-[0-9]{10}",
                placeholder: "1-5550123",
                example: "Código de área-número (ex: 1-5550123)",
            },
            India: {
                pattern: "[0-9]{2}-[0-9]{7,10}",
                placeholder: "91-23456789",
                example: "Código STD-número (ex: 91-23456789)",
            },
            Indonesia: {
                pattern: "[0-9]{2}-[0-9]{5,10}",
                placeholder: "62-12345678",
                example: "Código de área-número (ex: 62-1234567)",
            },
            Japon: {
                pattern: "[0-9]{2}-[0-9]{5,13}",
                placeholder: "81-12345678",
                example: "Código de área-número (ex: 81-12345678)",
            },
            Mexico: {
                pattern: "[0-9]{2}-[0-9]{10}",
                placeholder: "52-12345678",
                example: "Lada-número (ex: 52-12345678)",
            },
            Rusia: {
                pattern: "[0-9]{1}-[0-9]{10}",
                placeholder: "7-1234567",
                example: "Código-número (ex: 7-1234567)",
            },
        };

        // Función para actualizar el formato del teléfono según el país
        function actualizarFormatoTelefono() {
            const countrySelect = document.getElementById("country");
            const phoneInput = document.getElementById("phone");
            const phoneHelp = document.getElementById("phoneHelp");

            const selectedCountry = countrySelect.value;
            const format = phoneFormats[selectedCountry] || phoneFormats["Mexico"];

            phoneInput.pattern = format.pattern;
            phoneInput.placeholder = format.placeholder;
            phoneInput.value = "";

            phoneHelp.textContent = `Debe seguir el siguiente formato: ${format.example}`;
        }

        document.getElementById("country").addEventListener("change", actualizarFormatoTelefono);

        document.addEventListener("DOMContentLoaded", function () {
            actualizarFormatoTelefono();
        });
    </script>
</body>

</html>