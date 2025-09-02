CREATE OR REPLACE VIEW vw_estudiantes AS

SELECT 
    s.id_students AS ID,
    s.nombre AS Nombre,
    sex.descripcion AS Sexo,
    s.edad AS Edad,
    s.nacimiento AS Fecha_Nacimiento,
    p.pais AS País,
    s.telefono AS Teléfono,
    s.correo AS Correo,
    s.domicilio AS Domicilio,
    s.foto AS Foto,
    s.lista AS Lista,
    s.excel AS Excel
    
FROM 
    student s
    
JOIN 
    sexo sex ON s.id_sexo = sex.id_sexo
    
JOIN 
    paises p ON s.id_paises = p.id_paises;
