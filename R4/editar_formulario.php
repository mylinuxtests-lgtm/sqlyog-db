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

// Obtiene los datos del estudiante
$sql = "SELECT * FROM student WHERE id_students = $student_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Estudiante no encontrado");
}

$student = $result->fetch_assoc();

// Obtiene el sexo y país
$sexo_map = [
    1 => 'Hombre',
    2 => 'Mujer',
    3 => 'Otro'
];

$paises_map = [
    1 => 'Alemania',
    2 => 'Brazil',
    3 => 'Canada',
    4 => 'China',
    5 => 'Estados Unidos',
    6 => 'India',
    7 => 'Indonesia',
    8 => 'Japon',
    9 => 'Mexico',
    10 => 'Rusia'
];

$sexo_valor = $sexo_map[$student['id_sexo']] ?? 'Hombre';
$pais_valor = $paises_map[$student['id_paises']] ?? 'Mexico';
$especifique_valor = $student['especifique'] ?? "";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" media="screen" />
    <title>Editar Formulario</title>
</head>

<body>
    <form action="actualizar_formulario.php" method="post" enctype="multipart/form-data" autocomplete="off"
        class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?php echo $student_id; ?>">
        <div class="form-container">
            <h2>Editar información</h2>
            <h2><span class="error">* Campo Obligatorio</span></h2>
        </div>
        <input type="hidden" id="fecha_respuesta" name="fecha_respuestas" value="0" />
        <div class="form-box">
            <label for="nombre">
                Nombre completo: <span class="error">*</span><br /></label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($student['nombre']); ?>" required /><br /><br />
        </div>
        <div class="form-box">
            <label for="sexo"> Sexo: <span class="error">*</span><br /></label>
            <br /><br />

            <label class="radio-label">
                <input type="radio" id="S1" name="sexo" value="Hombre" <?php echo ($sexo_valor == 'Hombre') ? 'checked' : ''; ?> required />
                <span class="radio-text">Hombre</span> </label><br />

            <label class="radio-label">
                <input type="radio" id="S2" name="sexo" value="Mujer" <?php echo ($sexo_valor == 'Mujer') ? 'checked' : ''; ?> />
                <span class="radio-text">Mujer</span> </label><br />

            <label class="radio-label">
                <input type="radio" id="S3" name="sexo" value="Otro" <?php echo ($sexo_valor == 'Otro') ? 'checked' : ''; ?> />
                <span class="radio-text">Otro</span> </label><br />

            <label id="labelOtro" for="especifique" class="<?php echo ($sexo_valor == 'Otro') ? '' : 'oculto'; ?>">
                Especifique:
            </label>
            <input type="text" class="<?php echo ($sexo_valor == 'Otro') ? '' : 'oculto'; ?>" id="especifique"
                name="especifique" value="<?php echo htmlspecialchars($especifique_valor); ?>" <?php echo ($sexo_valor == 'Otro') ? 'required' : ''; ?> /><br />
        </div>
        <div class="form-box">
            <label for="age"> Edad: <span class="error">*</span><br /></label>
            <input type="number" id="age" name="age" min="1" max="99" value="<?php echo $student['edad']; ?>"
                required /><br /><br />
        </div>
        <div class="form-box">
            <label for="bday">
                Fecha de Nacimiento: <span class="error">*</span><br /></label>
            <input type="date" id="bday" name="bday" value="<?php echo $student['nacimiento']; ?>"
                required /><br /><br />
        </div>
        <div class="form-box">
            <label for="country" class="col-sm-3 control-label">Nacionalidad: <span class="error">*</span><br /></label>
            <div class="col-12">
                <select id="country" name="country" class="form-control">
                    <option value="Alemania" <?php echo ($pais_valor == 'Alemania') ? 'selected' : ''; ?>>Alemania
                    </option>
                    <option value="Brazil" <?php echo ($pais_valor == 'Brazil') ? 'selected' : ''; ?>>Brazil</option>
                    <option value="Canada" <?php echo ($pais_valor == 'Canada') ? 'selected' : ''; ?>>Canada</option>
                    <option value="China" <?php echo ($pais_valor == 'China') ? 'selected' : ''; ?>>China</option>
                    <option value="Estados Unidos" <?php echo ($pais_valor == 'Estados Unidos') ? 'selected' : ''; ?>>
                        Estados Unidos</option>
                    <option value="India" <?php echo ($pais_valor == 'India') ? 'selected' : ''; ?>>India</option>
                    <option value="Indonesia" <?php echo ($pais_valor == 'Indonesia') ? 'selected' : ''; ?>>Indonesia
                    </option>
                    <option value="Japon" <?php echo ($pais_valor == 'Japon') ? 'selected' : ''; ?>>Japon</option>
                    <option value="Mexico" <?php echo ($pais_valor == 'Mexico') ? 'selected' : ''; ?>>Mexico</option>
                    <option value="Rusia" <?php echo ($pais_valor == 'Rusia') ? 'selected' : ''; ?>>Rusia</option>
                </select>
            </div>
        </div>
        <div class="form-box">
            <label for="photo">
                Sube tu foto: <span class="error">*</span><br />
            </label>
            <input type="file" id="photo" name="photo" accept="image/*" /><br /><br />
            <small>Foto actual:</small><br />
            <img src="<?php echo $student['foto']; ?>" alt="Foto actual" width="100" height="150" /><br /><br />
        </div>
        <div class="form-box">
            <label for="phone"> Telefono: <span class="error">*</span><br /></label>
            <input type="tel" id="phone" name="phone" placeholder="000-0000000" pattern="[0-9]{3}-[0-9]{7}"
                maxlength="11" value="<?php echo htmlspecialchars($student['telefono']); ?>" required /><br /><br />
            <small>Debe ser 10 digitos, como el siguiente formato: 473-1111111</small><br /><br />
        </div>
        <div class="form-box">
            <label for="correo"> Correo: <span class="error">*</span><br /></label>
            <input type="email" id="correo" name="correo" placeholder="ejemplo@hotmail.com" aria-label="correo"
                aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($student['correo']); ?>"
                required /><br /><br />
        </div>
        <div class="form-box">
            <label for="domicilio">
                Domicilio: <span class="error">*</span><br />
            </label>
            <textarea id="domicilio" name="domicilio" rows="3" cols="30"
                required><?php echo htmlspecialchars($student['domicilio']); ?></textarea><br /><br />
        </div>
        <div class="form-box">
            <label for="list"> Listado <span class="error">*</span><br /> </label>
            <input type="file" id="list" name="list" accept=".txt" /><br /><br />
            <small>Archivo actual: <?php echo basename($student['lista']); ?></small>
        </div>
        <div class="form-box">
            <label for="excel">
                Archivo Excel <span class="error">*</span><br />
            </label>
            <input type="file" id="excel" name="excel" accept=".xlsx, .csv" /><br /><br />
            <small>Archivo actual: <?php echo basename($student['excel']); ?></small>
        </div>
        <div class="col-12">
            <div class="form-check">
                <label class="form-check-label" for="invalidCheck">
                    Confirmas que estas de acuerdo con los terminos y condiciones.
                    <span class="error">*</span>
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required />
                </label>
                <small>Debes aceptar antes de finalizar </small>
            </div>
        </div>
        <input type="submit" value="Actualizar información" />
        <input type="reset" value="Restablecer cambios" />
    </form>
    <br /><br />

    <script>
        const radioOtro = document.getElementById("S3");
        const campoEspecifique = document.getElementById("especifique");
        const labelEspecifique = document.getElementById("labelOtro");

        // Mostrar/ocultar campo de especificación
        radioOtro.addEventListener("change", function () {
            if (radioOtro.checked) {
                campoEspecifique.classList.remove("oculto");
                labelEspecifique.classList.remove("oculto");
                campoEspecifique.setAttribute("required", "true");
            } else {
                campoEspecifique.classList.add("oculto");
                labelEspecifique.classList.add("oculto");
                campoEspecifique.removeAttribute("required");
            }
        });

        // Si ya está seleccionado "Otro", mostrar el campo
        if (radioOtro.checked) {
            campoEspecifique.classList.remove("oculto");
            labelEspecifique.classList.remove("oculto");
        }

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
    </script>
</body>

</html>
<?php
$conn->close();
?>