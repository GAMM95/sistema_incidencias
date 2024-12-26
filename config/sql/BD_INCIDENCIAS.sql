-- Empresa		 : Municipalidad Distrital de La Esperanza
-- Producto		 : Sistema de Gestion de Incidencias Informaticas
-- Software		 : Sistema de Incidencias Informaticas
-- DBMS			 : SQL Server
-- Base de datos : SISTEMA_INCIDENCIAS
-- Responsable	 : Subgerente de informatica y Sistemas - SGIS
--				   jhonatanmm.1995@gmail.com
-- Repositorio	 : https://github.com/GAMM95/helpdeskMDE/tree/main/config/sql
-- Creado por	 : Jhonatan Mantilla Miñano
--		         : 16 de mayo del 2024

-------------------------------------------------------------------------------------------------------
  -- CREACION DE LA BASE DE DATOS
-------------------------------------------------------------------------------------------------------
USE master;
GO

IF EXISTS (SELECT name FROM sys.databases WHERE name = 'BD_INCIDENCIAS')
BEGIN
    DROP DATABASE BD_INCIDENCIAS;
END
GO

CREATE DATABASE BD_INCIDENCIAS;
GO

USE BD_INCIDENCIAS;
GO

-------------------------------------------------------------------------------------------------------
  -- CREACION DE LAS TABLAS
-------------------------------------------------------------------------------------------------------
-- CREACION DE LA TABLA ROL
CREATE TABLE ROL (
	ROL_codigo SMALLINT IDENTITY(1, 1),
	ROL_nombre VARCHAR(20),
	CONSTRAINT pk_rol PRIMARY KEY (ROL_codigo)
);
GO

-- CREACION DE LA TABLA ESTADO
CREATE TABLE ESTADO (
	EST_codigo SMALLINT IDENTITY(1, 1),
	EST_descripcion VARCHAR(20),
	CONSTRAINT pk_estado PRIMARY KEY (EST_codigo)
);
GO

-- CREACION DE LA TABLA PERSONA
CREATE TABLE PERSONA (
	PER_codigo SMALLINT IDENTITY (1,1) NOT NULL,
	PER_dni CHAR(8) NOT NULL,
	PER_nombres VARCHAR(20) NOT NULL,
	PER_apellidoPaterno VARCHAR(15) NOT NULL,
	PER_apellidoMaterno VARCHAR(15) NOT NULL,
	PER_celular CHAR(9) NULL,
	PER_email VARCHAR(45) NULL,
	CONSTRAINT pk_persona PRIMARY KEY (PER_codigo),
	CONSTRAINT uq_dniPersona UNIQUE (PER_dni)
);
GO

-- Modificar la tabla AREA si es necesario
CREATE TABLE AREA (
    ARE_codigo SMALLINT NOT NULL,
    ARE_nombre VARCHAR(100) NOT NULL,
    EST_codigo SMALLINT NOT NULL,
    CONSTRAINT pk_area PRIMARY KEY (ARE_codigo)
);
GO

-- CREACION DE LA TABLA USUARIO
CREATE TABLE USUARIO (
    USU_codigo SMALLINT NOT NULL,
    USU_nombre NVARCHAR(50) UNIQUE NOT NULL,
    USU_password VARBINARY(64) NOT NULL,
    USU_salt UNIQUEIDENTIFIER NOT NULL,
    PER_codigo SMALLINT NULL,
    ROL_codigo SMALLINT NOT NULL,
    ARE_codigo SMALLINT NOT NULL,
    EST_codigo SMALLINT NOT NULL,
    USU_ultimo_acceso DATETIME,
    USU_fecha_creacion DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT pk_usuario PRIMARY KEY (USU_codigo),
    CONSTRAINT fk_persona_usuario FOREIGN KEY (PER_codigo) REFERENCES PERSONA(PER_codigo),
    CONSTRAINT fk_rol_usuario FOREIGN KEY (ROL_codigo) REFERENCES ROL(ROL_codigo),
    CONSTRAINT fk_area_usuario FOREIGN KEY (ARE_codigo) REFERENCES AREA(ARE_codigo)
);
GO

-- Crear una secuencia para generar USU_codigo a partir de 0
CREATE SEQUENCE seq_usuario_codigo
    AS INT
    START WITH 1
    INCREMENT BY 1;
GO

-- Índices adicionales para mejorar el rendimiento
CREATE INDEX idx_usuario_nombre ON USUARIO(USU_nombre);
CREATE INDEX idx_usuario_persona ON USUARIO(PER_codigo);
GO

 -- CREACION DE LA TABLA PRIORIDAD
CREATE TABLE PRIORIDAD(
	PRI_codigo SMALLINT IDENTITY(1, 1),
	PRI_nombre VARCHAR(15) NOT NULL,
	CONSTRAINT pk_prioridad PRIMARY KEY(PRI_codigo),
	CONSTRAINT uq_nombrePrioridad UNIQUE (PRI_nombre)
);
GO

-- CREACION DE LA TABLA CATEGORIA
CREATE TABLE CATEGORIA (
	CAT_codigo SMALLINT IDENTITY(1, 1),
	CAT_nombre VARCHAR(60) NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_categoria PRIMARY KEY(CAT_codigo),
	CONSTRAINT uq_nombreCategoria UNIQUE (CAT_nombre)
);
GO

-- CREACION DE TABLA IMPACTO
CREATE TABLE IMPACTO (
	IMP_codigo SMALLINT IDENTITY(1, 1),
	IMP_descripcion VARCHAR(20) NOT NULL,
	CONSTRAINT pk_impacto PRIMARY KEY (IMP_codigo)
);
GO

-- CREACIÓN DE LA TABLA BIEN
CREATE TABLE BIEN (
    BIE_codigo SMALLINT IDENTITY(1,1),
    BIE_codigoIdentificador VARCHAR(12) NULL,
    BIE_nombre VARCHAR(100) NULL,
	  EST_codigo SMALLINT NOT NULL,
    CONSTRAINT pk_bien PRIMARY KEY (BIE_codigo),
    CONSTRAINT uk_codigoIdentificador UNIQUE (BIE_codigoIdentificador)
);
GO

-- CREACIÓN DE LA TABLA INCIDENCIA
CREATE TABLE INCIDENCIA (
    INC_numero SMALLINT NOT NULL,
    INC_numero_formato VARCHAR(20) NULL,
    INC_fecha DATE NOT NULL,
    INC_hora TIME NOT NULL,
    INC_asunto VARCHAR(500) NOT NULL,
    INC_descripcion VARCHAR(800) NULL,
    INC_documento VARCHAR(500) NOT NULL,
    INC_codigoPatrimonial CHAR(12) NULL, 
    EST_codigo SMALLINT NOT NULL,
    CAT_codigo SMALLINT NOT NULL,
    ARE_codigo SMALLINT NOT NULL,
    USU_codigo SMALLINT NOT NULL,
    CONSTRAINT pk_incidencia PRIMARY KEY (INC_numero),
    CONSTRAINT fk_categoria_incidencia FOREIGN KEY (CAT_codigo) REFERENCES CATEGORIA (CAT_codigo),
    CONSTRAINT fk_area_incidencia FOREIGN KEY (ARE_codigo) REFERENCES AREA (ARE_codigo),
    CONSTRAINT fk_usuario_incidencia FOREIGN KEY (USU_codigo) REFERENCES USUARIO (USU_codigo)
);
GO

-- CREACION DE TABLA RECEPCION
CREATE TABLE RECEPCION (
	REC_numero SMALLINT NOT NULL,
	REC_fecha DATE NOT NULL,
	REC_hora TIME(7) NOT NULL,
	INC_numero SMALLINT NOT NULL,
	PRI_codigo SMALLINT NOT NULL,
	IMP_codigo SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_recepcion PRIMARY KEY (REC_numero),
	CONSTRAINT fk_incidencia_recepcion FOREIGN KEY (INC_numero) REFERENCES INCIDENCIA (INC_numero),
	CONSTRAINT fk_prioridad_recepcion FOREIGN KEY (PRI_codigo) REFERENCES PRIORIDAD (PRI_codigo),
	CONSTRAINT fk_impacto_recepcion FOREIGN KEY (IMP_codigo) REFERENCES IMPACTO (IMP_codigo),
	CONSTRAINT fk_usuario_recepcion FOREIGN KEY (USU_codigo) REFERENCES USUARIO (USU_codigo),
);
GO

--CREACION DE LA TABLA ASIGNACION
CREATE TABLE ASIGNACION (
	ASI_codigo SMALLINT NOT NULL,
	ASI_fecha DATE NOT NULL,
	ASI_hora TIME NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	REC_numero SMALLINT NOT NULL,
	CONSTRAINT pk_asignacion PRIMARY KEY (ASI_codigo),
	CONSTRAINT fk_usuario_asignacion FOREIGN KEY (USU_codigo)
	REFERENCES USUARIO (USU_codigo),
	CONSTRAINT fk_recepcion_asignacion FOREIGN KEY (REC_numero)
	REFERENCES RECEPCION (REC_numero)
);
GO

--CREACION DE LA TABLA EJECUCION DE MANTENIMIENTO
CREATE TABLE MANTENIMIENTO (
	MAN_codigo SMALLINT NOT NULL,
	MAN_fecha DATE NOT NULL,
	MAN_hora TIME NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	ASI_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_mantenimiento PRIMARY KEY (MAN_codigo),
	CONSTRAINT fk_asignacion_mantenimiento FOREIGN KEY (ASI_codigo) 
	REFERENCES ASIGNACION (ASI_codigo)
);
GO

-- CREACION DE LA TABLA CONDICION
CREATE TABLE CONDICION (
	CON_codigo SMALLINT IDENTITY(1,1) NOT NULL,
	CON_descripcion VARCHAR(20) NOT NULL,
	CONSTRAINT pk_operatividad PRIMARY KEY (CON_codigo)
);
GO

--CREACION DE LA TABLA SOLUCION
CREATE TABLE SOLUCION(
	SOL_codigo SMALLINT IDENTITY(1,1) NOT NULL,
	SOL_descripcion VARCHAR(100) NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_solucion PRIMARY KEY (SOL_codigo)
);
GO

-- CREACION DE LA TABLA CIERRE
CREATE TABLE CIERRE(
	CIE_numero SMALLINT NOT NULL,
	CIE_fecha DATE NOT NULL,
	CIE_hora TIME NOT NULL,
	CIE_diagnostico VARCHAR(1000) NULL,
	CIE_documento VARCHAR(500) NOT NULL,
	CIE_recomendaciones VARCHAR(1000) NULL,
	CON_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	MAN_codigo SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	SOL_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_cierre PRIMARY KEY (CIE_numero),
	CONSTRAINT fk_condicion_cierre FOREIGN KEY (CON_codigo) 
	REFERENCES CONDICION (CON_codigo),
	CONSTRAINT fk_mantenimiento_cierre FOREIGN KEY (MAN_codigo) 
	REFERENCES MANTENIMIENTO (MAN_codigo),
	CONSTRAINT fk_usuario_cierre FOREIGN KEY (USU_codigo) 
	REFERENCES USUARIO (USU_codigo),
	CONSTRAINT fk_solucion_cierre FOREIGN KEY (SOL_codigo)
	REFERENCES SOLUCION (SOL_codigo)
);
GO

--CREACION DE LA TABLA AUDITORIA
CREATE TABLE AUDITORIA (
	AUD_codigo SMALLINT IDENTITY(1,1),
	AUD_fecha DATE NOT NULL,
	AUD_hora TIME NOT NULL,
	AUD_usuario SMALLINT NULL,
	AUD_tabla VARCHAR(50) NOT NULL,
	AUD_operacion VARCHAR(100) NOT NULL,
	AUD_ip VARCHAR(50) NULL,
	AUD_nombreEquipo VARCHAR(200) NULL,
    AUD_referencia SMALLINT NULL,
	CONSTRAINT pk_auditoria PRIMARY KEY (AUD_codigo)
);
GO

-------------------------------------------------------------------------------------------------------
  -- VOLCADO DE DATOS
-------------------------------------------------------------------------------------------------------
--VOLCADO DE DATOS PARA LA TABLA ROL
INSERT INTO ROL (ROL_nombre) VALUES ('Administrador');
INSERT INTO ROL (ROL_nombre) VALUES ('Soporte');
INSERT INTO ROL (ROL_nombre) VALUES ('Usuario');
GO

