-- Empresa		 : Municipalidad Distrital de La Esperanza
-- Producto		 : Sistema de Gestion de Incidencias Informaticas
-- Software		 : Sistema de Incidencias Informaticas
-- DBMS			 : SQL Server
-- Base de datos : SISTEMA_INCIDENCIAS
-- Responsable	 : Subgerente de informatica y Sistemas - SGIS
--				   jhonatanmm.1995@gmail.com
-- Repositorio	 : https://github.com/GAMM95/helpdeskMDE/tree/main/config/sql
-- Creado por	 : Jhonatan Mantilla Mi�ano
--		         : 16 de mayo del 2024

USE BD_INCIDENCIAS;
GO

-------------------------------------------------------------------------------------------------------
  -- VISTAS
-------------------------------------------------------------------------------------------------------

-- VISTA LISTAR USUARIOS
CREATE VIEW vista_usuarios AS
SELECT USU_codigo, (p.PER_nombres + ' ' + p.PER_apellidoPaterno + ' '+ p.PER_apellidoMaterno) as persona,
a.ARE_nombre, a.EST_codigo, USU_nombre, USU_password, r.ROL_nombre, e.EST_descripcion 
FROM USUARIO u
INNER JOIN PERSONA p on p.PER_codigo = u.PER_codigo 
LEFT JOIN AREA a on a.ARE_codigo = u.ARE_codigo
LEFT JOIN ESTADO e on e.EST_codigo = u.EST_codigo
LEFT JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
where USU_codigo <>0
GO


-- VISTA LISTAR INCIDENCIAS ADMINISTRADOR
CREATE VIEW vista_incidencias_administrador AS
SELECT 
  I.INC_numero,
  I.INC_numero_formato,
  (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
  I.INC_codigoPatrimonial,
  I.INC_asunto,
  I.INC_documento,
  I.INC_descripcion,
  CAT.CAT_nombre,
  A.ARE_nombre,
  CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
  END AS ESTADO,
  p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
FROM 
  INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
  LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
  LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    (I.EST_codigo IN (3) OR C.EST_codigo IN (3));
GO

--Vista para listar las incidencias recepionadas para el administrador
CREATE VIEW vista_recepciones AS
SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
	R.REC_numero,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
	IMP.IMP_descripcion,
    O.CON_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS UsuarioIncidente,
    pR.PER_nombres + ' ' + pR.PER_apellidoPaterno AS UsuarioRecepcion
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN USUARIO uR ON uR.USU_codigo = R.USU_codigo 
LEFT JOIN PERSONA pR ON pR.PER_codigo = uR.PER_codigo 
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON R.REC_numero = C.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    (EC.EST_codigo = 4 OR 
    (I.EST_codigo = 4 AND ASI.ASI_codigo IS NULL))
    AND (I.EST_codigo IN (3, 4, 5,6) OR EC.EST_codigo IN (3, 4, 5, 6));
GO

--Vista para listar las incidencias totales para el administrador
CREATE VIEW vista_incidencias_totales_administrador AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_codigoPatrimonial,
    I.INC_documento,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON R.REC_numero = C.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
GO

-- Vista para listar incidencias pendientes de cierre
CREATE VIEW vista_incidencias_pendientes AS
SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- �ltima modificaci�n (fecha y hora m�s reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON R.REC_numero = C.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    I.EST_codigo IN (3, 4) 
    AND NOT EXISTS (  
        SELECT 1 
        FROM CIERRE C2
        WHERE C2.MAN_codigo = MAN.MAN_codigo
        AND C2.EST_codigo = 5
    )
GROUP BY 
    I.INC_numero,
    INC_numero_formato,
    I.INC_fecha,
    I.INC_hora,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion,
    p.PER_nombres,
    p.PER_apellidoPaterno
GO

--Vista para listar las nuevas incidencias para el usuario
CREATE VIEW vista_incidencias_usuario AS 
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
	I.INC_descripcion,
    I.INC_codigoPatrimonial,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
	E.EST_descripcion AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3);
GO

--Vista para listar las incidencias totales para el usuario en la consulta
CREATE VIEW vista_incidencias_totales_usuario AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5);
GO

-- Vista para listar incidencias por fecha para el administrador
CREATE VIEW vista_incidencias_fecha_admin AS
SELECT 
    I.INC_numero,
    I.INC_numero_formato,
	I.INC_fecha,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5);
