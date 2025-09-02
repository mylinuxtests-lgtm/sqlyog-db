CREATE OR REPLACE VIEW vw_estudiantes_correo AS

SELECT * FROM vw_estudiantes

WHERE 
    correo REGEXP '^[A-Za-z][A-Za-z0-9._%-]*@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$'
    AND correo NOT REGEXP '\\.[A-Za-z0-9._%-]*@'  
    AND correo != ''
    AND correo IS NOT NULL
    
ORDER BY 
    correo;