-- VOLCADO DE DATOS PARA LA TABLA PERSONA
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('18066272', 'Jose', 'Castro', 'Gonzales', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('11111111', 'Percy', 'Carranza', 'X', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('42761038', 'Alan', 'Collantes', 'Arana', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70400300', 'Freysi', 'Benites', 'Torres', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70400307', 'Carlos', 'Leyva', 'Campos', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70400000', 'Maria', 'Blas', 'Vera', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70401204', 'Stiven', 'Fabian', 'Bustamante', '', '');
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70555743', 'Jhonatan', 'Mantilla', 'Miñano', 'jhonatanmm.1995@gmail.com', '950212909');
GO 

-- VOLCADO DE DATOS PARA LA TABLA ESTADO
INSERT INTO ESTADO (EST_descripcion) VALUES ('ACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('INACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('ABIERTO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('RECEPCIONADO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('EN ESPERA');
INSERT INTO ESTADO (EST_descripcion) VALUES ('RESUELTO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('CERRADO');
GO

-- VOLCADO DE DATOS PARA LA TABLA PRIORIDAD
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('BAJA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('MEDIA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('ALTA');
GO

-- VOLCADO DE DATOS PARA LA TABLA CATEGORIA
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Red inaccesible', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Asistencia técnica', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Generación de usuario', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Fallo de equipo de computo', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Inaccesibilidad a impresora', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Cableado de red', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Correo corporativo', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Reporte de sistemas informáticos', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Otros', 1);
INSERT INTO CATEGORIA (CAT_nombre, EST_codigo) VALUES ('Inaccesibilidad a sistemas informáticos', 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA IMPACTO
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('BAJO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('MEDIO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('ALTO');
GO

-- VOLCADO DE DATOS PARA LA TABLA BIEN
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('','',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74089950','CPU',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74080500','LAPTOP',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74088187','MONITOR PLANO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74087700','MONITOR A COLOR',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74089500','TECLADO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74088600','MOUSE',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('46225215','ESTABILIZADOR',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74083650','IMPRESORA A INYECCION DE TINTA',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74083875','IMPRESORA DE CODIGO DE BARRAS',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74084550','IMPRESORA MATRIZ DE PUNTO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74085000','IMPRESORA PARA PLANOS - PLOTTERS',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74084100','IMPRESORA LASER',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74222358','EQUIPO MULTIFUNCIONAL COPIADORA IMPRESORA SCANNER',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('95228117','SWITCH PARA RED',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo) VALUES ('74087250','MODEM EXTERNO',1);
GO

-- VOLCADO DE DATOS PARA LA TABLA CONDICION
INSERT INTO CONDICION (CON_descripcion) VALUES ('OPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('INOPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('SOLUCIONADO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('NO SOLUCIONADO');
GO

--VOLCADO DE DATOS PARA LA TABLA SOLUCION
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Formateo de disco duro e instalación de programas',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Mantenimiento correctivo de hardware',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Restauración de sistema operativo',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Restablecimiento de contraseñas de usuario',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Recuperación de archivos',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Actualizaciones de software',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Restablecimiento de configuración de fábrica',1);
INSERT INTO SOLUCION (SOL_descripcion, EST_codigo) VALUES ('Mantenimiento de infraestructura de red',1);
GO

-------------------------------------------------------------------------------------------------------
  -- VISTAS
-------------------------------------------------------------------------------------------------------
-- VISTA LISTAR USUARIOS
CREATE OR ALTER VIEW vista_usuarios AS
SELECT 
    u.USU_codigo, 
    p.PER_dni, 
    CONCAT(p.PER_nombres, ' ', p.PER_apellidoPaterno, ' ', p.PER_apellidoMaterno) AS persona,
    a.ARE_nombre, 
    u.EST_codigo, 
    u.USU_nombre, 
    u.USU_password, 
    r.ROL_nombre, 
    e.EST_descripcion 
FROM 
    USUARIO u
    INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo 
    LEFT JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
    LEFT JOIN ESTADO e ON e.EST_codigo = u.EST_codigo
    LEFT JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
WHERE 
    u.USU_codigo <> 0
GO


-- VISTA LISTAR INCIDENCIAS ADMINISTRADOR
CREATE OR ALTER VIEW vista_incidencias_administrador AS
SELECT 
  I.INC_numero,
  I.INC_numero_formato,
  (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
  I.INC_codigoPatrimonial,
  B.BIE_nombre,
  I.INC_asunto,
  I.INC_documento,
  I.INC_descripcion,
  CAT.CAT_nombre,
  A.ARE_nombre,
  E.EST_descripcion,
  p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
FROM 
  INCIDENCIA I
  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    I.EST_codigo IN (2, 3);
GO

-- VISTA PARA LISTAR INCIDENCIAS LISTAS PARA RECEPCIONAR
CREATE OR ALTER VIEW vista_incidencias_recepcionar AS
SELECT 
  I.INC_numero,
  I.INC_numero_formato,
  (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
  I.INC_codigoPatrimonial,
  B.BIE_nombre,
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
  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
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

CREATE OR ALTER VIEW vista_recepciones AS
SELECT
  I.INC_numero,
  I.INC_numero_formato,
  ( CONVERT ( VARCHAR ( 10 ), I.INC_fecha, 103 ) ) AS fechaIncidenciaFormateada,
  I.INC_codigoPatrimonial,
  B.BIE_nombre,
  I.INC_asunto,
  I.INC_documento,
  I.INC_descripcion,
  R.REC_numero,
  (
    CONVERT ( VARCHAR ( 10 ), R.REC_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), REC_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaRecepcionFormateada,
  PRI.PRI_nombre,
  IMP.IMP_descripcion,
  CAT.CAT_nombre,
  EST_descripcion AS ESTADO,
  A.ARE_nombre,
  p.PER_nombres + ' ' + p.PER_apellidoPaterno AS UsuarioIncidente,
  pR.PER_nombres + ' ' + pR.PER_apellidoPaterno AS UsuarioRecepcion 
FROM
  INCIDENCIA I
  LEFT JOIN BIEN B ON LEFT( I.INC_codigoPatrimonial, 8 ) = B.BIE_codigoIdentificador
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN USUARIO uR ON uR.USU_codigo = R.USU_codigo
  LEFT JOIN PERSONA pR ON pR.PER_codigo = uR.PER_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo 
WHERE
  R.EST_codigo = 4 
  AND (I.EST_codigo IN ( 3, 4 ));
GO

--Vista para listar las incidencias totales para el administrador
-- CREATE OR ALTER VIEW vista_incidencias_totales_administrador AS
-- SELECT
--     I.INC_numero,
--     I.INC_numero_formato,
--     (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
--     A.ARE_nombre,
--     CAT.CAT_nombre,
--     I.INC_asunto,
--     I.INC_codigoPatrimonial,
--     B.BIE_nombre,
--     I.INC_documento,
--     (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
--     PRI.PRI_nombre,
--     IMP.IMP_descripcion,
--     (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
--     O.CON_descripcion,
--     U.USU_nombre,
--     CASE
--         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
--         ELSE E.EST_descripcion
--     END AS Estado,
--     -- Última modificación (fecha y hora más reciente)
--     MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
--     MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
-- FROM CIERRE C
-- LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
-- LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
-- RIGHT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
-- RIGHT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
-- RIGHT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
-- RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
-- INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
-- INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
-- INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
-- LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
-- LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
-- LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
-- LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
-- WHERE (I.EST_codigo IN (3, 4, 7) OR C.EST_codigo IN (3, 4, 7))
-- GROUP BY
--     I.INC_numero,
--     I.INC_numero_formato,
--     CONVERT(VARCHAR(10), INC_fecha, 103),
--     INC_hora,
--     A.ARE_nombre,
--     CAT.CAT_nombre,
--     I.INC_asunto,
--     I.INC_codigoPatrimonial,
--     B.BIE_nombre,
--     I.INC_documento,
--     CONVERT(VARCHAR(10), REC_fecha, 103),
--     REC_hora,
--     PRI.PRI_nombre,
--     IMP.IMP_descripcion,
--     CONVERT(VARCHAR(10), CIE_fecha, 103),
--     O.CON_descripcion,
--     U.USU_nombre,
--     CASE
--         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
--         ELSE E.EST_descripcion
--     END;
-- GO


CREATE OR ALTER VIEW vw_incidencias_registradas AS
WITH UltimaRecepcion AS (
  SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (
      CONVERT ( VARCHAR ( 10 ), I.INC_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), I.INC_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    E.EST_descripcion AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
    ROW_NUMBER ( ) OVER ( PARTITION BY I.INC_numero ORDER BY R.REC_fecha DESC, R.REC_hora DESC ) AS rn 
  FROM
    INCIDENCIA I
    LEFT JOIN BIEN B ON LEFT( I.INC_codigoPatrimonial, 8 ) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo 
  WHERE
    I.EST_codigo IN ( 3 ) 
) -- Filtramos para obtener solo la recepción más reciente de cada incidencia
SELECT * FROM UltimaRecepcion 
WHERE rn = 1
GO

CREATE OR ALTER VIEW vw_incidencias_totales_administrador AS
WITH IncidenciasOrdenadas AS (
    SELECT
        I.INC_numero,
        I.INC_numero_formato,
        I.INC_fecha,
        (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
        A.ARE_nombre,
        CAT.CAT_nombre,
        I.INC_asunto,
        I.INC_codigoPatrimonial,
        B.BIE_nombre,
        I.INC_documento,
        (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
        -- Si el estado es "ABIERTO", mostramos NULL en PRI_nombre e IMP_descripcion
        CASE
            WHEN E.EST_descripcion = 'ABIERTO' THEN NULL
            ELSE PRI.PRI_nombre
        END AS PRI_nombre,
        CASE
            WHEN E.EST_descripcion = 'ABIERTO' THEN NULL
            ELSE IMP.IMP_descripcion
        END AS IMP_descripcion,
        (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
        O.CON_descripcion,
        U.USU_nombre,
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END AS Estado,
        COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha) AS ultimaFecha,
        COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora) AS ultimaHora,
        ROW_NUMBER() OVER (PARTITION BY I.INC_numero ORDER BY COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha) DESC, COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora) DESC) AS rn
    FROM CIERRE C
    LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
    LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
    RIGHT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    RIGHT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    RIGHT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    WHERE (I.EST_codigo IN (3, 4, 7) OR C.EST_codigo IN (3, 4, 7))
)
-- Seleccionamos solo el registro más reciente (rn = 1)
SELECT 
    INC_numero,
    INC_numero_formato,
    fechaIncidenciaFormateada,
    INC_fecha,
    ARE_nombre,
    CAT_nombre,
    INC_asunto,
    INC_codigoPatrimonial,
    BIE_nombre,
    INC_documento,
    fechaRecepcionFormateada,
    PRI_nombre,
    IMP_descripcion,
    fechaCierreFormateada,
    CON_descripcion,
    USU_nombre,
    Estado,
    ultimaFecha,
    ultimaHora
FROM IncidenciasOrdenadas
WHERE rn = 1;
GO

-- Vista para listar incidencias pendientes de cierre
CREATE OR ALTER VIEW vw_incidencias_pendientes AS
WITH IncidenciasOrdenadas AS (
    SELECT
        I.INC_numero,
        I.INC_numero_formato,
        I.INC_fecha,
        REC_fecha,
        (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
        A.ARE_nombre,
        CAT.CAT_nombre,
        I.INC_asunto,
        I.INC_codigoPatrimonial,
        B.BIE_nombre,
        I.INC_documento,
        (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
        -- Si el estado es "ABIERTO", mostramos NULL en PRI_nombre e IMP_descripcion
        CASE
            WHEN E.EST_descripcion = 'ABIERTO' THEN NULL
            ELSE PRI.PRI_nombre
        END AS PRI_nombre,
        CASE
            WHEN E.EST_descripcion = 'ABIERTO' THEN NULL
            ELSE IMP.IMP_descripcion
        END AS IMP_descripcion,
        (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
        O.CON_descripcion,
        U.USU_nombre,
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END AS Estado,
        -- Aquí agregamos EST_codigo
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_codigo
            ELSE E.EST_codigo
        END AS EST_codigo,
        COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha) AS ultimaFecha,
        COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora) AS ultimaHora,
        ROW_NUMBER() OVER (PARTITION BY I.INC_numero ORDER BY COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha) DESC, COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora) DESC) AS rn
    FROM CIERRE C
    LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
    LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
    RIGHT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    RIGHT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    RIGHT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    WHERE (I.EST_codigo IN (3, 4) OR C.EST_codigo IN (3, 4))
)
-- Seleccionamos solo el registro más reciente (rn = 1)
SELECT 
    INC_numero,
    INC_numero_formato,
    fechaIncidenciaFormateada,
    INC_fecha,
    REC_fecha,
    ARE_nombre,
    CAT_nombre,
    INC_asunto,
    INC_codigoPatrimonial,
    BIE_nombre,
    INC_documento,
    fechaRecepcionFormateada,
    PRI_nombre,
    IMP_descripcion,
    fechaCierreFormateada,
    CON_descripcion,
    USU_nombre,
    Estado,
    EST_codigo, -- Aquí mostramos el EST_codigo
    ultimaFecha,
    ultimaHora
FROM IncidenciasOrdenadas
WHERE rn = 1
  AND EST_codigo IN (3, 4);  -- Solo mostrar los estados con EST_codigo 3 (ABIERTO) y 4 (RECEPCIONADO)
GO

--Vista para listar las nuevas incidencias para el usuario
CREATE OR ALTER VIEW vista_incidencias_usuario AS 
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
CREATE OR ALTER VIEW vista_incidencias_totales_usuario AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT(VARCHAR(7), I.INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,	A.ARE_codigo,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
	B.BIE_nombre,
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
LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
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
WHERE I.EST_codigo IN (3, 4, 7) OR C.EST_codigo IN (3, 4, 7);
GO

-- Vista para listar incidencias por fecha para el administrador
CREATE OR ALTER VIEW vista_incidencias_fecha_admin AS
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
CREATE OR ALTER VIEW vista_incidencias_fecha_user AS
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

-- Vista para listar las incidencias asignadas solo las que no estan finalizadas
CREATE OR ALTER VIEW vw_incidencias_asignadas AS
SELECT 
	I.INC_numero,
    ASI.ASI_codigo,
	R.REC_numero,
    I.INC_numero_formato,
	M.MAN_codigo,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
    (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    A.ARE_nombre,
    I.INC_asunto,
	I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    U.USU_codigo,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
    pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
	CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS EST_descripcion
FROM 
    ASIGNACION ASI
    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
    LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
    LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
	LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  WHERE E.EST_codigo = 5
GO

--Vista para listar asignaciones
CREATE OR ALTER VIEW vista_asignaciones AS
SELECT 
    ASI.ASI_codigo,
    R.REC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,
    A.ARE_nombre,
    I.INC_asunto,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    U.USU_codigo,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioAsignado,
    pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
    E.EST_descripcion,
    E.EST_codigo
FROM 
    ASIGNACION ASI
    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
    LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
    LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo;
GO

-- --Vista para listar incidencias finalizadas
-- CREATE OR ALTER VIEW vista_mantenimiento AS
-- SELECT 
-- 	I.INC_numero,
--     ASI.ASI_codigo,
-- 	R.REC_numero,
--     I.INC_numero_formato,
-- 	M.MAN_codigo,
--     (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
--     (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
--     (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
--     A.ARE_nombre,
--     I.INC_asunto,
-- 	I.INC_documento,
--     I.INC_codigoPatrimonial,
--     B.BIE_nombre,
--     U.USU_codigo,
--     P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
--     pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
-- 	CASE
--         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
--         ELSE E.EST_descripcion
--     END AS EST_descripcion
-- FROM 
--     ASIGNACION ASI
--     INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
--     LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
--     LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
--     LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
--     INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
--     LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
--     LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
--     LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
--     INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
-- 	LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
-- 	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
-- 	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
-- GO

-- Vista para listar mantenimientos con tiempo de mantenimiento
CREATE OR ALTER VIEW vw_incidencias_mantenimiento AS
SELECT 
    I.INC_numero,
    ASI.ASI_codigo,
    I.INC_numero_formato,
    M.MAN_codigo,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
    (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    
    -- Cálculo del tiempo de mantenimiento en segundos
    DATEDIFF(SECOND, 
        CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
        CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
    ) AS tiempoMantenimientoSegundos,

    -- Mostrar solo días, horas, minutos y segundos según sea necesario
    CASE
        -- Si tiene días, mostrar días, horas, minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 86400 
        THEN
            CAST(DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) / 86400 AS VARCHAR) + ' días ' +
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas ' 
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

        -- Si tiene horas pero no días, mostrar horas y minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 3600 
        THEN
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas '
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

       -- Si tiene menos de una hora, mostrar solo minutos
			ELSE
				CAST(DATEDIFF(MINUTE, 
					CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
					CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
				) AS VARCHAR) + 
                CASE 
                    WHEN DATEDIFF(MINUTE, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                                  CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)) = 1 
                    THEN ' minuto '
                    ELSE ' minutos '
                END
    END AS tiempoMantenimientoFormateado, 
    M.MAN_fecha,
    A.ARE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    U.USU_codigo,
    E.EST_descripcion,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
    pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
	CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
    E.EST_codigo ,
	 -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, M.MAN_fecha, ASI.ASI_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, M.MAN_hora, ASI.ASI_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM 
    ASIGNACION ASI
    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
    LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
    LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
GROUP BY 
    I.INC_numero, 
    ASI.ASI_codigo, 
    I.INC_numero_formato, 
    M.MAN_codigo, 
    REC_fecha, 
    REC_hora, 
    ASI.ASI_fecha, 
    ASI.ASI_hora, 
    M.MAN_fecha, 
    M.MAN_hora,
    A.ARE_nombre, 
    I.INC_asunto, 
    I.INC_documento, 
    I.INC_codigoPatrimonial, 
    B.BIE_nombre, 
    U.USU_codigo, 
    P.PER_nombres, 
    P.PER_apellidoPaterno, 
    pA.PER_nombres, 
    pA.PER_apellidoPaterno, 
    C.CIE_numero, 
    EC.EST_descripcion, 
    E.EST_descripcion,
    E.EST_codigo 
GO



-- CREATE OR ALTER VIEW vista_incidencias_matenimiento AS
-- SELECT 
--     I.INC_numero,
--     ASI.ASI_codigo,
--     I.INC_numero_formato,
--     M.MAN_codigo,
--     (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
--     (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
--     (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    
--     -- Cálculo del tiempo de mantenimiento en segundos
--     DATEDIFF(SECOND, 
--         CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--         CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--     ) AS tiempoMantenimientoSegundos,

--     -- Mostrar solo días, horas, minutos y segundos según sea necesario
--     CASE
--         -- Si tiene días, mostrar días, horas, minutos
--         WHEN DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) >= 86400 
--         THEN
--             CAST(DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) / 86400 AS VARCHAR) + ' días ' +
--             CAST((DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) % 86400) / 3600 AS VARCHAR) + 
--             CASE 
--                 WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                             CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--                     ) % 86400) / 3600 = 1 THEN ' hora '
--                 ELSE ' horas ' 
--             END +
--             CAST(((DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) % 86400) % 3600) / 60 AS VARCHAR) + 
--             CASE 
--                 WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                             CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--                     ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
--                 ELSE ' minutos '
--             END

--         -- Si tiene horas pero no días, mostrar horas y minutos
--         WHEN DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) >= 3600 
--         THEN
--             CAST((DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) % 86400) / 3600 AS VARCHAR) + 
--             CASE 
--                 WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                             CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--                     ) % 86400) / 3600 = 1 THEN ' hora '
--                 ELSE ' horas '
--             END +
--             CAST(((DATEDIFF(SECOND, 
--                 CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                 CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--             ) % 86400) % 3600) / 60 AS VARCHAR) + 
--             CASE 
--                 WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                             CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
--                     ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
--                 ELSE ' minutos '
--             END

--        -- Si tiene menos de una hora, mostrar solo minutos
-- 			ELSE
-- 				CAST(DATEDIFF(MINUTE, 
-- 					CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
-- 					CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
-- 				) AS VARCHAR) + 
--                 CASE 
--                     WHEN DATEDIFF(MINUTE, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
--                                   CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)) = 1 
--                     THEN ' minuto '
--                     ELSE ' minutos '
--                 END
--     END AS tiempoMantenimientoFormateado, 
--     M.MAN_fecha,
--     A.ARE_nombre,
--     I.INC_asunto,
--     I.INC_documento,
--     I.INC_codigoPatrimonial,
--     B.BIE_nombre,
--     U.USU_codigo,
--     P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
--     pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
-- 	CASE
--         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
--         ELSE E.EST_descripcion
--     END AS Estado,
-- 	 -- Última modificación (fecha y hora más reciente)
--     MAX(COALESCE(C.CIE_fecha, M.MAN_fecha, ASI.ASI_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
--     MAX(COALESCE(C.CIE_hora, M.MAN_hora, ASI.ASI_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
-- FROM 
--     ASIGNACION ASI
--     INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
--     LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
--     LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
--     LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
--     INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
--     LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
--     LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
--     LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
--     INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
--     LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
-- 	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
-- 	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
-- GROUP BY 
--     I.INC_numero, 
--     ASI.ASI_codigo, 
--     I.INC_numero_formato, 
--     M.MAN_codigo, 
--     REC_fecha, 
--     REC_hora, 
--     ASI.ASI_fecha, 
--     ASI.ASI_hora, 
--     M.MAN_fecha, 
--     M.MAN_hora,
--     A.ARE_nombre, 
--     I.INC_asunto, 
--     I.INC_documento, 
--     I.INC_codigoPatrimonial, 
--     B.BIE_nombre, 
--     U.USU_codigo, 
--     P.PER_nombres, 
--     P.PER_apellidoPaterno, 
--     pA.PER_nombres, 
--     pA.PER_apellidoPaterno, 
--     C.CIE_numero, 
--     EC.EST_descripcion, 
--     E.EST_descripcion
-- GO

-- Vista para listar cierres
CREATE OR ALTER VIEW vista_cierres AS
SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    PRI.PRI_nombre,
    (CONVERT(VARCHAR(10), C.CIE_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT(VARCHAR(7), C.CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
    C.CIE_numero,
    C.CIE_diagnostico, 
    C.CIE_recomendaciones,
    C.CIE_documento,
    O.CON_descripcion,
    U.USU_nombre,
    S.SOL_descripcion,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
    (P.PER_nombres + ' ' + P.PER_apellidoPaterno) AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM CIERRE C
LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
LEFT JOIN SOLUCION S ON S.SOL_codigo = C.SOL_codigo
WHERE MAN.EST_codigo = 7 OR C.EST_codigo = 7
GROUP BY
    I.INC_numero,
    I.INC_numero_formato,
    INC_fecha,
    INC_hora,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    PRI.PRI_nombre,
    C.CIE_fecha,
    C.CIE_hora,
    C.CIE_numero,
    C.CIE_diagnostico,
    C.CIE_recomendaciones,
    C.CIE_documento,
    O.CON_descripcion,
    U.USU_nombre,
    S.SOL_descripcion,
    P.PER_nombres,
    P.PER_apellidoPaterno,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END;
GO

--VISTA PARA MOSTRAR NOTIFICACIONES PARA LOS USUARIOS
CREATE OR ALTER VIEW vista_notificaciones_usuario AS
SELECT 
I.INC_numero,
I.INC_numero_formato,
(CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + CONVERT(VARCHAR(5), I.INC_hora, 108)) AS fechaIncidenciaFormateada,
A.ARE_codigo,
A.ARE_nombre AS NombreAreaIncidencia,
I.INC_asunto,
C.USU_codigo,
O.CON_descripcion,
U.USU_nombre,
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
CREATE OR ALTER VIEW vista_notificaciones_administrador AS
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
WHERE I.EST_codigo NOT IN (2, 4, 5) 
AND A.ARE_codigo <> 1;
GO

--VISTA PARA REPORTE DE INCIDENCIAS TOTALES
-- CREATE OR ALTER VIEW vw_reporte_incidencias_totales AS
-- SELECT
--     I.INC_numero,
-- 	C.CIE_numero,
--     I.INC_numero_formato,
--     (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
--     A.ARE_nombre,
--     CAT.CAT_nombre,
--     I.INC_asunto,
--     I.INC_documento,
--     I.INC_codigoPatrimonial,
-- 	B.BIE_nombre,
--     (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
--     PRI.PRI_nombre,
--     IMP.IMP_descripcion,
--     (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
--     O.CON_descripcion,
--     U.USU_nombre,
--     p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
--     CASE
--         WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
--         ELSE E.EST_descripcion
--     END AS Estado
--     FROM INCIDENCIA I
--     INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
--     INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
--     INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
-- 	LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
--     LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
-- 	LEFT JOIN ASIGNACION ASI ON ASI.REC_numero =R.REC_numero
-- 	LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
-- 	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
--     LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
--     LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
--     LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
--     LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
--     LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
--     INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
--     WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
-- GO


--VISTA PARA REPORTE DE INCIDENCIAS TOTALES
CREATE OR ALTER VIEW vw_reporte_incidencias_area_equipo AS
SELECT
    I.INC_numero,
	C.CIE_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' '))  AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
	B.BIE_nombre,
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
    END AS Estado
    FROM INCIDENCIA I
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
	LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
	LEFT JOIN ASIGNACION ASI ON ASI.REC_numero =R.REC_numero
	LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
    WHERE (I.EST_codigo IN (3, 4, 7) OR C.EST_codigo IN (3, 4, 7));
GO

-- //Vista para listar las incidencias con codigo patrimonial
CREATE OR ALTER VIEW vw_reporte_incidencias_equipos AS
SELECT
    I.INC_numero,
	C.CIE_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' '))  AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
	B.BIE_nombre,
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
    END AS Estado
    FROM INCIDENCIA I
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
	LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
	LEFT JOIN ASIGNACION ASI ON ASI.REC_numero =R.REC_numero
	LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
    WHERE (I.EST_codigo IN (3, 4, 7) OR C.EST_codigo IN (3, 4, 7))
	AND i.INC_codigoPatrimonial IS NOT NULL
    AND i.INC_codigoPatrimonial <> '';
GO

CREATE OR ALTER VIEW vw_reporte_incidencias_area AS
SELECT
      I.INC_numero_formato,
      (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
      A.ARE_nombre,
      CAT.CAT_nombre,
      I.INC_asunto,
      I.INC_documento,
      I.INC_codigoPatrimonial,
	  B.BIE_nombre,
      PRI.PRI_nombre,
      U.USU_nombre,
      O.CON_descripcion,
      -- p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
      CASE
          WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
          ELSE E.EST_descripcion
      END AS ESTADO
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
      INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
	  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
      LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
	  LEFT JOIN ASIGNACION ASI ON ASI.REC_numero =R.REC_numero
	  LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	  LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
      LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
      LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
      LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
      LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
      LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
      INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
      WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
GO

-- Vista para listar incidencias recepcionadas
CREATE OR ALTER VIEW vw_recepciones AS
WITH UltimaRecepcion AS (
  SELECT
    I.INC_numero,
    I.INC_numero_formato,
    R.REC_numero,
    (
      CONVERT ( VARCHAR ( 10 ), I.INC_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), I.INC_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaRecepcionFormateada,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    A.ARE_nombre,
    E.EST_descripcion AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS UsuarioRecepcion,
    ROW_NUMBER() OVER (PARTITION BY I.INC_numero ORDER BY R.REC_fecha DESC, R.REC_hora DESC) AS rn 
  FROM
    INCIDENCIA I
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo 
  WHERE
    I.EST_codigo = 4  -- Estado de "Recepcionado"
    AND (R.EST_codigo IS NULL OR R.EST_codigo NOT IN (5, 6, 7)) 
)
SELECT * 
FROM UltimaRecepcion 
WHERE rn = 1;
GO

-- Vista para listar las ultimas incidencias recepcionadas, solo en estado 4 (RECEPCIONADO)
CREATE OR ALTER VIEW vw_incidencias_recepcionadas AS
WITH UltimaRecepcion AS (
SELECT
  I.INC_numero,
  I.INC_numero_formato,
  R.REC_numero,
  (
    CONVERT ( VARCHAR ( 10 ), I.INC_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), I.INC_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaRecepcionFormateada,
  A.ARE_nombre,
  I.INC_codigoPatrimonial,
  B.BIE_nombre,
  I.INC_asunto,
  CAT.CAT_nombre,
  PRI.PRI_nombre,
  IMP.IMP_descripcion,
  p.PER_nombres + ' ' + p.PER_apellidoPaterno AS UsuarioRecepcion,
  E.EST_descripcion AS ESTADO,
  R.EST_codigo ,
  ROW_NUMBER() OVER (PARTITION BY I.INC_numero ORDER BY R.REC_fecha DESC, R.REC_hora DESC) AS rn 
FROM
  RECEPCION R
  INNER JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  INNER JOIN ESTADO E ON E.EST_codigo = R.EST_codigo
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  LEFT JOIN BIEN B ON LEFT( I.INC_codigoPatrimonial, 8 ) = B.BIE_codigoIdentificador
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  WHERE
    R.EST_codigo = 4  -- Only include "Recepcionado" state
    )
SELECT * 
FROM UltimaRecepcion 
WHERE rn = 1;
GO

--Vista para reporte de incidencias pendientes de cierre
CREATE OR ALTER VIEW vw_reporte_pendientes_cierre AS
SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
	PRI.PRI_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    I.EST_codigo IN (3, 4) -- Solo incluir incidencias con estado 3 o 4
    AND NOT EXISTS (  -- Excluir incidencias que hayan pasado al estado 7 en la tabla CIERRE
        SELECT 1 
        FROM CIERRE C2
        WHERE C2.MAN_codigo = M.MAN_codigo
        AND C2.EST_codigo = 7
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
	PRI.PRI_nombre,
    B.BIE_nombre, -- Agregada al GROUP BY
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion,
    p.PER_nombres,
    p.PER_apellidoPaterno;
GO

-- VISTA PARA LISTAR LAS AREAS MAS AFECTADAS
CREATE OR ALTER VIEW vw_area_mas_afectada AS
SELECT a.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias
FROM INCIDENCIA i
INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
GROUP BY a.ARE_nombre;
GO

-- VISTA PARA LSITAR PERSONAS 
CREATE OR ALTER VIEW vw_personas AS SELECT
  PER_codigo,
  PER_dni,
  ( PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno ) AS persona,
  PER_celular,
  PER_email 
FROM PERSONA;
GO

-- Vista para listar los equipos mas afectados
CREATE OR ALTER VIEW vw_equipos_mas_afectados AS
SELECT 
    i.INC_codigoPatrimonial AS codigoPatrimonial,
    -- Subconsulta para obtener el nombre del bien utilizando los primeros 8 dígitos
    (SELECT BIE_nombre 
     FROM BIEN 
     WHERE LEFT(i.INC_codigoPatrimonial, 8) = LEFT(BIE_codigoIdentificador, 8)) AS nombreBien,
    a.ARE_nombre AS nombreArea, 
    COUNT(*) AS cantidadIncidencias
FROM INCIDENCIA i
INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo 
WHERE i.INC_codigoPatrimonial IS NOT NULL
AND i.INC_codigoPatrimonial <> ''
GROUP BY i.INC_codigoPatrimonial, a.ARE_nombre;
GO

-- Vista para listar todos los eventos de auditoria
CREATE OR ALTER VIEW vw_eventos_totales AS
SELECT
  (
    CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha, -- Campo de fecha original
  A.AUD_hora,  -- Campo de hora original
  A.AUD_tabla,
  A.AUD_usuario,
  R.ROL_nombre,
  U.USU_nombre,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario  
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo 
  INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo;
GO

--Vista para ver los incios de sesion
CREATE OR ALTER VIEW vw_auditoria_login AS
SELECT
  (
    CONVERT ( VARCHAR ( 10 ), AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha,-- Campo de fecha original
  A.AUD_hora,-- Campo de hora original
  A.AUD_tabla,
  A.AUD_usuario,
  R.ROL_nombre,
  U.USU_nombre,
  PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
WHERE
  AUD_operacion IN ( 'Iniciar sesión' );
GO


CREATE OR ALTER VIEW vw_auditoria_registrar_incidencia AS
SELECT  
(CONVERT(VARCHAR(10), i.INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), i.INC_hora, 0), 7), 6, 0, ' ')) AS fechaFormateada,
A.AUD_fecha,
I.INC_fecha,
R.ROL_nombre,
U.USU_nombre,
p.PER_nombres + ' ' + PER_apellidoPaterno AS NombreCompleto,
AR.ARE_nombre,
I.INC_numero,
i.INC_numero_formato,
a.AUD_usuario, 
a.AUD_tabla, 
a.AUD_operacion,
a.AUD_ip, 
a.AUD_nombreEquipo
FROM INCIDENCIA i
JOIN (
    SELECT AUD_codigo, AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo, 
           ROW_NUMBER() OVER (PARTITION BY AUD_usuario ORDER BY AUD_fecha DESC, AUD_hora DESC) AS row_num
    FROM AUDITORIA
    WHERE AUD_operacion = 'Registrar incidencia'
) a ON i.USU_codigo = a.AUD_usuario
INNER JOIN USUARIO u ON U.USU_codigo = i.USU_codigo
INNER JOIN PERSONA p ON P.PER_codigo = U.PER_codigo
INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
INNER JOIN AREA AR ON AR.ARE_codigo = I.ARE_codigo
WHERE a.row_num = 1;
GO

-- VISTA PARA LISTAR TODOS LOS EVENTOS DE LAS INCIDENCIAS
CREATE OR ALTER VIEW vw_eventos_incidencias AS
SELECT
  (
    CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  A.AUD_tabla,
  I.INC_numero_formato AS referencia,
CASE
    
    WHEN A.AUD_tabla = 'INCIDENCIA' THEN
    I.INC_numero 
  END AS NumeroReferencia,
  A.AUD_usuario,
  R.ROL_nombre,
  U.USU_nombre,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
  LEFT JOIN INCIDENCIA I ON I.INC_numero = A.AUD_referencia 
  AND A.AUD_tabla = 'INCIDENCIA' 
WHERE
  A.AUD_tabla = 'INCIDENCIA' 
  AND ( I.INC_numero_formato IS NOT NULL AND I.INC_numero_formato <> '' );
GO

-- VISTA PARA LISTAR TODOS LOS EVENTOS DE RECEPCIONES
CREATE OR ALTER VIEW vw_eventos_recepciones AS
SELECT
  (
    CONVERT(VARCHAR(10), A.AUD_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  A.AUD_tabla,
  R.REC_numero,
  I.INC_numero,
  CASE
    WHEN A.AUD_operacion = 'Eliminar incidencia recepcionada' THEN I.INC_numero_formato
    ELSE I.INC_numero_formato
  END AS referencia,
  A.AUD_usuario,
  U.USU_nombre,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
  LEFT JOIN RECEPCION R ON R.REC_numero = A.AUD_referencia
  LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
WHERE
  A.AUD_tabla = 'RECEPCION';
GO

-- VISTA PARA LISTAR EVENTOS DE ASIGNACIONES
CREATE OR ALTER VIEW vw_eventos_asignaciones AS
SELECT
  (
    CONVERT(VARCHAR(10), A.AUD_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  A.AUD_tabla,
  R.REC_numero,
  I.INC_numero,
  I.INC_numero_formato AS referencia,
  A.AUD_usuario,
  U.USU_nombre,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
  LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = A.AUD_referencia
  LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
  LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
WHERE
  A.AUD_tabla = 'ASIGNACION';
GO

-- VISTA PARA LISTAR EVENTOS DE MANTENIMIENTO
CREATE OR ALTER VIEW vw_eventos_mantenimiento AS
SELECT
  (
    CONVERT(VARCHAR(10), A.AUD_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  A.AUD_tabla,
  I.INC_numero, 
  I.INC_numero_formato AS referencia,
  U.USU_nombre,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
  A.AUD_operacion,
  AR.ARE_nombre,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
  LEFT JOIN MANTENIMIENTO M ON M.MAN_codigo = A.AUD_referencia
  LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = M.ASI_codigo
  LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
  LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
WHERE
  A.AUD_tabla = 'MANTENIMIENTO';
GO

-- VISTA PARA LISTAR EVENTOS DE CIERRES


-- Vista para listar todos los eventos de personas
CREATE OR ALTER VIEW vw_eventos_personas AS
SELECT
  (
    CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
  A.AUD_referencia,
  CASE-- Si la operación es "Actualizar perfil", tomamos la referencia del código de usuario 
    WHEN A.AUD_operacion = 'Actualizar perfil' THEN
    (
      SELECT
        PE2.PER_nombres + ' ' + PE2.PER_apellidoPaterno + ' ' + PE2.PER_apellidoMaterno 
      FROM
        USUARIO U2
        LEFT JOIN PERSONA PE2 ON PE2.PER_codigo = U2.PER_codigo 
      WHERE
        U2.USU_codigo = A.AUD_referencia 
    ) -- Si no es "Actualizar perfil", tomamos la referencia del código de persona
    ELSE PE.PER_nombres + ' ' + PE.PER_apellidoPaterno + ' ' + PE.PER_apellidoMaterno 
  END AS UsuarioReferencia,
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A 
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo 
  LEFT JOIN PERSONA PE ON PE.PER_codigo = A.AUD_referencia 
WHERE
  A.AUD_tabla = 'PERSONA';
GO

-- Vista para listar eventos de usuarios
CREATE OR ALTER VIEW vw_eventos_usuarios AS 
SELECT
  (CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  A.AUD_usuario,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
  A.AUD_referencia,
  PE.PER_nombres + ' ' + PE.PER_apellidoPaterno + ' ' + PE.PER_apellidoMaterno AS UsuarioReferencia,
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  LEFT JOIN USUARIO UA ON UA.USU_codigo = A.AUD_referencia
  LEFT JOIN PERSONA PE ON PE.PER_codigo = UA.PER_codigo 
WHERE
  A.AUD_tabla = 'USUARIO' 
  AND A.AUD_operacion NOT IN ( 'Iniciar sesión' );
GO

-- Vista para listar eventos de areas
CREATE OR ALTER VIEW vw_eventos_areas AS
SELECT
  (
    CONVERT (VARCHAR(10), A.AUD_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT (VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento, 
  A.AUD_referencia, 
  AR.ARE_nombre AS referencia, 
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  LEFT JOIN AREA AR ON AR.ARE_codigo = A.AUD_referencia
WHERE
  A.AUD_tabla = 'AREA';
GO

-- Vista para listar eventos de bienes
CREATE OR ALTER VIEW vw_eventos_bienes AS
SELECT
  (
    CONVERT (VARCHAR(10), A.AUD_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT (VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento, 
  A.AUD_referencia, 
  B.BIE_codigoIdentificador + ' - ' + B.BIE_nombre AS referencia, 
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  LEFT JOIN BIEN b ON b.BIE_codigo = A.AUD_referencia 
WHERE
  A.AUD_tabla = 'BIEN';
GO

-- Vista para listar eventos de categorias
CREATE OR ALTER VIEW vw_eventos_categorias AS
SELECT
  (
    CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
  A.AUD_referencia,
  C.CAT_nombre AS referencia,
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo 
FROM
  AUDITORIA A
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  LEFT JOIN CATEGORIA C ON C.CAT_codigo = A.AUD_referencia 
WHERE
  A.AUD_tabla = 'CATEGORIA';
GO

-- Vista para listar eventos de soluciones
CREATE OR ALTER VIEW vw_eventos_soluciones AS
SELECT
  (
    CONVERT (VARCHAR(10), A.AUD_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT (VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')
  ) AS fechaFormateada,
  A.AUD_fecha,
  A.AUD_hora,
  P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento, 
  A.AUD_referencia, 
  S.SOL_descripcion AS referencia, 
  A.AUD_operacion,
  A.AUD_ip,
  A.AUD_nombreEquipo
FROM
  AUDITORIA A
  LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
  LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
  LEFT JOIN SOLUCION S ON S.SOL_codigo = A.AUD_referencia 
WHERE
  A.AUD_tabla = 'SOLUCION';
GO

-------------------------------------------------------------------------------------------------------
  -- FUNCIONES Y TRIGGERS
-------------------------------------------------------------------------------------------------------
-- FUNCION PARA GENERAR EL NUMERO DE INCIDENCIA 000-AÑO-MDE
CREATE OR ALTER FUNCTION dbo.GenerarNumeroIncidencia()
RETURNS VARCHAR(20)
AS
BEGIN
    DECLARE @numero INT;
    DECLARE @anio_actual CHAR(4);
    DECLARE @formato VARCHAR(20);
    DECLARE @resultado VARCHAR(20);

    -- Obtener el anio actual
    SET @anio_actual = YEAR(GETDATE());

    -- Obtener el ultimo numero de incidencia del anio actual
    SELECT @numero = ISNULL(MAX(CAST(SUBSTRING(INC_numero_formato, 1, CHARINDEX('-', INC_numero_formato) - 1) AS INT)), 0) + 1
    FROM INCIDENCIA
    WHERE SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) = @anio_actual;

    -- Generar el formato con el numero actual
    SET @formato = RIGHT('000' + CAST(@numero AS VARCHAR(3)), 3) + '-' + @anio_actual + '-MDE';
    SET @resultado = @formato;

    RETURN @resultado;
END;
GO

--Funcion para mostrar el tiempo de mantenimiento
CREATE OR ALTER FUNCTION dbo.fn_tiempo_mantenimiento (
    @ASI_fecha DATE, 
    @ASI_hora TIME(0), 
    @MAN_fecha DATE, 
    @MAN_hora TIME(0)
)
RETURNS VARCHAR(100)
AS
BEGIN
    DECLARE @FechaHoraAsignacion DATETIME;
    DECLARE @FechaHoraMantenimiento DATETIME;
    DECLARE @DiferenciaDias INT;
    DECLARE @DiferenciaHoras INT;
    DECLARE @DiferenciaMinutos INT;
    DECLARE @Resultado VARCHAR(100);

    -- Combinar fecha y hora de asignación en un solo valor de tipo DATETIME
    SET @FechaHoraAsignacion = CAST(@ASI_fecha AS DATETIME) + CAST(@ASI_hora AS DATETIME);

    -- Combinar fecha y hora de mantenimiento en un solo valor de tipo DATETIME
    SET @FechaHoraMantenimiento = CAST(@MAN_fecha AS DATETIME) + CAST(@MAN_hora AS DATETIME);

    -- Calcular la diferencia en días
    SET @DiferenciaDias = DATEDIFF(DAY, @FechaHoraAsignacion, @FechaHoraMantenimiento);

    -- Calcular la diferencia total en minutos
    SET @DiferenciaMinutos = DATEDIFF(MINUTE, @FechaHoraAsignacion, @FechaHoraMantenimiento);

    -- Calcular las horas y minutos restantes (diferencia después de calcular los días)
    SET @DiferenciaHoras = (@DiferenciaMinutos % (60 * 24)) / 60;  -- Horas después de descontar días
    SET @DiferenciaMinutos = @DiferenciaMinutos % 60;  -- Minutos restantes después de descontar horas

    -- Verificar si hay días y formatear el resultado adecuadamente
    IF @DiferenciaDias > 0
    BEGIN
        SET @Resultado = CAST(@DiferenciaDias AS VARCHAR(10)) + ' días ' 
                        + CAST(@DiferenciaHoras AS VARCHAR(10)) + ' horas y ' 
                        + CAST(@DiferenciaMinutos AS VARCHAR(10)) + ' minutos';
    END
    ELSE
    BEGIN
        SET @Resultado = CAST(@DiferenciaHoras AS VARCHAR(10)) + ' horas y ' 
                        + CAST(@DiferenciaMinutos AS VARCHAR(10)) + ' minutos';
    END

    -- Devolver la diferencia en el formato adecuado
    RETURN @Resultado;
END;
GO

-- Crear el trigger para insertar el area inicial con código 0
CREATE OR ALTER TRIGGER trg_incrementar_codigoArea
ON AREA
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_codigo SMALLINT;
    DECLARE @existe_area_inicial BIT;

    -- Verificar si ya existe el área inicial con ARE_codigo = 0
    SELECT @existe_area_inicial = CASE WHEN EXISTS (SELECT 1 FROM AREA WHERE ARE_codigo = 0) THEN 1 ELSE 0 END;

    -- Si no existe el área inicial, insertar el area con código 0
    IF @existe_area_inicial = 0
    BEGIN
        INSERT INTO AREA (ARE_codigo, ARE_nombre, EST_codigo)
        VALUES (0, 'General', 1);
    END

    -- Obtener el último codigo del área despues de insertar el area inicial (si es necesario)
    SELECT @ultimo_codigo = ISNULL(MAX(ARE_codigo), 0) FROM AREA;

    -- Insertar el nuevo registro con el codigo de área aumentado en 1 solo si no se está insertando el area inicial
    INSERT INTO AREA (ARE_codigo, ARE_nombre, EST_codigo)
    SELECT @ultimo_codigo + 1, ARE_nombre, EST_codigo
    FROM inserted
    WHERE NOT (@existe_area_inicial = 0 AND ARE_codigo = 0);
END;
GO


-- Trigger para incrementar el numero de incidencia en 1
CREATE OR ALTER TRIGGER trg_incrementar_numeroIncidencia
ON INCIDENCIA
INSTEAD OF INSERT
AS
BEGIN
	DECLARE @ultimo_numero SMALLINT;
    
	-- Obtener el ultimo numero de incidencia
	SELECT @ultimo_numero = ISNULL(MAX(INC_numero), 0) FROM INCIDENCIA;
    
	-- Insertar el nuevo registro con INC_numero incrementado en 1
	INSERT INTO INCIDENCIA (INC_numero, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo)
	SELECT @ultimo_numero + 1, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo
	FROM inserted;
END;
GO

-- Trigger para aumentar el numero del formato de la incidencia
CREATE OR ALTER TRIGGER trg_UpdateNumeroFormato
ON INCIDENCIA
AFTER INSERT
AS
BEGIN
    UPDATE INCIDENCIA
    SET INC_numero_formato = dbo.GenerarNumeroIncidencia()
    FROM INCIDENCIA i
    INNER JOIN inserted ins
    ON i.INC_numero = ins.INC_numero
    WHERE i.INC_numero_formato IS NULL; 
END;
GO

-- Trigger para aumentar el numero de recepcion en 1 
CREATE OR ALTER TRIGGER trg_incrementar_numeroRecepcion
ON RECEPCION
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el ultimo numero de recepcion
    SELECT @ultimo_numero = ISNULL(MAX(REC_numero), 0) FROM RECEPCION;

    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO RECEPCION (REC_numero, REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo)
    SELECT @ultimo_numero + 1, REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo
    FROM inserted;
END;
GO

-- Trigger para aumentar el numero de asignacion en 1
CREATE OR ALTER TRIGGER trg_incrementar_numeroAsignacion
ON ASIGNACION
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el ultimo numero de asignacion
    SELECT @ultimo_numero = ISNULL(MAX(ASI_codigo), 0) FROM ASIGNACION;

    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO ASIGNACION (ASI_codigo, ASI_fecha, ASI_hora, EST_codigo, USU_codigo, REC_numero)
    SELECT @ultimo_numero + 1, ASI_fecha, ASI_hora, EST_codigo, USU_codigo, REC_numero
    FROM inserted;
END;
GO

-- Trigger para generar el numero de mantenimiento aumentado en 1
CREATE OR ALTER TRIGGER trg_incrementar_numeroMantenimiento
ON MANTENIMIENTO
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el ultimo numero de mantenimiento
    SELECT @ultimo_numero = ISNULL(MAX(MAN_codigo), 0) FROM MANTENIMIENTO;

    -- Insertar el nuevo registro con MAN_codigo incrementado en 1
    INSERT INTO MANTENIMIENTO (MAN_codigo, MAN_fecha, MAN_hora, EST_codigo, ASI_codigo)
    SELECT @ultimo_numero + 1, MAN_fecha, MAN_hora, EST_codigo, ASI_codigo
    FROM inserted;
END;
GO

-- Trigger para generar el numero de cierre aumentado en 1
CREATE OR ALTER TRIGGER trg_incrementar_numeroCierre
ON CIERRE
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el ultimo numero de cierre
    SELECT @ultimo_numero = ISNULL(MAX(CIE_numero), 0) FROM CIERRE;
    -- Insertar el nuevo registro con CIE_numero incrementado en 1
    INSERT INTO CIERRE (CIE_numero, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_recomendaciones, CON_codigo, EST_codigo, MAN_codigo, USU_codigo, SOL_codigo)
    SELECT @ultimo_numero + 1, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_recomendaciones, CON_codigo, EST_codigo, MAN_codigo, USU_codigo, SOL_codigo
    FROM inserted;
END;
GO

-------------------------------------------------------------------------------------------------------
  -- PROCEDIMIENTOS ALMACENADOS
-------------------------------------------------------------------------------------------------------
--CREACION DEL USUARIO INCIAL
CREATE OR ALTER PROCEDURE sp_crear_usuario_inicial
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar si ya existe algún usuario
    IF EXISTS (SELECT 1 FROM USUARIO)
    BEGIN
        RAISERROR('Ya existen usuarios en el sistema. No se puede crear el usuario inicial.', 16, 1);
        RETURN;
    END

    -- Datos del usuario inicial
    DECLARE @USU_nombre NVARCHAR(50) = 'admin';
    DECLARE @USU_password NVARCHAR(100) = 'admin123'; 
    DECLARE @ROL_codigo SMALLINT;
    DECLARE @ARE_codigo SMALLINT;

    -- Verificar si existe el rol 'Administrador', si no, crearlo
    IF NOT EXISTS (SELECT 1 FROM ROL WHERE ROL_nombre = 'Administrador')
    BEGIN
        INSERT INTO ROL (ROL_nombre)
        VALUES ('Administrador');
    END

    -- Verificar si existe el área 'General', si no, crearlo
    IF NOT EXISTS (SELECT 1 FROM AREA WHERE ARE_nombre = 'General')
    BEGIN
        INSERT INTO AREA (ARE_codigo, ARE_nombre, EST_codigo)
        VALUES (0, 'General', 1);
    END

    -- Obtener los códigos de rol y área después de la inserción - verificación
    SELECT TOP 1 @ROL_codigo = ROL_codigo FROM ROL WHERE ROL_nombre = 'Administrador';
    SELECT TOP 1 @ARE_codigo = ARE_codigo FROM AREA WHERE ARE_nombre = 'General';

    -- Generar un salt aleatorio
    DECLARE @salt UNIQUEIDENTIFIER = NEWID();
    
    -- Hashear la contraseña
    DECLARE @hashed_password VARBINARY(64);
    DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

    DECLARE @iterations INT = 10000;
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password = HASHBYTES('SHA2_512', @to_hash);
        SET @to_hash = @hashed_password + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- Insertar el usuario inicial con USU_codigo 0
    INSERT INTO USUARIO (USU_codigo, USU_nombre, USU_password, USU_salt, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo, USU_fecha_creacion)
    VALUES (0, @USU_nombre, @hashed_password, @salt, NULL, @ROL_codigo, @ARE_codigo, 1, GETDATE());

    PRINT 'Usuario inicial creado con éxito. Por favor, cambie la contraseña después del primer inicio de sesión.';
END;
GO

--EJECUCION DEL PROCEDIMIENTO ALMANCENADO - CREAR USUARIO INICIAL
EXEC sp_crear_usuario_inicial;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR AREA
CREATE OR ALTER PROCEDURE sp_registrar_area
    @NombreArea VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;

        -- Inserta el area con ARE_estado siempre en 1
        INSERT INTO AREA (ARE_nombre, EST_codigo)
        VALUES (@NombreArea, 1);

        -- Confirmar la transaccion si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transaccion en caso de error
        ROLLBACK TRANSACTION;

        -- Mostrar mensaje de error
        DECLARE @ErrorMessage NVARCHAR(4000);
        DECLARE @ErrorSeverity INT;
        DECLARE @ErrorState INT;

        SELECT 
            @ErrorMessage = ERROR_MESSAGE(), 
            @ErrorSeverity = ERROR_SEVERITY(), 
            @ErrorState = ERROR_STATE();

        -- Lanzar el error capturado
        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

--VOLCADO DE DATOS PARA LAS AREAS
EXEC sp_registrar_area 'Subgerencia de Informática y Sistemas';
EXEC sp_registrar_area 'Gerencia Municipal';
EXEC sp_registrar_area 'Subgerencia de Contabilidad';
EXEC sp_registrar_area 'Alcaldía';
EXEC sp_registrar_area 'Subgerencia de Tesorería';
EXEC sp_registrar_area 'Sección de Almacén';
EXEC sp_registrar_area 'Subgerencia de Abastecimiento y Control Patrimonial';
EXEC sp_registrar_area 'Unidad de Control Patrimonial';
EXEC sp_registrar_area 'Caja General';
EXEC sp_registrar_area 'Gerencia de Recursos Humanos';
EXEC sp_registrar_area 'Gerencia de Desarrollo Económico Local';
EXEC sp_registrar_area 'Área de Liquidación de Obras';
EXEC sp_registrar_area 'Subgerencia de Habilitación Urbana y Catastro';
EXEC sp_registrar_area 'Subgerencia de Escalafón';
EXEC sp_registrar_area 'Secretaría General';
EXEC sp_registrar_area 'Unidad de Programa de Vaso de Leche';
EXEC sp_registrar_area 'DEMUNA';
EXEC sp_registrar_area 'OMAPED';
EXEC sp_registrar_area 'Subgerencia de Salud';
EXEC sp_registrar_area 'Gerencia de Administración Tributaria';
EXEC sp_registrar_area 'Servicio Social';
EXEC sp_registrar_area 'Unidad de Relaciones Públicas y Comunicaciones';
EXEC sp_registrar_area 'Gerencia de Gestión Ambiental';
EXEC sp_registrar_area 'Gerencia de Asesoría Jurídica';
EXEC sp_registrar_area 'Subgerencia de Planificación y Modernización Institucional';
EXEC sp_registrar_area 'Subgerencia de Gestión y Desarrollo de RR.HH.';
EXEC sp_registrar_area 'Gerencia de Desarrollo Social y Promoción de la Familia';
EXEC sp_registrar_area 'Subgerencia de Educación';
EXEC sp_registrar_area 'Subgerencia de Programas Sociales e Inclusión';
EXEC sp_registrar_area 'Subgerencia de Licencias';
EXEC sp_registrar_area 'Unidad de Policía Municipal';
EXEC sp_registrar_area 'Unidad de Registro Civil';
EXEC sp_registrar_area 'Subgerencia de Mantenimiento de Obras Públicas';
EXEC sp_registrar_area 'Gerencia de Desarrollo Urbano y Planeamiento Territorial';
EXEC sp_registrar_area 'Unidad de Ejecución Coactiva';
EXEC sp_registrar_area 'Subgerencia de Estudios y Proyectos';
EXEC sp_registrar_area 'Subgerencia de Obras';
EXEC sp_registrar_area 'Procuradoría Pública Municipal';
EXEC sp_registrar_area 'Gerencia de Administración y Finanzas';
EXEC sp_registrar_area 'Subgerencia de Defensa Civil';
EXEC sp_registrar_area 'Subgerencia de Juventud, Deporte y Cultura';
EXEC sp_registrar_area 'Subgerencia de Áreas Verdes';
EXEC sp_registrar_area 'Subgerencia de Seguridad Ciudadana';
EXEC sp_registrar_area 'Órgano de Control Institucional';
EXEC sp_registrar_area 'Unidad Local de Empadronamiento - ULE';
EXEC sp_registrar_area 'Unidad de Atención al Usuario y Trámite Documentario';
EXEC sp_registrar_area 'Gerencia de Seguridad Ciudadana, Defensa Civil y Tránsito';
EXEC sp_registrar_area 'Subgerencia de Abastecimiento';
EXEC sp_registrar_area 'Unidad de Participación Vecinal';
EXEC sp_registrar_area 'Gerencia de Planeamiento, Presupuesto y Modernización';
EXEC sp_registrar_area 'Subgerencia de Transporte, Tránsito y Seguridad Vial';
EXEC sp_registrar_area 'Archivo Central';
EXEC sp_registrar_area 'Equipo Mecánico y Maestranza';
EXEC sp_registrar_area 'Subgerencia de Limpieza Pública';
EXEC sp_registrar_area 'Subgerencia de Bienestar Social';
EXEC sp_registrar_area 'Orientación Tributaria';
EXEC sp_registrar_area 'Servicios Generales';
EXEC sp_registrar_area 'Secretaría Técnica de Procesos Administrativos Disciplinarios';
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR AREA
CREATE OR ALTER PROCEDURE sp_deshabilitar_area
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR AREA
CREATE OR ALTER PROCEDURE sp_habilitar_area
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR USUARIO
CREATE OR ALTER PROCEDURE sp_registrar_usuario (
    @USU_nombre NVARCHAR(50),
    @USU_password NVARCHAR(100),
    @PER_codigo SMALLINT,
    @ROL_codigo SMALLINT,
    @ARE_codigo SMALLINT
)
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar si la persona ya tiene un usuario registrado
    IF EXISTS (SELECT 1 FROM USUARIO WHERE PER_codigo = @PER_codigo)
    BEGIN
        RAISERROR('La persona ya tiene un usuario registrado.', 16, 1);
        RETURN;
    END

    -- Generar un salt aleatorio
    DECLARE @salt UNIQUEIDENTIFIER = NEWID();
    
    -- Hashear la contraseña utilizando PBKDF2
    DECLARE @hashed_password VARBINARY(64);
    DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

    DECLARE @iterations INT = 10000;
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password = HASHBYTES('SHA2_512', @to_hash);
        SET @to_hash = @hashed_password + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- Insertar el nuevo usuario usando la secuencia para USU_codigo
    INSERT INTO USUARIO (USU_codigo, USU_nombre, USU_password, USU_salt, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
    VALUES (NEXT VALUE FOR seq_usuario_codigo, @USU_nombre, @hashed_password, @salt, @PER_codigo, @ROL_codigo, @ARE_codigo, 1);
END;
GO

--VOLCADO DE DATOS PARA LOS USUARIOS EJECUTANDO sp_registrar_usuario
EXEC sp_registrar_usuario 'JCASTRO', 'garbalenus', 1, 1, 1;
EXEC sp_registrar_usuario 'PERCY', '123456', 2, 2, 1;
EXEC sp_registrar_usuario 'ACOLLANTES', '123456', 3, 2, 1;
EXEC sp_registrar_usuario 'FBENITES', 'mde123', 4, 2, 1;
EXEC sp_registrar_usuario 'CLEYVA', '123456', 5, 2, 1;
EXEC sp_registrar_usuario 'MBLAS', '123456', 6, 2, 1;
EXEC sp_registrar_usuario 'SFABIAN', '123456', 7, 2, 1;
EXEC sp_registrar_usuario 'JMANTILLA', '123456', 8, 2, 1;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR USUARIO
CREATE OR ALTER PROCEDURE sp_habilitar_usuario
    @codigoUsuario SMALLINT
AS
BEGIN
    -- Actualizar el estado del usuario
    UPDATE USUARIO 
    SET EST_codigo = 1
    WHERE EST_codigo = 2 AND USU_codigo = @codigoUsuario;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR USUARIO
CREATE OR ALTER PROCEDURE sp_deshabilitar_usuario
	@codigoUsuario SMALLINT
AS
BEGIN
    -- Actualizar el estado del usuario
	UPDATE USUARIO SET EST_codigo = 2 
    WHERE EST_codigo = 1 AND  USU_codigo = @codigoUsuario;
END;
GO

--EJECUTAR sp_deshabilitar_usuario
EXEC sp_deshabilitar_usuario 2;
EXEC sp_deshabilitar_usuario 6;
EXEC sp_deshabilitar_usuario 7; 
GO 

--PROCEDIMIENTO ALMACENADO PARA INCIAR SESION
CREATE OR ALTER PROCEDURE sp_login 
    @USU_usuario NVARCHAR(50),
    @USU_password NVARCHAR(100),
    @DNI_ultimos2 CHAR(2) 
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @USU_codigo INT;
    DECLARE @EST_codigo SMALLINT;
    DECLARE @stored_password VARBINARY(64);
    DECLARE @salt UNIQUEIDENTIFIER;
    DECLARE @PER_dni CHAR(8);

    -- Obtener información del usuario
    SELECT @USU_codigo = u.USU_codigo, 
           @EST_codigo = u.EST_codigo, 
           @stored_password = u.USU_password,
           @salt = u.USU_salt,
           @PER_dni = p.PER_dni
    FROM USUARIO u
    LEFT JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
    WHERE u.USU_nombre = @USU_usuario;

    -- Verificar si el usuario existe y está activo
    IF @USU_codigo IS NOT NULL AND @EST_codigo = 1
    BEGIN
        -- Hashear la contraseña ingresada
        DECLARE @hashed_password VARBINARY(64);
        DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password);
        DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
        DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

        DECLARE @iterations INT = 10000;
        WHILE @iterations > 0
        BEGIN
            SET @hashed_password = HASHBYTES('SHA2_512', @to_hash);
            SET @to_hash = @hashed_password + @salt_bytes;
            SET @iterations = @iterations - 1;
        END

        -- Verificar si las credenciales son correctas
        IF @hashed_password = @stored_password
        BEGIN
            -- Verificar si el usuario es 'ADMIN'
            IF LOWER(@USU_usuario) = 'admin'
            BEGIN
                -- Devolver datos del usuario para la sesión sin validar los últimos 2 dígitos del DNI
                SELECT 
                    u.USU_codigo,
                    u.USU_nombre, 
                    ISNULL(p.PER_nombres, 'Admin') AS PER_nombres, 
                    ISNULL(p.PER_apellidoPaterno, 'System') AS PER_apellidoPaterno, 
                    ISNULL(r.ROL_codigo, 1) AS ROL_codigo, 
                    ISNULL(r.ROL_nombre, 'Administrador') AS ROL_nombre, 
                    ISNULL(a.ARE_codigo, 0) AS ARE_codigo,
                    ISNULL(a.ARE_nombre, 'General') AS ARE_nombre,
                    u.EST_codigo
                FROM USUARIO u
                LEFT JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
                INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
                INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
                WHERE u.USU_codigo = @USU_codigo;

                -- Actualizar la fecha de último acceso
                UPDATE USUARIO SET USU_ultimo_acceso = GETDATE() WHERE USU_codigo = @USU_codigo;
            END
            ELSE
            BEGIN
                -- Verificar los últimos 2 dígitos del DNI para usuarios que no son 'ADMIN'
                IF RIGHT(@PER_dni, 2) = @DNI_ultimos2
                BEGIN
                    -- Devolver datos del usuario para la sesión
                    SELECT 
                        u.USU_codigo,
                        u.USU_nombre, 
                        ISNULL(p.PER_nombres, 'Admin') AS PER_nombres, 
                        ISNULL(p.PER_apellidoPaterno, 'System') AS PER_apellidoPaterno, 
                        ISNULL(r.ROL_codigo, 1) AS ROL_codigo, 
                        ISNULL(r.ROL_nombre, 'Administrador') AS ROL_nombre, 
                        ISNULL(a.ARE_codigo, 0) AS ARE_codigo,
                        ISNULL(a.ARE_nombre, 'General') AS ARE_nombre,
                        u.EST_codigo
                    FROM USUARIO u
                    LEFT JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
                    INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
                    INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
                    WHERE u.USU_codigo = @USU_codigo;

                    -- Actualizar la fecha de último acceso
                    UPDATE USUARIO SET USU_ultimo_acceso = GETDATE() WHERE USU_codigo = @USU_codigo;
                END
                ELSE
                BEGIN
                    -- Los dos últimos dígitos del DNI son incorrectos
                    SELECT 'Los dos últimos dígitos del DNI no coinciden.' AS MensajeError;
                END
            END
        END
        ELSE
        BEGIN
            -- Credenciales incorrectas
            SELECT 'Credenciales incorrectas' AS MensajeError;
        END
    END
    ELSE
    BEGIN
        -- Usuario no encontrado o inactivo
        SELECT CASE 
            WHEN @USU_codigo IS NULL THEN 'Usuario no encontrado'
            ELSE 'Usuario inactivo. Por favor, contacte al administrador.'
        END AS MensajeError;
    END
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS PERSONALES DEL USUARIO
CREATE OR ALTER PROCEDURE sp_editar_perfil
  @USU_codigo SMALLINT,
  @PER_nombres VARCHAR(20),
  @PER_apellidoPaterno VARCHAR(15),
  @PER_apellidoMaterno VARCHAR(15),
  @PER_celular CHAR(9),
  @PER_email VARCHAR(45)
AS
BEGIN
  BEGIN TRY
    BEGIN TRANSACTION; -- Inicia una transacción para asegurar la consistencia de los datos
      -- Actualiza los datos de la persona vinculada al usuario
      UPDATE PERSONA
      SET 
          PER_nombres = @PER_nombres,
          PER_apellidoPaterno = @PER_apellidoPaterno,
          PER_apellidoMaterno = @PER_apellidoMaterno,
          PER_celular = @PER_celular,
          PER_email = @PER_email
      WHERE PER_codigo = (
          SELECT PER_codigo 
          FROM USUARIO 
          WHERE USU_codigo = @USU_codigo
      );
    COMMIT TRANSACTION; 
  END TRY
  BEGIN CATCH   
    ROLLBACK TRANSACTION; 
    DECLARE @ErrorMessage NVARCHAR(4000);
    DECLARE @ErrorSeverity INT;
    DECLARE @ErrorState INT;

    SELECT 
        @ErrorMessage = ERROR_MESSAGE(),
        @ErrorSeverity = ERROR_SEVERITY(),
        @ErrorState = ERROR_STATE();

    RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
  END CATCH
END;
GO

--PROCEDIMIENTO ALMANCENADO PARA VERIFICAR USUARIO
CREATE OR ALTER PROCEDURE sp_verificar_usuario
    @USU_nombre NVARCHAR(50),
    @USU_password NVARCHAR(100)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @stored_password VARBINARY(64);
    DECLARE @salt UNIQUEIDENTIFIER;
    DECLARE @USU_codigo INT;

    -- Obtener la contraseña hasheada, el salt y el código del usuario almacenados
    SELECT @stored_password = USU_password, 
           @salt = USU_salt,
           @USU_codigo = USU_codigo
    FROM USUARIO
    WHERE USU_nombre = @USU_nombre;

    -- Si no se encuentra el usuario, terminar
    IF @stored_password IS NULL
    BEGIN
        SELECT 'Usuario no encontrado' AS Resultado;
        RETURN;
    END

    -- Hashear la contraseña proporcionada
    DECLARE @hashed_password VARBINARY(64);
    DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

    DECLARE @iterations INT = 10000;
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password = HASHBYTES('SHA2_512', @to_hash);
        SET @to_hash = @hashed_password + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- Comparar las contraseñas hasheadas
    IF @hashed_password = @stored_password
    BEGIN
        SELECT @USU_codigo AS codigo, 
               @USU_nombre AS usuario, 
               'Autenticación exitosa' AS Resultado;
    END
    ELSE
    BEGIN
        SELECT 'Contraseña incorrecta' AS Resultado;
    END
END;
GO

--PROCEDIMIENTO ALMACENADO PARA CAMBIAR CONTRASEÑA
CREATE OR ALTER PROCEDURE sp_cambiar_contrasena
    @USU_codigo INT,                       -- Código del usuario
    @USU_password_actual NVARCHAR(100),    -- Contraseña actual
    @USU_password_nueva NVARCHAR(100),     -- Nueva contraseña
    @USU_password_confirmacion NVARCHAR(100) -- Confirmación de la nueva contraseña
AS
BEGIN
    SET NOCOUNT ON;

    -- 1. Verificar que la nueva contraseña y la confirmación coincidan
    IF @USU_password_nueva != @USU_password_confirmacion
    BEGIN
        SELECT 'La nueva contraseña y la confirmación no coinciden' AS Resultado;
        RETURN;
    END

    -- 2. Obtener el salt y la contraseña hasheada del usuario
    DECLARE @stored_password VARBINARY(64);  -- Contraseña almacenada (hash)
    DECLARE @salt UNIQUEIDENTIFIER;          -- Salt almacenado

    SELECT @stored_password = USU_password, 
           @salt = USU_salt
    FROM USUARIO
    WHERE USU_codigo = @USU_codigo;

    -- Si no se encuentra el usuario, terminar
    IF @stored_password IS NULL
    BEGIN
        SELECT 'Usuario no encontrado' AS Resultado;
        RETURN;
    END

    -- 3. Hashear la nueva contraseña antes de guardarla
    DECLARE @hashed_password_nueva VARBINARY(64);
    DECLARE @nueva_password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password_nueva);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash_nueva VARBINARY(116) = @nueva_password_bytes + @salt_bytes;

    DECLARE @iterations INT = 10000;
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password_nueva = HASHBYTES('SHA2_512', @to_hash_nueva);
        SET @to_hash_nueva = @hashed_password_nueva + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- 4. Actualizar la contraseña en la base de datos
    UPDATE USUARIO
    SET USU_password = @hashed_password_nueva
    WHERE USU_codigo = @USU_codigo;

    SELECT 'Contraseña cambiada exitosamente' AS Resultado;
END;
GO


-- PROCEDIMIENTO ALMACENADO PARA RESTABLECER CONTRASEÑA
CREATE OR ALTER PROCEDURE sp_restablecer_contrasena
    @USU_codigo INT,                       -- Código del usuario
    @USU_password_nueva NVARCHAR(100),     -- Nueva contraseña
    @USU_password_confirmacion NVARCHAR(100) -- Confirmación de la nueva contraseña
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar que la nueva contraseña y la confirmación coincidan
    IF @USU_password_nueva != @USU_password_confirmacion
    BEGIN
        SELECT 'La nueva contraseña y la confirmación no coinciden' AS Resultado;
        RETURN;
    END

    -- Generar un nuevo salt para hashear la contraseña
    DECLARE @salt UNIQUEIDENTIFIER = NEWID();

    -- Convertir la nueva contraseña y el salt a varbinary
    DECLARE @hashed_password_nueva VARBINARY(64);
    DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password_nueva);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

    -- Hashear la nueva contraseña usando un bucle de iteraciones (ej. 10000 iteraciones de SHA2_512)
    DECLARE @iterations INT = 10000;
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password_nueva = HASHBYTES('SHA2_512', @to_hash);
        SET @to_hash = @hashed_password_nueva + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- Actualizar la nueva contraseña hasheada y el salt en la base de datos
    UPDATE USUARIO
    SET USU_password = @hashed_password_nueva, USU_salt = @salt
    WHERE USU_codigo = @USU_codigo;

    SELECT 'Contraseña cambiada exitosamente' AS Resultado;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA VERIFICAR SI LA CONTRASEÑA ACTUAL ES CORRECTA
CREATE OR ALTER PROCEDURE sp_verificar_contrasena_actual
    @USU_codigo INT, 
    @USU_password_actual NVARCHAR(100)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @stored_password VARBINARY(64); 
    DECLARE @salt UNIQUEIDENTIFIER;        

    -- Obtener la contraseña hasheada y el salt del usuario
    SELECT @stored_password = USU_password, 
           @salt = USU_salt
    FROM USUARIO
    WHERE USU_codigo = @USU_codigo;

    -- Si no se encuentra el usuario, terminar
    IF @stored_password IS NULL
    BEGIN
        SELECT 'Usuario no encontrado' AS Resultado;
        RETURN;
    END

    -- Hashear la contraseña actual proporcionada por el usuario
    DECLARE @hashed_password VARBINARY(64);
    DECLARE @password_bytes VARBINARY(100) = CONVERT(VARBINARY(100), @USU_password_actual);
    DECLARE @salt_bytes VARBINARY(16) = CAST(@salt AS VARBINARY(16));
    DECLARE @to_hash VARBINARY(116) = @password_bytes + @salt_bytes;

    DECLARE @iterations INT = 10000;  -- Iteraciones del algoritmo de hash
    WHILE @iterations > 0
    BEGIN
        SET @hashed_password = HASHBYTES('SHA2_512', @to_hash);
        SET @to_hash = @hashed_password + @salt_bytes;
        SET @iterations = @iterations - 1;
    END

    -- Comparar las contraseñas hasheadas
    IF @hashed_password = @stored_password
    BEGIN
        SELECT 'Contraseña correcta' AS Resultado;
    END
    ELSE
    BEGIN
        SELECT 'Contraseña incorrecta' AS Resultado;
    END
END;
GO

--PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS DE USUARIO
CREATE OR ALTER PROCEDURE sp_editar_usuario
    @USU_codigo SMALLINT,
    @USU_nombre VARCHAR(20),
    @PER_codigo SMALLINT,
    @ROL_codigo SMALLINT,
    @ARE_codigo SMALLINT
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION;

        -- Verificar si el nombre de usuario ya existe excluyendo el usuario actual
        IF EXISTS (
            SELECT 1 FROM USUARIO 
            WHERE USU_nombre = @USU_nombre AND USU_codigo != @USU_codigo
        )
        BEGIN
            -- En caso de que el nombre ya exista, devolver un error
            RAISERROR('El nombre de usuario ya está en uso.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END
        -- Actualizar los datos del usuario
        UPDATE USUARIO
        SET 
            USU_nombre = @USU_nombre,
            PER_codigo = @PER_codigo,
            ROL_codigo = @ROL_codigo,
            ARE_codigo = @ARE_codigo
        WHERE 
            USU_codigo = @USU_codigo;

        COMMIT TRANSACTION;
        PRINT 'Usuario actualizado correctamente.';
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        PRINT 'Error al actualizar usuario: ' + ERROR_MESSAGE();
    END CATCH
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR PERSONA
CREATE OR ALTER PROCEDURE sp_registrar_persona (
    @PER_dni CHAR(8),
    @PER_nombres VARCHAR(20),
    @PER_apellidoPaterno VARCHAR(15),
    @PER_apellidoMaterno VARCHAR(15),
    @PER_celular CHAR(9),
    @PER_email VARCHAR(45)
)
AS
BEGIN
    -- Insertar la nueva persona
    INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_celular, PER_email)
    VALUES (@PER_dni, @PER_nombres, @PER_apellidoPaterno, @PER_apellidoMaterno, @PER_celular, @PER_email);
    
    -- Retornar el ID de la persona recién insertada
    SELECT SCOPE_IDENTITY() AS PER_codigo;
END
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR BIEN
CREATE OR ALTER PROCEDURE sp_registrar_bien
    @codigoIdentificador VARCHAR(12),
    @NombreBien VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Insertar bien con el estado siempre en 1
        INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo)
        VALUES (@codigoIdentificador, @NombreBien, 1);

        -- Confirmar la transaccion si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transaccion en caso de error
        ROLLBACK TRANSACTION;

        -- Mostrar mensaje de error
        DECLARE @ErrorMessage NVARCHAR(4000);
        DECLARE @ErrorSeverity INT;
        DECLARE @ErrorState INT;

        SELECT 
            @ErrorMessage = ERROR_MESSAGE(), 
            @ErrorSeverity = ERROR_SEVERITY(), 
            @ErrorState = ERROR_STATE();

        -- Lanzar el error capturado
        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- Procedimiento almacenado para editar persona
CREATE OR ALTER PROCEDURE sp_editar_persona
  @PER_codigo SMALLINT,
  @PER_dni CHAR(8),
  @PER_nombres VARCHAR(20),
  @PER_apellidoPaterno VARCHAR(15),
  @PER_apellidoMaterno VARCHAR(15),
  @PER_celular CHAR(9),
  @PER_email VARCHAR(45)
AS
BEGIN
  BEGIN TRY
    BEGIN TRANSACTION;
    -- Actualizar los datos del usuario
    UPDATE PERSONA
    SET 
      PER_nombres = @PER_nombres,
      PER_apellidoPaterno = @PER_apellidoPaterno,
      PER_apellidoMaterno = @PER_apellidoMaterno,
      PER_celular = @PER_celular,
      PER_email = @PER_email
    WHERE 
      PER_codigo = @PER_codigo;

    COMMIT TRANSACTION;
    PRINT 'Persona actualizado correctamente.';
  END TRY
  BEGIN CATCH
    ROLLBACK TRANSACTION;
    PRINT 'Error al actualizar persona: ' + ERROR_MESSAGE();
  END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE OR ALTER PROCEDURE sp_habilitar_bien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  BIE_codigo = @codigoBien;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR BIEN
CREATE OR ALTER PROCEDURE sp_deshabilitar_bien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  BIE_codigo = @codigoBien;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR CATEGORIAS
CREATE OR ALTER PROCEDURE sp_registrar_categoria
    @NombreCategoria VARCHAR(60)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el area con ARE_estado siempre en 1
        INSERT INTO CATEGORIA (CAT_nombre, EST_codigo)
        VALUES (@NombreCategoria, 1);

        -- Confirmar la transaccion si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transaccion en caso de error
        ROLLBACK TRANSACTION;

        -- Mostrar mensaje de error
        DECLARE @ErrorMessage NVARCHAR(4000);
        DECLARE @ErrorSeverity INT;
        DECLARE @ErrorState INT;

        SELECT 
            @ErrorMessage = ERROR_MESSAGE(), 
            @ErrorSeverity = ERROR_SEVERITY(), 
            @ErrorState = ERROR_STATE();

        -- Lanzar el error capturado
        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR CATEGORIA
CREATE OR ALTER PROCEDURE sp_deshabilitar_categoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE OR ALTER PROCEDURE sp_habilitar_categoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR INCIDENCIA
CREATE OR ALTER PROCEDURE sp_habilitar_incidencia
	@codigoIncidencia SMALLINT
AS
BEGIN
	UPDATE INCIDENCIA SET EST_codigo = 3
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  INC_numero = @codigoIncidencia;
END;
GO


-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR INCIDENCIA
CREATE OR ALTER PROCEDURE sp_deshabilitar_incidencia
	@codigoIncidencia SMALLINT
AS
BEGIN
	UPDATE INCIDENCIA SET EST_codigo = 2 
   WHERE (EST_codigo = 3 OR  EST_codigo = '')
	AND  INC_numero = @codigoIncidencia;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR SOLUCIONES
CREATE OR ALTER PROCEDURE sp_registrar_solucion
    @descripcionSolucion VARCHAR(60)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el area con EST_codigo siempre en 1
        INSERT INTO SOLUCION (SOL_descripcion, EST_codigo)
        VALUES (@descripcionSolucion, 1);

        -- Confirmar la transaccion si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transaccion en caso de error
        ROLLBACK TRANSACTION;

        -- Mostrar mensaje de error
        DECLARE @ErrorMessage NVARCHAR(4000);
        DECLARE @ErrorSeverity INT;
        DECLARE @ErrorState INT;

        SELECT 
            @ErrorMessage = ERROR_MESSAGE(), 
            @ErrorSeverity = ERROR_SEVERITY(), 
            @ErrorState = ERROR_STATE();

        -- Lanzar el error capturado
        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR SOLUCION
CREATE OR ALTER PROCEDURE sp_habilitar_solucion
	@codigoSolucion SMALLINT
AS
BEGIN
	UPDATE SOLUCION SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  SOL_codigo = @codigoSolucion;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR SOLUCION
CREATE OR ALTER PROCEDURE sp_deshabilitar_solucion
	@codigoSolucion SMALLINT
AS
BEGIN
	UPDATE SOLUCION SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  SOL_codigo = @codigoSolucion;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR INCIDENCIA - ADMINISTRADOR / USUARIO
CREATE OR ALTER PROCEDURE sp_registrar_incidencia
  @INC_fecha DATE,
  @INC_hora TIME,
  @INC_asunto VARCHAR(500),
  @INC_descripcion VARCHAR(800),
  @INC_documento VARCHAR(500),
  @INC_codigoPatrimonial CHAR(12) = NULL,
  @CAT_codigo SMALLINT,
  @ARE_codigo SMALLINT,
  @USU_codigo SMALLINT
AS 
BEGIN 
  DECLARE @numero_formato VARCHAR(20);  -- N�mero de incidencia

  -- Verificar si ya existe una incidencia similar
  IF NOT EXISTS (
      SELECT 1 
      FROM INCIDENCIA 
      WHERE 
          INC_fecha = @INC_fecha 
          AND INC_hora = @INC_hora 
          AND INC_asunto = @INC_asunto 
          AND INC_descripcion = @INC_descripcion 
          AND INC_documento = @INC_documento
          AND (INC_codigoPatrimonial = @INC_codigoPatrimonial OR (@INC_codigoPatrimonial IS NULL AND INC_codigoPatrimonial IS NULL))
          AND CAT_codigo = @CAT_codigo 
          AND ARE_codigo = @ARE_codigo 
          AND USU_codigo = @USU_codigo
  )
  BEGIN
      -- Generar el numero de incidencia formateado
      SET @numero_formato = dbo.GenerarNumeroIncidencia();

      -- Insertar la nueva incidencia
      INSERT INTO INCIDENCIA (INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo,  USU_codigo, INC_numero_formato)
      VALUES (@INC_fecha, @INC_hora, @INC_asunto, @INC_descripcion, @INC_documento, @INC_codigoPatrimonial, 3, @CAT_codigo, @ARE_codigo, @USU_codigo, @numero_formato);
  
  END
  ELSE
  BEGIN
      -- RETORNAR MENSAJE QUE LA INCIDENCIA YA EXISTE
      PRINT 'La incidencia ya existe y no se puede registrar nuevamente.';
  END
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR INCIDENCIA - ADMINISTRADOR
CREATE OR ALTER PROCEDURE sp_actualizar_incidencia
  @INC_numero SMALLINT,
  @CAT_codigo SMALLINT,
  @ARE_codigo SMALLINT,
  @INC_codigoPatrimonial CHAR(12),
  @INC_asunto VARCHAR(500),
  @INC_documento VARCHAR(500),
  @INC_descripcion VARCHAR(800)
AS
BEGIN
  -- Actualizar el registro en la tabla INCIDENCIA solo si el estado es 3
  UPDATE INCIDENCIA
  SET CAT_codigo = @CAT_codigo,
      ARE_codigo = @ARE_codigo,
      INC_codigoPatrimonial = @INC_codigoPatrimonial,
      INC_asunto = @INC_asunto,
      INC_documento = @INC_documento,
      INC_descripcion = @INC_descripcion
  WHERE INC_numero = @INC_numero
  AND EST_codigo = 3;
END;
GO 

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR INCIDENCIA - USUARIO
CREATE OR ALTER PROCEDURE sp_actualizar_incidencia_usuario
  @INC_numero SMALLINT,
  @CAT_codigo SMALLINT,
  @INC_codigoPatrimonial CHAR(12),
  @INC_asunto VARCHAR(500),
  @INC_documento VARCHAR(500),
  @INC_descripcion VARCHAR(800)
AS
BEGIN
  -- Actualizar el registro en la tabla INCIDENCIA solo si el estado es 3
  UPDATE INCIDENCIA
  SET CAT_codigo = @CAT_codigo,
      INC_codigoPatrimonial = @INC_codigoPatrimonial,
      INC_asunto = @INC_asunto,
      INC_documento = @INC_documento,
      INC_descripcion = @INC_descripcion
  WHERE INC_numero = @INC_numero
  AND EST_codigo = 3;
END;
GO 

-- PROCEDIMIENTO ALMACENADO PARA ELIMINAR INCIDENCIA
CREATE OR ALTER PROCEDURE sp_eliminar_incidencia
    @NumeroIncidencia INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        -- Eliminar la incidencia basada en el numero de incidencia
        DELETE FROM INCIDENCIA
        WHERE INC_numero = @NumeroIncidencia;

        -- Confirmar la transaccion
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA INSERTAR LA RECEPCION Y ACTUALIZAR ESTADO DE INCIDENCIA
CREATE OR ALTER PROCEDURE sp_insertar_recepcion (
    @REC_fecha DATE,
    @REC_hora TIME,
    @INC_numero INT,
    @PRI_codigo INT,
    @IMP_codigo INT,
    @USU_codigo INT
)
AS 
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY 
        BEGIN TRANSACTION;

        -- Verificar si ya existe una recepcion con los mismos valores
        IF NOT EXISTS (
            SELECT 1 
            FROM RECEPCION 
            WHERE 
                REC_fecha = @REC_fecha 
                AND REC_hora = @REC_hora 
                AND INC_numero = @INC_numero 
                AND PRI_codigo = @PRI_codigo 
                AND IMP_codigo = @IMP_codigo 
                AND USU_codigo = @USU_codigo
        )
        BEGIN
            -- Insertar la nueva recepcion
            INSERT INTO RECEPCION (REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo)
            VALUES (@REC_fecha, @REC_hora, @INC_numero, @PRI_codigo, @IMP_codigo, @USU_codigo, 4);
            
            -- Actualizar el estado de la incidencia a 'recepcionada' (estado 4)
            UPDATE INCIDENCIA 
            SET EST_codigo = 4
            WHERE INC_numero = @INC_numero;
        END
        ELSE
        BEGIN
            -- Si ya existe una recepcion, solo actualizamos el estado de la recepcion
            UPDATE RECEPCION 
            SET EST_codigo = 4
            WHERE 
                REC_fecha = @REC_fecha 
                AND REC_hora = @REC_hora 
                AND INC_numero = @INC_numero 
                AND PRI_codigo = @PRI_codigo 
                AND IMP_codigo = @IMP_codigo 
                AND USU_codigo = @USU_codigo;

            -- Actualizar el estado de la incidencia a 'recepcionada' (estado 4) si aún no está en ese estado
            UPDATE INCIDENCIA 
            SET EST_codigo = 4
            WHERE INC_numero = @INC_numero;
        END

        COMMIT TRANSACTION;
    END TRY 
    BEGIN CATCH 
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR RECEPCION
CREATE OR ALTER PROCEDURE sp_actualizar_recepcion
  @REC_numero SMALLINT,
  @PRI_codigo SMALLINT,
  @IMP_codigo SMALLINT
AS
BEGIN
  -- Actualizar el registro en la tabla RECEPCION solo si el estado es 4
  UPDATE RECEPCION
  SET PRI_codigo = @PRI_codigo,
      IMP_codigo = @IMP_codigo
  WHERE REC_numero = @REC_numero
	AND EST_codigo = 4;
END;
GO 


---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR RECEPCION
CREATE OR ALTER PROCEDURE sp_eliminar_recepcion
    @IdRecepcion INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        DECLARE @NumeroIncidencia INT;

        -- Obtener el numero de incidencia basado en el ID de recepcion
        SELECT @NumeroIncidencia = INC_numero
        FROM RECEPCION
        WHERE REC_numero = @IdRecepcion;

        -- Actualizar el estado de la incidencia a 3 (abierto)
        UPDATE INCIDENCIA
        SET EST_codigo = 3  -- Estado "abierto"
        WHERE INC_numero = @NumeroIncidencia;

        -- Actualizar el estado de la recepción a 2 (inactivo)
        UPDATE RECEPCION
        SET EST_codigo = 2  -- Estado "inactivo"
        WHERE REC_numero = @IdRecepcion;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR ASINACION - ADMINISTRADOR / SOPORTE
CREATE OR ALTER PROCEDURE sp_registrar_asignacion
    @ASI_fecha DATE,
    @ASI_hora TIME,
    @USU_codigo SMALLINT,
    @REC_numero SMALLINT
AS 
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY 
        -- Iniciar la transacción
        BEGIN TRANSACTION;

        -- Verificar si ya existe una incidencia asignada con los mismos valores
        IF NOT EXISTS (
            SELECT 1 
            FROM ASIGNACION 
            WHERE 
                ASI_fecha = @ASI_fecha 
                AND ASI_hora = @ASI_hora 
                AND USU_codigo = @USU_codigo 
                AND REC_numero = @REC_numero 
        )
        BEGIN
            -- Insertar la nueva asignación
            INSERT INTO ASIGNACION (ASI_fecha, ASI_hora, USU_codigo, REC_numero, EST_codigo)
            VALUES (@ASI_fecha, @ASI_hora, @USU_codigo, @REC_numero, 5); -- ESTADO "EN ESPERA"
            
             -- Actualizar el estado de la recepcion a 'en espera' (estado 5)
            UPDATE RECEPCION 
            SET EST_codigo = 5
            WHERE REC_numero = @REC_numero;
        END;

        ELSE
        BEGIN
            -- Si ya existe una asignacion, solo actualizamos el estado de la asignacion
            UPDATE ASIGNACION 
            SET EST_codigo = 5
            WHERE 
                 ASI_fecha = @ASI_fecha 
                AND ASI_hora = @ASI_hora 
                AND USU_codigo = @USU_codigo 
                AND REC_numero = @REC_numero;

            -- Actualizar el estado de la recepcion a 'en espera' (estado 5) si aún no está en ese estado
            UPDATE RECEPCION 
            SET EST_codigo = 5
            WHERE REC_numero = @REC_numero;
        END

        COMMIT TRANSACTION;
    END TRY 
    BEGIN CATCH 
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR ASIGNACION
CREATE OR ALTER PROCEDURE sp_actualizar_asignacion
  @ASI_codigo SMALLINT,
  @USU_codigo SMALLINT
AS
BEGIN
  -- Actualizar el registro en la tabla ASIGNACION solo si el estado es 5
  UPDATE ASIGNACION
  SET USU_codigo = @USU_codigo
  WHERE ASI_codigo = @ASI_codigo
	AND EST_codigo = 5;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR EL MANTENIMIENTO DE LA INCIDENCIA
CREATE OR ALTER PROCEDURE sp_resolver_incidencia
    @ASI_codigo SMALLINT
AS 
BEGIN
    -- Declarar variables para almacenar la fecha y la hora del sistema
    DECLARE @FechaSistema DATE = CONVERT(DATE, GETDATE());
    DECLARE @HoraSistema TIME(0) = CONVERT(TIME(0), GETDATE()); -- Hora en formato hh:mm:ss

    SET NOCOUNT ON;

    BEGIN TRY 
        BEGIN TRANSACTION;

        -- Verificar si ya existe un registro con los mismos valores
        IF NOT EXISTS (
            SELECT 1 
            FROM MANTENIMIENTO 
            WHERE 
                MAN_fecha = @FechaSistema 
                AND MAN_hora = @HoraSistema 
                AND ASI_codigo = @ASI_codigo
        )
        BEGIN
            -- Insertar la nueva recepción con el estado "ATENDIDO"
            INSERT INTO MANTENIMIENTO (MAN_fecha, MAN_hora, EST_codigo, ASI_codigo)
            VALUES (@FechaSistema, @HoraSistema, 6, @ASI_codigo); 

            -- Actualizar estado de la asignacion
            UPDATE ASIGNACION SET EST_codigo = 6
            WHERE (EST_codigo = 5 OR  EST_codigo = '')
	          AND  ASI_codigo = @ASI_codigo;        
        END
        ELSE
        BEGIN
            -- Mensaje que la recepción ya existe
            PRINT 'La incidencia ya está en mantenimiento y no se puede registrar nuevamente.';
        END

        COMMIT TRANSACTION;
    END TRY 
    BEGIN CATCH 
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ENCOLAR INCIDENCIA
CREATE OR ALTER PROCEDURE sp_encolar_incidencia
    @ASI_codigo SMALLINT
AS 
BEGIN
    SET NOCOUNT ON;

    BEGIN TRY 
        BEGIN TRANSACTION;

        -- Verificar si existe un registro con el código de asignación
        IF EXISTS (
            SELECT 1 
            FROM MANTENIMIENTO 
            WHERE ASI_codigo = @ASI_codigo
        )
        BEGIN
            -- Eliminar el último registro del mantenimiento
            DELETE FROM MANTENIMIENTO 
            WHERE ASI_codigo = @ASI_codigo;

            -- Actualizar el estado de la asignación a "EN ESPERA"
            UPDATE ASIGNACION 
            SET EST_codigo = 5
            WHERE (EST_codigo = 6 OR EST_codigo = '')
            AND ASI_codigo = @ASI_codigo;        
        END
        ELSE
        BEGIN
            -- Mensaje indicando que la incidencia no tiene mantenimiento activo
            PRINT 'La incidencia no tiene mantenimiento activo para ser encolada.';
        END

        COMMIT TRANSACTION;
    END TRY 
    BEGIN CATCH 
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END;
GO


-- PROCEDIMIENTO ALMAENADO PARA INSERTAR CIERRES Y ACTUALIZAR ESTADO DE ASIGNACION
CREATE OR ALTER PROCEDURE sp_registrar_cierre
  @CIE_fecha DATE,
  @CIE_hora TIME,
  @CIE_diagnostico VARCHAR(1000),
  @CIE_documento VARCHAR(500),
  @CIE_recomendaciones VARCHAR(1000),
  @CON_codigo SMALLINT,
  @MAN_codigo SMALLINT,
  @USU_codigo SMALLINT,
  @SOL_codigo SMALLINT
AS BEGIN
SET 
	NOCOUNT ON;
  BEGIN TRY 
    BEGIN TRANSACTION;

	  -- VERIFICAR SI YA EXISTE UN CIERRE CON LOS MISMOS VALORES
        IF NOT EXISTS (
            SELECT 1 
            FROM CIERRE 
            WHERE 
                CIE_fecha = @CIE_fecha 
                AND CIE_hora = @CIE_hora 
                AND CIE_diagnostico = @CIE_diagnostico 
                AND CIE_documento = @CIE_documento 
                AND CIE_recomendaciones = @CIE_recomendaciones
                AND CON_codigo = @CON_codigo
                AND MAN_codigo = @MAN_codigo
                AND USU_codigo = @USU_codigo
				AND SOL_codigo = @SOL_codigo
        )
		BEGIN
			-- Insertar el nuevo cierre
			INSERT INTO CIERRE (CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_recomendaciones, CON_codigo, MAN_codigo, USU_codigo, SOL_codigo, EST_codigo)
			VALUES (@CIE_fecha, @CIE_hora , @CIE_diagnostico, @CIE_documento, @CIE_recomendaciones, @CON_codigo, @MAN_codigo, @USU_codigo, @SOL_codigo, 7);
    
			-- Actualizar el estado de la recepcion
			UPDATE MANTENIMIENTO SET EST_codigo = 7
			WHERE MAN_codigo = @MAN_codigo;
		END
        ELSE
        BEGIN
		     -- MENSAJE QUE EL CIERRE YA EXISTE
            PRINT 'El cierre ya existe y no se puede registrar nuevamente.';
        END
		COMMIT TRANSACTION;
  END TRY 
  BEGIN CATCH 
    ROLLBACK TRANSACTION;
    THROW;
  END CATCH
END;
GO

---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR CIERRE
CREATE OR ALTER PROCEDURE sp_eliminar_cierre
    @IdCierre INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        DECLARE @NumeroMantenimiento INT;

        -- Obtener el número de asignación basado en el ID de cierre
        SELECT @NumeroMantenimiento = MAN_codigo
        FROM CIERRE
        WHERE CIE_numero = @IdCierre;

        -- Actualizar el estado de la asignación a 5 (o el estado que desees)
        UPDATE MANTENIMIENTO
        SET EST_codigo = 6
        WHERE MAN_codigo = @NumeroMantenimiento;

        -- Eliminar el registro en la tabla CIERRE basado en el ID de cierre
        DELETE FROM CIERRE
        WHERE CIE_numero = @IdCierre;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR CIERRES
CREATE OR ALTER PROCEDURE sp_actualizar_cierre
	@CIE_numero SMALLINT,
	@CIE_documento VARCHAR(500),
	@CON_codigo SMALLINT,
	@SOL_codigo SMALLINT,
	@CIE_diagnostico VARCHAR(1000),
	@CIE_recomendaciones VARCHAR(1000)
AS
BEGIN
	-- Actualizar el registro de la tala CIERRE
	UPDATE CIERRE
	SET
		CIE_documento  = @CIE_documento,
		CON_codigo =  @CON_codigo,
		SOL_codigo = @SOL_codigo,
		CIE_diagnostico = @CIE_diagnostico,
		CIE_recomendaciones = @CIE_recomendaciones
	WHERE CIE_numero = @CIE_numero;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR INCIDENCIAS TOTALES - ADMINISTRADOR
CREATE OR ALTER PROCEDURE sp_consultar_incidencias_totales
  @area INT = NULL,
  @codigoPatrimonial CHAR(12) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    INC_numero,
    INC_numero_formato,
    fechaIncidenciaFormateada,
    ARE_nombre,
    CAT_nombre,
    INC_asunto,
    INC_codigoPatrimonial,
    BIE_nombre,
    INC_documento,
    fechaRecepcionFormateada,
    PRI_nombre,
    IMP_descripcion,
    fechaCierreFormateada,
    CON_descripcion,
    USU_nombre,
    Estado,
    ultimaFecha,
    ultimaHora
  FROM vw_incidencias_totales_administrador
  WHERE (@codigoPatrimonial IS NULL OR INC_codigoPatrimonial = @codigoPatrimonial)
    AND (@fechaInicio IS NULL OR ultimaFecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR ultimaFecha <= @fechaFin)
    AND (@area IS NULL OR ARE_nombre = (SELECT ARE_nombre FROM AREA WHERE ARE_codigo = @area))
  ORDER BY ultimaFecha DESC, ultimaHora DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO APRA FILTRAR INCIDENCIAS TOTALES POR FECHA - MODULO REPORTE
CREATE OR ALTER PROCEDURE sp_filtrar_incidencias_totales_fecha
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    INC_numero,
    INC_numero_formato,
    INC_fecha,
    fechaIncidenciaFormateada,
    ARE_nombre,
    CAT_nombre,
    INC_asunto,
    INC_codigoPatrimonial,
    BIE_nombre,
    INC_documento,
    fechaRecepcionFormateada,
    PRI_nombre,
    IMP_descripcion,
    fechaCierreFormateada,
    CON_descripcion,
    USU_nombre,
    Estado,
    ultimaFecha,
    ultimaHora
  FROM vw_incidencias_totales_administrador
  WHERE (@fechaInicio IS NULL OR INC_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR INC_fecha <= @fechaFin)
  ORDER BY INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - ADMINISTRADOR
CREATE OR ALTER PROCEDURE sp_consultar_incidencias_pendientes
  @area INT,
  @estado INT,
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT
    INC_numero,
    INC_numero_formato,
    fechaIncidenciaFormateada,
    INC_codigoPatrimonial,
    BIE_nombre,
    INC_asunto,
    INC_documento,
    CAT_nombre,
    ARE_nombre,
    PRI_nombre,
    IMP_descripcion,
    fechaRecepcionFormateada,
    fechaCierreFormateada,
    CON_descripcion,
    USU_nombre,
    Estado,
    EST_codigo,
    ultimaFecha,
    ultimaHora 
  FROM
    vw_incidencias_pendientes 
    WHERE-- Filtros según los parámetros
    ( @estado IS NULL OR EST_codigo = @estado ) 
    AND ( @fechaInicio IS NULL OR ultimaFecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR ultimaFecha <= @fechaFin ) 
    AND ( @area IS NULL OR ARE_nombre = ( SELECT ARE_nombre FROM AREA WHERE ARE_codigo = @area ) ) 
  ORDER BY
    ultimaFecha DESC,
    ultimaHora DESC;
  
  END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR INCIDENCIAS ASIGNADAS
CREATE OR ALTER PROCEDURE sp_consultar_incidencias_asignadas
  @usuario INT = NULL,
  @codigoPatrimonial CHAR(12) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
SELECT 
    I.INC_numero,
    ASI.ASI_codigo,
    I.INC_numero_formato,
    M.MAN_codigo,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
    (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    
    -- Cálculo del tiempo de mantenimiento en segundos
    DATEDIFF(SECOND, 
        CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
        CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
    ) AS tiempoMantenimientoSegundos,

    -- Mostrar solo días, horas, minutos y segundos según sea necesario
    CASE
        -- Si tiene días, mostrar días, horas, minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 86400 
        THEN
            CAST(DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) / 86400 AS VARCHAR) + ' días ' +
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas ' 
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

        -- Si tiene horas pero no días, mostrar horas y minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 3600 
        THEN
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas '
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

       -- Si tiene menos de una hora, mostrar solo minutos
			ELSE
				CAST(DATEDIFF(MINUTE, 
					CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
					CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
				) AS VARCHAR) + 
                CASE 
                    WHEN DATEDIFF(MINUTE, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                                  CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)) = 1 
                    THEN ' minuto '
                    ELSE ' minutos '
                END
    END AS tiempoMantenimientoFormateado, 
    M.MAN_fecha,
    A.ARE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    U.USU_codigo,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
    pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
	CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
	 -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, M.MAN_fecha, ASI.ASI_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, M.MAN_hora, ASI.ASI_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM 
    ASIGNACION ASI
    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
    LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
    LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  WHERE 
    (@usuario IS NULL OR U.USU_codigo = @usuario) 
    AND (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial)
    AND (@fechaInicio IS NULL OR ASI.ASI_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR ASI.ASI_fecha <= @fechaFin)
GROUP BY 
    I.INC_numero, 
    ASI.ASI_codigo, 
    I.INC_numero_formato, 
    M.MAN_codigo, 
    REC_fecha, 
    REC_hora, 
    ASI.ASI_fecha, 
    ASI.ASI_hora, 
    M.MAN_fecha, 
    M.MAN_hora,
    A.ARE_nombre, 
    I.INC_asunto, 
    I.INC_documento, 
    I.INC_codigoPatrimonial, 
    B.BIE_nombre, 
    U.USU_codigo, 
    P.PER_nombres, 
    P.PER_apellidoPaterno, 
    pA.PER_nombres, 
    pA.PER_apellidoPaterno, 
    C.CIE_numero, 
    EC.EST_descripcion, 
    E.EST_descripcion
ORDER BY ultimaFecha DESC, ultimaHora DESC
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA FILTRAR INCIDENCIAS ASIGNADAS
CREATE OR ALTER PROCEDURE sp_filtrar_incidencias_asignadas
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
SELECT 
    I.INC_numero,
    ASI.ASI_codigo,
    I.INC_numero_formato,
    M.MAN_codigo,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
    (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    
    -- Cálculo del tiempo de mantenimiento en segundos
    DATEDIFF(SECOND, 
        CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
        CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
    ) AS tiempoMantenimientoSegundos,

    -- Mostrar solo días, horas, minutos y segundos según sea necesario
    CASE
        -- Si tiene días, mostrar días, horas, minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 86400 
        THEN
            CAST(DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) / 86400 AS VARCHAR) + ' días ' +
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas ' 
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

        -- Si tiene horas pero no días, mostrar horas y minutos
        WHEN DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) >= 3600 
        THEN
            CAST((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) / 3600 AS VARCHAR) + 
            CASE 
                WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) / 3600 = 1 THEN ' hora '
                ELSE ' horas '
            END +
            CAST(((DATEDIFF(SECOND, 
                CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
            ) % 86400) % 3600) / 60 AS VARCHAR) + 
            CASE 
                WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
                    ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
                ELSE ' minutos '
            END

       -- Si tiene menos de una hora, mostrar solo minutos
			ELSE
				CAST(DATEDIFF(MINUTE, 
					CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
					CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
				) AS VARCHAR) + 
                CASE 
                    WHEN DATEDIFF(MINUTE, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
                                  CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)) = 1 
                    THEN ' minuto '
                    ELSE ' minutos '
                END
    END AS tiempoMantenimientoFormateado, 
    M.MAN_fecha,
    A.ARE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    U.USU_codigo,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
    pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
	CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
	 -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, M.MAN_fecha, ASI.ASI_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, M.MAN_hora, ASI.ASI_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM 
    ASIGNACION ASI
    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
    LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
    LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
	LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  WHERE 
    (@usuario IS NULL OR U.USU_codigo = @usuario)
    AND (@fechaInicio IS NULL OR ASI.ASI_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR ASI.ASI_fecha <= @fechaFin)
GROUP BY 
    I.INC_numero, 
    ASI.ASI_codigo, 
    I.INC_numero_formato, 
    M.MAN_codigo, 
    REC_fecha, 
    REC_hora, 
    ASI.ASI_fecha, 
    ASI.ASI_hora, 
    M.MAN_fecha, 
    M.MAN_hora,
    A.ARE_nombre, 
    I.INC_asunto, 
    I.INC_documento, 
    I.INC_codigoPatrimonial, 
    B.BIE_nombre, 
    U.USU_codigo, 
    P.PER_nombres, 
    P.PER_apellidoPaterno, 
    pA.PER_nombres, 
    pA.PER_apellidoPaterno, 
    C.CIE_numero, 
    EC.EST_descripcion, 
    E.EST_descripcion
ORDER BY ultimaFecha DESC, ultimaHora DESC
END;
GO

--PROCEDIMIENTO ALMACENADO PARA CONSULTAR CIERRES
CREATE OR ALTER PROCEDURE sp_consultar_incidencias_cerradas
  @area INT = NULL,
  @codigoPatrimonial CHAR(12) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
	SELECT
		I.INC_numero,
		I.INC_numero_formato,
		(CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + 
		STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
		A.ARE_nombre,
		CAT.CAT_nombre,
		I.INC_asunto,
		I.INC_documento,
		I.INC_codigoPatrimonial,
		B.BIE_nombre,
		PRI.PRI_nombre,
		(CONVERT(VARCHAR(10), C.CIE_fecha, 103) + ' - ' + 
		STUFF(RIGHT('0' + CONVERT(VARCHAR(7), C.CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
		C.CIE_numero,
		C.CIE_diagnostico, 
		C.CIE_recomendaciones,
		C.CIE_documento,
		O.CON_descripcion,
		U.USU_nombre,
		S.SOL_descripcion,
		CASE
			WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
			ELSE E.EST_descripcion
		END AS Estado,
		(P.PER_nombres + ' ' + P.PER_apellidoPaterno) AS Usuario,
		-- Última modificación (fecha y hora más reciente)
		MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
		MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
	FROM CIERRE C
	LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
	LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
	LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
	INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
	RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
	LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
	INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
	INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
	INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
	INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
	INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
	INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
	LEFT JOIN SOLUCION S ON S.SOL_codigo = C.SOL_codigo
	WHERE 
		C.EST_codigo = 7 -- Solo incidencias con estado 7 (cerradas)
		AND (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial)
		AND (@fechaInicio IS NULL OR C.CIE_fecha >= @fechaInicio)
		AND (@fechaFin IS NULL OR C.CIE_fecha <= @fechaFin)
		AND (@area IS NULL OR A.ARE_codigo = @area)
	GROUP BY
		I.INC_numero,
		I.INC_numero_formato,
		INC_fecha,
		INC_hora,
		A.ARE_nombre,
		CAT.CAT_nombre,
		I.INC_asunto,
		I.INC_documento,
		I.INC_codigoPatrimonial,
		B.BIE_nombre,
		PRI.PRI_nombre,
		C.CIE_fecha,
		C.CIE_hora,
		C.CIE_numero,
		C.CIE_diagnostico,
		C.CIE_recomendaciones,
		C.CIE_documento,
		O.CON_descripcion,
		U.USU_nombre,
		S.SOL_descripcion,
		P.PER_nombres,
		P.PER_apellidoPaterno,
		CASE
			WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
			ELSE E.EST_descripcion
		END
	ORDER BY ultimaFecha DESC, ultimaHora DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA FILTRAR INCIDENCIAS CERRADAS 
CREATE OR ALTER PROCEDURE sp_filtrar_incidencias_cerradas
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
	SELECT
		I.INC_numero,
		I.INC_numero_formato,
		(CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + 
		STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
		A.ARE_nombre,
		CAT.CAT_nombre,
		I.INC_asunto,
		I.INC_documento,
		I.INC_codigoPatrimonial,
		B.BIE_nombre,
		PRI.PRI_nombre,
		(CONVERT(VARCHAR(10), C.CIE_fecha, 103) + ' - ' + 
		STUFF(RIGHT('0' + CONVERT(VARCHAR(7), C.CIE_hora, 0), 7), 6, 0, ' ')) AS fechaCierreFormateada,
		C.CIE_numero,
		C.CIE_diagnostico, 
		C.CIE_recomendaciones,
		C.CIE_documento,
		O.CON_descripcion,
    C.USU_codigo,
		U.USU_nombre,
		S.SOL_descripcion,
		CASE
			WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
			ELSE E.EST_descripcion
		END AS Estado,
		(P.PER_nombres + ' ' + P.PER_apellidoPaterno) AS Usuario,
		-- Última modificación (fecha y hora más reciente)
		MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
		MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
	FROM CIERRE C
	LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
	LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
	LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
	INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
	RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
	LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
	INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
	INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
	INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
	LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
	INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
	INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
	INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
	LEFT JOIN SOLUCION S ON S.SOL_codigo = C.SOL_codigo
	WHERE 
		C.EST_codigo = 7 -- Solo incidencias con estado 7 (cerradas)
		AND (@fechaInicio IS NULL OR C.CIE_fecha >= @fechaInicio)
		AND (@fechaFin IS NULL OR C.CIE_fecha <= @fechaFin)
    AND (@usuario IS NULL OR C.USU_codigo = @usuario)
	GROUP BY
		I.INC_numero,
		I.INC_numero_formato,
		INC_fecha,
		INC_hora,
		A.ARE_nombre,
		CAT.CAT_nombre,
		I.INC_asunto,
		I.INC_documento,
		I.INC_codigoPatrimonial,
		B.BIE_nombre,
		PRI.PRI_nombre,
		C.CIE_fecha,
		C.CIE_hora,
		C.CIE_numero,
		C.CIE_diagnostico,
		C.CIE_recomendaciones,
		C.CIE_documento,
		O.CON_descripcion,
    C.USU_codigo,
		U.USU_nombre,
		S.SOL_descripcion,
		P.PER_nombres,
		P.PER_apellidoPaterno,
		CASE
			WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
			ELSE E.EST_descripcion
		END
	ORDER BY ultimaFecha DESC, ultimaHora DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - USUARIO
CREATE OR ALTER PROCEDURE sp_consultar_incidencias_usuario
  @area INT,
  @codigoPatrimonial CHAR(12),
  @estado INT,
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT(VARCHAR(7), I.INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    I.INC_documento,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103) + ' - ' + 
    STUFF(RIGHT('0' + CONVERT(VARCHAR(7), R.REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    CONVERT(VARCHAR(10), C.CIE_fecha, 103) AS fechaCierreFormateada,
    O.CON_descripcion,
    U.USU_nombre,
	p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS ESTADO,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
  FROM CIERRE C
  LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
  LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
  RIGHT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
  RIGHT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  RIGHT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  WHERE 
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial) AND
    (@estado IS NULL OR 
        (EC.EST_codigo = @estado OR 
        (I.EST_codigo = @estado AND C.CIE_numero IS NULL))) AND
    (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio) AND
    (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin) AND
    (@area IS NULL OR A.ARE_codigo = @area)
    AND (I.EST_codigo IN (3, 4, 7) OR EC.EST_codigo IN (3, 4, 7))
	GROUP BY
    I.INC_numero,
    I.INC_numero_formato,
    I.INC_fecha,
    I.INC_hora,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_codigoPatrimonial,
    B.BIE_nombre,
    I.INC_documento,
    R.REC_fecha,
    R.REC_hora,
    PRI.PRI_nombre,
    IMP.IMP_descripcion,
    C.CIE_fecha,
    O.CON_descripcion,
    U.USU_nombre,
	P.PER_nombres,
	P.PER_apellidoPaterno,
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion
	ORDER BY ultimaFecha DESC, ultimaHora DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA FILTRAR INCIDENCIAS POR AREA Y RANGO DE FECHAS - REPORTE
CREATE OR ALTER PROCEDURE sp_filtrar_incidencias_area
  @area INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
	B.BIE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    O.CON_descripcion,
	  (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS Estado,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
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
    (@area IS NULL OR A.ARE_codigo = @area) AND
    (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio) AND
    (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin) 
    AND (I.EST_codigo IN (3, 4, 7) OR EC.EST_codigo IN (3, 4, 7))
  ORDER BY 
    SUBSTRING(I.INC_numero_formato, CHARINDEX('-', I.INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA FILTRAR POR CODIGO PATRIMONIAL
CREATE OR ALTER PROCEDURE sp_filtrar_incidencias_equipo
  @codigoPatrimonial CHAR(12),
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
	B.BIE_nombre,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
    PRI.PRI_nombre,
    O.CON_descripcion,
	  (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
      WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS Estado,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
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
    (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio) AND
    (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin) AND
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial)
    AND (I.EST_codigo IN (3, 4, 7) OR EC.EST_codigo IN (3, 4, 7))
	AND i.INC_codigoPatrimonial IS NOT NULL
    AND i.INC_codigoPatrimonial <> ''
  ORDER BY 
    SUBSTRING(I.INC_numero_formato, CHARINDEX('-', I.INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA FILTRAR AREAS MAS AFECTADAS
CREATE OR ALTER PROCEDURE sp_filtrar_areas_afectadas
  @categoria INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT 
    A.ARE_nombre AS areaMasIncidencia,
    COUNT(*) AS cantidadIncidencias
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  WHERE 
    (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio) AND
    (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin) AND
    (@categoria IS NULL OR I.CAT_codigo = @categoria)
  GROUP BY A.ARE_nombre
  ORDER BY cantidadIncidencias DESC;
END
GO

-- Procedimiento almacenado para filtrar equipos mas afectados - reportes
CREATE OR ALTER PROCEDURE sp_filtrar_equipos_afectados
  @codigoPatrimonial CHAR(12),
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT 
    i.INC_codigoPatrimonial AS codigoPatrimonial,
    -- Subconsulta para obtener el nombre del bien utilizando los primeros 8 dígitos
    (SELECT BIE_nombre 
     FROM BIEN 
     WHERE LEFT(i.INC_codigoPatrimonial, 8) = LEFT(BIE_codigoIdentificador, 8)) AS nombreBien,
    a.ARE_nombre AS nombreArea, 
    COUNT(*) AS cantidadIncidencias
  FROM INCIDENCIA i
  INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
  WHERE i.INC_codigoPatrimonial IS NOT NULL
    AND i.INC_codigoPatrimonial <> ''
    -- Filtrar por el rango de fechas si los parámetros no son nulos
    AND (@fechaInicio IS NULL OR i.INC_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR i.INC_fecha <= @fechaFin)
    -- Filtrar por código patrimonial si el parámetro no es nulo
    AND (@codigoPatrimonial IS NULL OR i.INC_codigoPatrimonial = @codigoPatrimonial)
  GROUP BY i.INC_codigoPatrimonial, a.ARE_nombre
  ORDER BY cantidadIncidencias DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS TOTALES - AUDITORIA
CREATE OR ALTER PROCEDURE sp_consultar_eventos_totales
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
    SELECT
      (
        CONVERT ( VARCHAR ( 10 ), AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
      ) AS fechaFormateada,
      A.AUD_fecha,
      A.AUD_hora,
      A.AUD_tabla,
      A.AUD_usuario,
      R.ROL_nombre,
      U.USU_nombre,
      PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno AS NombreCompleto,
      A.AUD_operacion,
      AR.ARE_nombre,
      A.AUD_ip,
      A.AUD_nombreEquipo 
    FROM
      AUDITORIA A
      INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
      INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
      INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
      INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo 
    WHERE
      ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
      AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
      AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
    ORDER BY
      AUD_fecha DESC,
      AUD_hora DESC;
    
  END 
GO

--PROCEDIMIENTO ALMANCENADO PARA CONSULTAR LOS INICIOS DE SESION
CREATE OR ALTER PROCEDURE sp_consultar_eventos_login
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
    SELECT
      (
        CONVERT ( VARCHAR ( 10 ), AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
      ) AS fechaFormateada,
      A.AUD_fecha,
      A.AUD_hora,
      A.AUD_tabla,
      A.AUD_usuario,
      R.ROL_nombre,
      U.USU_nombre,
      PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno AS NombreCompleto,
      A.AUD_operacion,
      AR.ARE_nombre,
      A.AUD_ip,
      A.AUD_nombreEquipo 
    FROM
      AUDITORIA A
      INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
      INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
      INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
      INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo 
    WHERE
      A.AUD_operacion LIKE 'Iniciar sesión' 
      AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
      AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
      AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
    ORDER BY
      AUD_fecha DESC,
      AUD_hora DESC;
    
  END 
GO



--TODO: PROCEDIMIENTO ALMANCENADO PARA CONSULTAR LOS REGISTROS DE INCIDENCIAS
-- CREATE OR ALTER PROCEDURE sp_consultar_auditoria_registro_incidencia
--     @fechaInicio DATE = NULL,
--     @fechaFin DATE = NULL
-- AS
-- BEGIN 
--     SELECT fechaFormateada, NombreCompleto, INC_numero_formato, ARE_nombre, AUD_ip, AUD_nombreEquipo
-- 	FROM vw_auditoria_registrar_incidencia
--     WHERE (@fechaInicio IS NULL OR INC_fecha >= @fechaInicio)
--       AND (@fechaFin IS NULL OR INC_fecha <= @fechaFin);
-- END;
-- GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE INCIDENCIAS
CREATE OR ALTER PROCEDURE sp_consultar_eventos_incidencias
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    fechaFormateada,
    AUD_fecha,
    AUD_hora,
    NombreCompleto,
    referencia,
    ARE_nombre,
    AUD_operacion,
    AUD_ip,
    AUD_nombreEquipo 
  FROM
    vw_eventos_incidencias 
  WHERE
    ( @fechaInicio IS NULL OR AUD_fecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR AUD_fecha <= @fechaFin ) 
    AND ( @usuario IS NULL OR AUD_usuario = @usuario ) 
  ORDER BY
    AUD_fecha DESC,
    AUD_hora DESC;
END
GO


--PROCEDIMIENTO ALMANCENADO PARA CONSULTAR LAS RECEPCIONES DE INCIDENCIAS
CREATE OR ALTER PROCEDURE sp_consultar_auditoria_recepcion_incidencia
    @fechaInicio DATE = NULL,
    @fechaFin DATE = NULL
AS
BEGIN 
    SELECT fechaFormateada, NombreCompleto, INC_numero_formato, ARE_nombre, AUD_ip, AUD_nombreEquipo
	FROM vw_auditoria_registrar_recepcion
    WHERE (@fechaInicio IS NULL OR REC_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR REC_fecha <= @fechaFin);
END;
GO


--select ASI_codigo, ASI_fecha, ASI_hora, A.EST_codigo, E.EST_descripcion, USU_codigo, REC_numero
--from ASIGNACION A
--LEFT JOIN ESTADO E ON E.EST_codigo = A.EST_codigo;
--GO

--SELECT MAN_codigo, MAN_fecha, MAN_hora, M.EST_codigo, E.EST_descripcion, ASI_codigo FROM MANTENIMIENTO M
--LEFT JOIN ESTADO E ON E.EST_codigo = M.EST_codigo;
--GO


--DECLARE @año INT = 2024; -- Ingresar el año deseado
--DECLARE @mes INT = 11;    -- Ingresar el mes deseado

--SELECT COUNT(*) AS incidencias_mes_año
--FROM INCIDENCIA
--WHERE INC_FECHA >= DATEFROMPARTS(@año, @mes, 1)
--  AND INC_FECHA < DATEFROMPARTS(
--        CASE 
--            WHEN @mes = 12 THEN @año + 1 
--            ELSE @año 
--        END,
--        CASE 
--            WHEN @mes = 12 THEN 1 
--            ELSE @mes + 1 
--        END, 1);
--GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE USUARIOS
CREATE OR ALTER PROCEDURE sp_consultar_eventos_usuarios
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
    SELECT
      (
        CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
      ) AS fechaFormateada,
      A.AUD_fecha,
      A.AUD_hora,
      A.AUD_usuario,
      P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
      A.AUD_referencia,
      PE.PER_nombres + ' ' + PE.PER_apellidoPaterno + ' ' + PE.PER_apellidoMaterno AS UsuarioReferencia,
      A.AUD_operacion,
      A.AUD_ip,
      A.AUD_nombreEquipo 
    FROM
      AUDITORIA A
      LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
      LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
      LEFT JOIN USUARIO UA ON UA.USU_codigo = A.AUD_referencia
      LEFT JOIN PERSONA PE ON PE.PER_codigo = UA.PER_codigo 
    WHERE
      A.AUD_tabla = 'USUARIO' 
      AND A.AUD_operacion NOT IN ( 'Iniciar sesión' ) 
      AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
      AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
      AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
    ORDER BY
      AUD_fecha DESC,
      AUD_hora DESC;
  END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE PERSONAS
CREATE OR ALTER PROCEDURE sp_consultar_eventos_personas
  @usuario INT = NULL,
  @operacion NVARCHAR(100) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
    SELECT
      (
        CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
      ) AS fechaFormateada,
      A.AUD_fecha,
      A.AUD_hora,
      P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
      A.AUD_referencia,
      CASE-- Si la operación es "Actualizar perfil", tomamos la referencia del código de usuario
        
        WHEN A.AUD_operacion = 'Actualizar perfil' THEN
        (
          SELECT
            PE2.PER_nombres + ' ' + PE2.PER_apellidoPaterno + ' ' + PE2.PER_apellidoMaterno 
          FROM
            USUARIO U2
            LEFT JOIN PERSONA PE2 ON PE2.PER_codigo = U2.PER_codigo 
          WHERE
            U2.USU_codigo = A.AUD_referencia 
        ) -- Si no es "Actualizar perfil", tomamos la referencia del código de persona
        ELSE PE.PER_nombres + ' ' + PE.PER_apellidoPaterno + ' ' + PE.PER_apellidoMaterno 
      END AS UsuarioReferencia,
      A.AUD_operacion,
      A.AUD_ip,
      A.AUD_nombreEquipo 
    FROM
      AUDITORIA A
      LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
      LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
      LEFT JOIN PERSONA PE ON PE.PER_codigo = A.AUD_referencia 
    WHERE
      A.AUD_tabla = 'PERSONA' 
      AND ( @operacion IS NULL OR A.AUD_operacion LIKE @operacion + '%' ) 
      AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
      AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
      AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
    ORDER BY
      AUD_fecha DESC,
      AUD_hora DESC;
 END 
 GO

-- PROCEDIMIENTO ALMACENADO PARA CONMSULTAR EVENTOS DE AREAS
CREATE OR ALTER PROCEDURE sp_consultar_eventos_areas
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    (
      CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaFormateada,
    A.AUD_fecha,
    A.AUD_hora,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
    A.AUD_referencia,
    AR.ARE_nombre AS referencia,
    A.AUD_operacion,
    A.AUD_ip,
    A.AUD_nombreEquipo 
  FROM
    AUDITORIA A
    LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
    LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN AREA AR ON AR.ARE_codigo = A.AUD_referencia 
  WHERE
    A.AUD_tabla = 'AREA' 
    AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
    AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
  ORDER BY
    AUD_fecha DESC,
    AUD_hora DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE BIENES
CREATE OR ALTER PROCEDURE sp_consultar_eventos_bienes
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    (
      CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaFormateada,
    A.AUD_fecha,
    A.AUD_hora,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
    A.AUD_referencia,
    B.BIE_codigoIdentificador + ' - ' + B.BIE_nombre AS referencia,
    A.AUD_operacion,
    A.AUD_ip,
    A.AUD_nombreEquipo 
  FROM
    AUDITORIA A
    LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
    LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN BIEN b ON b.BIE_codigo = A.AUD_referencia 
  WHERE
    A.AUD_tabla = 'BIEN' 
    AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
    AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
  ORDER BY
    AUD_fecha DESC,
    AUD_hora DESC;
  END
 GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE CATEGORIAS
CREATE OR ALTER PROCEDURE sp_consultar_eventos_categorias
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    (
      CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaFormateada,
    A.AUD_fecha,
    A.AUD_hora,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
    A.AUD_referencia,
    C.CAT_nombre AS referencia,
    A.AUD_operacion,
    A.AUD_ip,
    A.AUD_nombreEquipo 
  FROM
    AUDITORIA A
    LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
    LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN CATEGORIA C ON C.CAT_codigo = A.AUD_referencia 
  WHERE
    A.AUD_tabla = 'CATEGORIA' 
    AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
    AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
  ORDER BY
    AUD_fecha DESC,
    AUD_hora DESC;
  END 
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR EVENTOS DE SOLUCIONES
CREATE OR ALTER PROCEDURE sp_consultar_eventos_soluciones
  @usuario INT = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT
    (
      CONVERT ( VARCHAR ( 10 ), A.AUD_fecha, 103 ) + ' - ' + STUFF( RIGHT( '0' + CONVERT ( VARCHAR ( 7 ), A.AUD_hora, 0 ), 7 ), 6, 0, ' ' ) 
    ) AS fechaFormateada,
    A.AUD_fecha,
    A.AUD_hora,
    P.PER_nombres + ' ' + P.PER_apellidoPaterno AS UsuarioEvento,
    A.AUD_referencia,
    S.SOL_descripcion AS referencia,
    A.AUD_operacion,
    A.AUD_ip,
    A.AUD_nombreEquipo 
  FROM
    AUDITORIA A
    LEFT JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
    LEFT JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN SOLUCION S ON S.SOL_codigo = A.AUD_referencia 
  WHERE
    A.AUD_tabla = 'SOLUCION' 
    AND ( @fechaInicio IS NULL OR A.AUD_fecha >= @fechaInicio ) 
    AND ( @fechaFin IS NULL OR A.AUD_fecha <= @fechaFin ) 
    AND ( @usuario IS NULL OR A.AUD_usuario = @usuario ) 
  ORDER BY
    AUD_fecha DESC,
    AUD_hora DESC;
  
  END
GO


PRINT 'BASE DE DATOS GENERADO';
GO
