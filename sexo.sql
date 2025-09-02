CREATE OR REPLACE VIEW vw_estudiantes_sexo AS

SELECT * FROM vw_estudiantes

-- WHERE Sexo = 'Masculino';

WHERE Sexo = 'Femenino';

-- WHERE Sexo = 'Otro';