GO

-- Vista para listar incidencias por fecha para el usuario
CREATE VIEW vista_incidencias_fecha_user AS
SELECT 
	I.INC_numero,
    I.INC_numero_formato,
	I.INC_fecha,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10), REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
GO

--Vista para listar asignaciones
CREATE VIEW vista_asignaciones AS
SELECT 
ASI.ASI_codigo,
R.REC_numero,
I.INC_numero_formato,
(CONVERT(VARCHAR(10),ASI.ASI_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,
A.ARE_nombre,
I.INC_asunto,
I.INC_codigoPatrimonial,
p.PER_nombres + ' ' + p.PER_apellidoPaterno AS usuarioAsignado,
pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
E.EST_descripcion
FROM ASIGNACION ASI
INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo 
LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
GO

-- Vista para listar cierres
CREATE VIEW vista_cierres AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10),INC_fecha,103)) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
	CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
	PRI_nombre,
    (CONVERT(VARCHAR(10),CIE_fecha,103)) AS fechaCierreFormateada,
    CIE_asunto,
    CIE_numero,
    C.CIE_diagnostico, 
    C.CIE_recomendaciones,
    C.CIE_documento,
	O.CON_descripcion,
	u.USU_nombre,
    CASE
		WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
    PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
FROM RECEPCION R
INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON R.REC_numero = C.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5;
GO

--VISTA PARA MOSTRAR NOTIFICACIONES PARA LOS USUARIOS
CREATE VIEW vista_notificaciones_usuario AS
SELECT 
I.INC_numero,
I.INC_numero_formato,
(CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), I.INC_hora, 108)) AS fechaIncidenciaFormateada,
A.ARE_codigo,
A.ARE_nombre AS NombreAreaIncidencia,
I.INC_asunto,
C.USU_codigo,
U.USU_nombre,
C.CIE_asunto,
p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
A2.ARE_nombre AS NombreAreaCierre, 
(CONVERT(VARCHAR(10), C.CIE_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), C.CIE_hora, 108)) AS fechaCierreFormateada,
CASE
    WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
    ELSE E.EST_descripcion
END AS ESTADO,
CASE
    WHEN DATEDIFF(MINUTE, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) < 60 THEN 
        CAST(DATEDIFF(MINUTE, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' min'
    WHEN DATEDIFF(DAY, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) < 1 THEN 
        CAST(DATEDIFF(HOUR, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' h ' +
        CAST(DATEDIFF(MINUTE, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
    ELSE 
        CAST(DATEDIFF(DAY, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' d ' +
        CAST(DATEDIFF(HOUR, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) % 24 AS VARCHAR) + ' h ' +
        CAST(DATEDIFF(MINUTE, CAST(C.CIE_fecha AS DATETIME) + CAST(C.CIE_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
END AS tiempoDesdeCierre
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
LEFT JOIN USUARIO U2 ON U2.USU_codigo = C.USU_codigo 
LEFT JOIN AREA A2 ON U2.ARE_codigo = A2.ARE_codigo 
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE (I.EST_codigo NOT IN (3, 4) OR C.EST_codigo NOT IN (3, 4))
AND CONVERT(DATE, C.CIE_fecha) = CONVERT(DATE, GETDATE());
GO

--VISTA PARA MOSTRAR NOTIFICACIONES PARA EL ADMINISTRADOR 
CREATE VIEW vista_notificaciones_administrador AS
SELECT 
I.INC_numero,
(CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), I.INC_hora, 108)) AS fechaIncidenciaFormateada,
A.ARE_nombre,
I.INC_asunto,
U.USU_nombre,
p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
I.EST_codigo,
CASE
    WHEN DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 60 THEN 
        CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' min'
    WHEN DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) < 1 THEN 
        CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' h ' +
        CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
    ELSE 
        CAST(DATEDIFF(DAY, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) AS VARCHAR) + ' d ' +
        CAST(DATEDIFF(HOUR, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 24 AS VARCHAR) + ' h ' +
        CAST(DATEDIFF(MINUTE, CAST(I.INC_fecha AS DATETIME) + CAST(I.INC_hora AS DATETIME), GETDATE()) % 60 AS VARCHAR) + ' min'
END AS tiempoDesdeIncidencia
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE I.EST_codigo NOT IN (4, 5) 
AND A.ARE_codigo <> 1;
GO
