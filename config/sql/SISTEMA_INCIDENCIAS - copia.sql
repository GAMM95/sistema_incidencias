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

IF EXISTS (SELECT name FROM sys.databases WHERE name = 'SISTEMA_HELPDESK')
BEGIN
    DROP DATABASE SISTEMA_HELPDESK;
END
GO

CREATE DATABASE SISTEMA_HELPDESK;
GO

USE SISTEMA_HELPDESK;
GO

-------------------------------------------------------------------------------------------------------
  -- CREACION DE LAS TABLAS
-------------------------------------------------------------------------------------------------------
-- CREACION DE LA TABLA ROL
CREATE TABLE ROL (
	ROL_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	ROL_nombre VARCHAR(20)
);
GO

-- CREACION DE LA TABLA PERSONA
CREATE TABLE PERSONA (
	PER_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	PER_dni CHAR(8) UNIQUE NOT NULL,
	PER_nombres VARCHAR(20) NOT NULL,
	PER_apellidoPaterno VARCHAR(15) NOT NULL,
	PER_apellidoMaterno VARCHAR(15) NOT NULL,
	PER_celular CHAR(9) NULL,
	PER_email VARCHAR(45) NULL,
);
GO

-- CREACION DE LA TABLA AREA
CREATE TABLE AREA (
	ARE_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	ARE_nombre VARCHAR(100) UNIQUE NOT NULL,
	ARE_estado SMALLINT NULL
);
GO

-- CREACION DE LA TABLA ESTADO
CREATE TABLE ESTADO (
	EST_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	EST_descripcion VARCHAR(20)
);
GO

-- CREACION DE LA TABLA USUARIO
CREATE TABLE USUARIO (
	USU_codigo SMALLINT IDENTITY(1, 1) PRIMARY KEY,
	USU_nombre VARCHAR(20) UNIQUE NOT NULL,
	USU_password VARCHAR(10) NOT NULL,
	PER_codigo SMALLINT NOT NULL,
	ROL_codigo SMALLINT NOT NULL,
	ARE_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT FK_USUARIO_PERSONA FOREIGN KEY (PER_codigo) REFERENCES PERSONA(PER_codigo),
	CONSTRAINT FK_USUARIO_ROL FOREIGN KEY (ROL_codigo) REFERENCES ROL(ROL_codigo),
	CONSTRAINT FK_USUARIO_AREA FOREIGN KEY (ARE_codigo) REFERENCES AREA(ARE_codigo),
);
GO

 -- CREACION DE LA TABLA PRIORIDAD
CREATE TABLE PRIORIDAD(
	PRI_codigo SMALLINT IDENTITY(1, 1),
	PRI_nombre VARCHAR(15) NOT NULL,
	CONSTRAINT pk_PRI_codigo PRIMARY KEY(PRI_codigo),
	CONSTRAINT uq_PRI_descripcion UNIQUE (PRI_nombre)
);
GO

-- CREACION DE LA TABLA CATEGORIA
CREATE TABLE CATEGORIA (
	CAT_codigo SMALLINT IDENTITY(1, 1),
	CAT_nombre VARCHAR(60) NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_CAT_codigo PRIMARY KEY(CAT_codigo),
	CONSTRAINT uq_CAT_nombre UNIQUE (CAT_nombre)
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
    CONSTRAINT uk_bie_codigoIdentificado UNIQUE (BIE_codigoIdentificador)
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
CREATE TABLE EJECUCION_MANTENIMIENTO (
	EJE_codigo SMALLINT NOT NULL,
	EJE_fecha DATE NOT NULL,
	EJE_hora TIME NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	ASI_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_ejecucion PRIMARY KEY (EJE_codigo),
	CONSTRAINT fk_asignacion_ejecucion FOREIGN KEY (ASI_codigo) 
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
	SOL_codigo SMALLINT NOT NULL,
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
	CIE_asunto VARCHAR(500) NOT NULL,
	CIE_recomendaciones VARCHAR(1000) NULL,
	CON_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	REC_numero SMALLINT NOT NULL,
	USU_codigo SMALLINT NOT NULL,
	SOL_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_cierre PRIMARY KEY (CIE_numero),
	CONSTRAINT fk_condicion_cierre FOREIGN KEY (CON_codigo) 
	REFERENCES CONDICION (CON_codigo),
	CONSTRAINT fk_recepcion_cierre FOREIGN KEY (REC_numero) 
	REFERENCES RECEPCION (REC_numero),
	CONSTRAINT fk_codigo_cierre FOREIGN KEY (USU_codigo) 
	REFERENCES USUARIO (USU_codigo),
	CONSTRAINT fk_solucion_cierre FOREIGN KEY (SOL_codigo)
	REFERENCES SOLUCION (SOL_codigo)
);
GO


-------------------------------------------------------------------------------------------------------
  -- VOLCADO DE DATOS PARA LAS TABLAS CREADAS
-------------------------------------------------------------------------------------------------------

-- VOLCADO DE DATOS PARA LA TABLA ROL
INSERT INTO ROL (ROL_nombre) VALUES ('Administrador');
INSERT INTO ROL (ROL_nombre) VALUES ('Soporte');
INSERT INTO ROL (ROL_nombre) VALUES ('Usuario');
GO

-- VOLCADO DE DATOS PARA LA TABLA PERSONA
INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_email, PER_celular)
VALUES ('70555000', 'Jose', 'Castro', 'Gonzales', '', '');
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

-- VOLCADO DE DATOS PARA LA TABLA AREA
--Areas del palacio municipal
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Informática y Sistemas', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia Municipal', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Contabilidad', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Alcaldía', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Tesorería', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Sección de Almacén', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Abastecimiento y Control Patrimonial', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Control Patrimonial', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Caja General', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Recursos Humanos', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Desarrollo Económico Local', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Área de Liquidación de Obras', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Habilitación Urbana y Catastro', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Escalafón', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Secretaría General', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Programa de Vaso de Leche', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('DEMUNA', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('OMAPED', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Salud', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Administración Tributaria', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Servicio Social', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Relaciones Públicas y Comunicaciones', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Gestión Ambiental', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Asesoría Jurídica', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Planificación y Modernización Institucional', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Gestión y Desarrollo de RR.HH.', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Desarrollo Social y Promoción de la Familia', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Educación', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Programas Sociales e Inclusión', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Licencias', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Policía Municipal', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Registro Civil', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Mantenimiento de Obras Públicas', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Desarrollo Urbano y Planeamiento Territorial', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Ejecución Coactiva', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Estudios y Proyectos', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Obras', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Procuradoría Pública Municipal', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Administración y Finanzas', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Defensa Civil', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Juventud, Deporte y Cultura', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Áreas Verdes', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Seguridad Ciudadana', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Órgano de Control Institucional', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad Local de Empadronamiento - ULE', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Atención al Usuario y Trámite Documentario', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Seguridad Ciudadana, Defensa Civil y Tránsito', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Abastecimiento', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Unidad de Participación Vecinal', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Gerencia de Planeamiento, Presupuesto y Modernización', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado)  VALUES ('Subgerencia de Transporte, Tránsito y Seguridad Vial', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Archivo Central', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Equipo Mecánico y Maestranza', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado)  VALUES ('Subgerencia de Limpieza Pública', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Subgerencia de Bienestar Social', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Orientación Tributaria', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Servicios Generales', 1);
INSERT INTO AREA(ARE_nombre, ARE_estado) VALUES ('Secretaría Técnica de Procesos Administrativos Disciplinarios', 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA ESTADO
INSERT INTO ESTADO (EST_descripcion) VALUES ('ACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('INACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('ABIERTO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('RECEPCIONADO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('CERRADO');
GO

-- VOLCADO DE DATOS PARA LA TABLA USUARIO
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('JCASTRO', 'garbalenus', 1, 1, 1, 1);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('PERCY', '123456', 2, 1, 1, 2);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('ACOLLANTES', '123456', 3, 1, 1, 1);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('FBENITES', 'mde123', 4, 1, 1, 1);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('CLEYVA', '123456', 5, 2, 1, 1);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('MBLAS', '123456', 6, 2, 1, 2);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('SFABIAN', '123456', 7, 2, 1, 2);
INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
VALUES ('JMANTILLA', '123456', 8, 2, 1, 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA PRIORIDAD
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('BAJA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('MEDIA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('ALTA');
INSERT INTO PRIORIDAD (PRI_nombre) VALUES ('CRÍTICO');
GO

-- VOLCADO DE DATOS PARA LA TABLA CATEGORIA
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Red inaccesible', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Asistencia técnica', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Generación de usuario', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Fallo de equipo de computo', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Inaccesibilidad a impresora', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Cableado de red', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Correo corporativo', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Reporte de sistemas informáticos', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Otros', 1);
INSERT INTO CATEGORIA (CAT_nombre, CAT_estado) VALUES ('Inaccesibilidad a sistemas informáticos', 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA IMPACTO
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('BAJO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('MEDIO');
INSERT INTO IMPACTO (IMP_descripcion) VALUES ('ALTO');
GO

-- VOLCADO DE DATOS PARA LA TABLA BIEN
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('','',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74089950','CPU',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74080500','LAPTOP',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74088187','MONITOR PLANO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74087700','MONITOR A COLOR',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74089500','TECLADO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74088600','MOUSE',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('46225215','ESTABILIZADOR',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74083650','IMPRESORA A INYECCION DE TINTA',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74083875','IMPRESORA DE CODIGO DE BARRAS',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74084550','IMPRESORA MATRIZ DE PUNTO',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74085000','IMPRESORA PARA PLANOS - PLOTTERS',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74084100','IMPRESORA LASER',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74222358','EQUIPO MULTIFUNCIONAL COPIADORA IMPRESORA SCANNER',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('95228117','SWITCH PARA RED',1);
INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado) VALUES ('74087250','MODEM EXTERNO',1);
GO

-- VOLCADO DE DATOS PARA LA TABLA CONDICION
INSERT INTO CONDICION (CON_descripcion) VALUES ('OPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('INOPERATIVO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('SOLUCIONADO');
INSERT INTO CONDICION (CON_descripcion) VALUES ('NO SOLUCIONADO');
go

-------------------------------------------------------------------------------------------------------
  -- FUNCIONES Y TRIGGERS
-------------------------------------------------------------------------------------------------------
-- FUNCION PARA GENERAR EL NUMERO DE INCIDENCIA 0000-AÑO-MDE
CREATE FUNCTION dbo.GenerarNumeroIncidencia()
RETURNS VARCHAR(20)
AS
BEGIN
    DECLARE @numero INT;
    DECLARE @año_actual CHAR(4);
    DECLARE @formato VARCHAR(20);
    DECLARE @resultado VARCHAR(20);

    -- Obtener el año actual
    SET @año_actual = YEAR(GETDATE());

    -- Obtener el último número de incidencia del año actual
    SELECT @numero = ISNULL(MAX(CAST(SUBSTRING(INC_numero_formato, 1, CHARINDEX('-', INC_numero_formato) - 1) AS INT)), 0) + 1
    FROM INCIDENCIA
    WHERE SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) = @año_actual;

    -- Generar el formato con el número actual
    SET @formato = RIGHT('0000' + CAST(@numero AS VARCHAR(4)), 4) + '-' + @año_actual + '-MDE';
    SET @resultado = @formato;

    RETURN @resultado;
END;
GO

-- TRIGGER PARA ACTUALIZAR EL NUMERO DE INCIDENCIA FORMATEADO "INC_numero_formato"
CREATE TRIGGER trg_incrementar_inc_numero
ON INCIDENCIA
INSTEAD OF INSERT
AS
BEGIN
	DECLARE @ultimo_numero SMALLINT;
    
	-- Obtener el último número de incidencia
	SELECT @ultimo_numero = ISNULL(MAX(INC_numero), 0) FROM INCIDENCIA;
    
	-- Insertar el nuevo registro con INC_numero incrementado en 1
	INSERT INTO INCIDENCIA (INC_numero, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo)
	SELECT @ultimo_numero + 1, INC_numero_formato, INC_fecha, INC_hora, INC_asunto, INC_descripcion, INC_documento, INC_codigoPatrimonial, EST_codigo, CAT_codigo, ARE_codigo, USU_codigo
	FROM inserted;
END;
GO

-- TRIGGER PARA ACTUALIZAR EL NUMERO DE INCIDENCIA FORMATEADA
CREATE TRIGGER trg_UpdateNumeroFormato
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

-- TRIGGER PARA GENERAR EL NUMERO DE RECEPCION AUMENTADO EN 1
CREATE TRIGGER trg_incrementar_rec_numero
ON RECEPCION
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el último número de recepción
    SELECT @ultimo_numero = ISNULL(MAX(REC_numero), 0) FROM RECEPCION;

    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO RECEPCION (REC_numero, REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo)
    SELECT @ultimo_numero + 1, REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo
    FROM inserted;
END;
GO

-- TRIGGER PARA GENERAR EL NUMERO DE CIERRE AUMENTADO EN 1
CREATE TRIGGER trg_incrementar_cie_numero
ON CIERRE
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el último número de recepción
    SELECT @ultimo_numero = ISNULL(MAX(CIE_numero), 0) FROM CIERRE;
    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO CIERRE (CIE_numero, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, EST_codigo, REC_numero, USU_codigo)
    SELECT @ultimo_numero + 1, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, EST_codigo, REC_numero, USU_codigo
    FROM inserted;
END;
GO

-------------------------------------------------------------------------------------------------------
  -- PROCEDIMIENTOS ALMACENADOS
-------------------------------------------------------------------------------------------------------

-- PROCEDIMIENTO ALMACENADO PARA INICIAR SESION
CREATE PROCEDURE SP_Usuario_login(
	@USU_usuario VARCHAR(20),
	@USU_password VARCHAR(10)
) 
AS 
BEGIN
	SET NOCOUNT ON;
	SELECT u.USU_nombre, u.USU_password, p.PER_nombres, p.PER_apellidoPaterno, r.ROL_codigo, r.ROL_nombre, a.ARE_codigo, a.ARE_nombre, u.EST_codigo
	FROM USUARIO u
	INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
	INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
	INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
	WHERE u.USU_nombre = @USU_usuario AND u.USU_password = @USU_password;
END;
GO


-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS PERSONALES Y USUARIO
CREATE PROCEDURE EditarPersonaYUsuario
  @USU_codigo SMALLINT,
  @USU_nombre VARCHAR(20),
  @USU_password VARCHAR(10),
  @PER_dni CHAR(8),
  @PER_nombres VARCHAR(20),
  @PER_apellidoPaterno VARCHAR(15),
  @PER_apellidoMaterno VARCHAR(15),
  @PER_celular CHAR(9),
  @PER_email VARCHAR(45)
AS
BEGIN
  BEGIN TRY
    BEGIN TRANSACTION; -- Inicia una transacción para asegurar la consistencia de los datos
      -- Actualiza los datos del usuario
      UPDATE USUARIO
      SET 
          USU_nombre = @USU_nombre,
          USU_password = @USU_password
      WHERE USU_codigo = @USU_codigo;

      -- Actualiza los datos de la persona vinculada al usuario
      UPDATE PERSONA
      SET 
          PER_dni = @PER_dni,
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

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR USUARIO
CREATE PROCEDURE SP_Registrar_Usuario ( 
	@USU_nombre VARCHAR(20),
	@USU_password VARCHAR(10),
	@PER_codigo SMALLINT,
	@ROL_codigo SMALLINT,
	@ARE_codigo SMALLINT)
AS
BEGIN 
	-- Verificar si la persona ya tiene un usuario registrado
	IF EXISTS (SELECT 1 FROM USUARIO WHERE PER_codigo = @PER_codigo)
	BEGIN
		-- Si la persona ya tiene un usuario, retornar un mensaje de error o un código de error
		RAISERROR('La persona ya tiene un usuario registrado.', 16, 1);
		RETURN;
	END

	-- Insertar el nuevo usuario con EST_codigo siempre igual a 1
	INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
	VALUES (@USU_nombre, @USU_password, @PER_codigo, @ROL_codigo, @ARE_codigo, 1);
END;
GO

--PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS DE USUARIO
CREATE PROCEDURE sp_editarUsuario
    @USU_codigo SMALLINT,
    @USU_nombre VARCHAR(20),
    @USU_password VARCHAR(10),
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
            USU_password = @USU_password,
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

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR USUARIO
CREATE PROCEDURE sp_deshabilitarUsuario
	@codigoUsuario SMALLINT
AS
BEGIN
	UPDATE USUARIO SET EST_codigo = 2 
    WHERE EST_codigo = 1 AND  USU_codigo = @codigoUsuario;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR USUARIO
CREATE PROCEDURE sp_habilitarUsuario
	@codigoUsuario SMALLINT
AS
BEGIN
	UPDATE USUARIO SET EST_codigo = 1
    WHERE EST_codigo = 2 AND  USU_codigo = @codigoUsuario;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR AREAS
CREATE PROCEDURE sp_registrarArea 
    @NombreArea VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;

        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO AREA (ARE_nombre, ARE_estado)
        VALUES (@NombreArea, 1);

        -- Confirmar la transacción si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacción en caso de error
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

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR AREA
CREATE PROCEDURE sp_deshabilitarArea
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET ARE_estado = 2 
   WHERE (ARE_estado = 1 OR  ARE_estado = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR AREA
CREATE PROCEDURE sp_habilitarArea
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET ARE_estado = 1
    WHERE (ARE_estado = 2 OR  ARE_estado = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR CATEGORIAS
CREATE PROCEDURE sp_registrarCategoria
    @NombreCategoria VARCHAR(60)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO CATEGORIA (CAT_nombre, CAT_estado)
        VALUES (@NombreCategoria, 1);

        -- Confirmar la transacción si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacción en caso de error
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
CREATE PROCEDURE sp_deshabilitarCategoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET CAT_estado = 2 
   WHERE (CAT_estado = 1 OR  CAT_estado = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE PROCEDURE sp_habilitarCategoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET CAT_estado = 1
    WHERE (CAT_estado = 2 OR  CAT_estado = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR BIEN
CREATE PROCEDURE sp_registrarBien
    @codigoIdentificador VARCHAR(12),
    @NombreBien VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, BIE_estado)
        VALUES (@codigoIdentificador, @NombreBien, 1);

        -- Confirmar la transacción si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacción en caso de error
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

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR BIEN
CREATE PROCEDURE sp_deshabilitarBien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET BIE_estado = 2 
   WHERE (BIE_estado = 1 OR  BIE_estado = '')
	AND  BIE_codigo = @codigoBien;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE PROCEDURE sp_habilitarBien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET BIE_estado = 1
    WHERE (BIE_estado = 2 OR  BIE_estado = '')
	AND  BIE_codigo = @codigoBien;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR INCIDENCIA - ADMINISTRADOR / USUARIO
CREATE PROCEDURE SP_Registrar_Incidencia
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
  DECLARE @numero_formato VARCHAR(20);  -- Número de incidencia

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
      -- Generar el número de incidencia formateado
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
CREATE PROCEDURE sp_ActualizarIncidencia
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
CREATE PROCEDURE sp_ActualizarIncidenciaUsuario
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

-- PROCEDIMIENTO ALMACENADO PARA INSERTAR LA RECEPCION Y ACTUALIZAR ESTADO DE INCIDENCIA
CREATE PROCEDURE sp_InsertarRecepcionActualizarIncidencia(
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

        -- Verificar si ya existe una recepción con los mismos valores
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
            -- Insertar la nueva recepción
            INSERT INTO RECEPCION (REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo)
            VALUES (@REC_fecha, @REC_hora, @INC_numero, @PRI_codigo, @IMP_codigo, @USU_codigo, 4);
            
            -- Actualizar el estado de la incidencia
            UPDATE INCIDENCIA 
            SET EST_codigo = 4
            WHERE INC_numero = @INC_numero;
        END
        ELSE
        BEGIN
            -- Mensaje que la recepción ya existe
            PRINT 'La recepción ya existe y no se puede registrar nuevamente.';
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
CREATE PROCEDURE sp_ActualizarRecepcion
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

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR CIERRES
CREATE PROCEDURE sp_ActualizarCierre
	@CIE_numero SMALLINT,
	@CIE_asunto VARCHAR(500),
	@CIE_documento VARCHAR(500),
	@CON_codigo SMALLINT,
	@CIE_diagnostico VARCHAR(1000),
	@CIE_recomendaciones VARCHAR(1000)
AS
BEGIN
	-- Actualizar el registro de la tala CIERRE
	UPDATE CIERRE
	SET
		CIE_asunto = @CIE_asunto,
		CIE_documento  = @CIE_documento,
		CON_codigo =  @CON_codigo,
		CIE_diagnostico = @CIE_diagnostico,
		CIE_recomendaciones = @CIE_recomendaciones
	WHERE CIE_numero = @CIE_numero;
END;
GO

-- PROCEDIMIENTO ALMAENADO PARA INSERTAR CIERRES Y ACTUALIZAR ESTADO DE RECEPCION
CREATE PROCEDURE sp_InsertarCierreActualizarRecepcion
  @CIE_fecha DATE,
  @CIE_hora TIME,
  @CIE_diagnostico VARCHAR(1000),
  @CIE_documento VARCHAR(500),
  @CIE_asunto VARCHAR(500),
  @CIE_recomendaciones VARCHAR(1000),
  @CON_codigo SMALLINT,
  @REC_numero SMALLINT,
  @USU_codigo SMALLINT
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
                AND CIE_asunto = @CIE_asunto 
                AND CIE_recomendaciones = @CIE_recomendaciones
                AND CON_codigo = @CON_codigo
                AND REC_numero = @REC_numero
                AND USU_codigo = @USU_codigo
        )
		BEGIN
			-- Insertar el nuevo cierre
			INSERT INTO CIERRE (CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, REC_numero, USU_codigo, EST_codigo)
			VALUES (@CIE_fecha, @CIE_hora , @CIE_diagnostico, @CIE_documento, @CIE_asunto, @CIE_recomendaciones, @CON_codigo, @REC_numero, @USU_codigo, 5);
    
			-- Actualizar el estado de la recepcion
			UPDATE RECEPCION SET EST_codigo = 5
			WHERE REC_numero = @REC_numero;
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

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarIncidencias
  @area INT,
  @estado INT,
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
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
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
        WHERE C2.REC_numero = R.REC_numero
        AND C2.EST_codigo = 5
    )
  AND 
    (@estado IS NULL OR e.EST_codigo = @estado) AND  
    (@fechaInicio IS NULL OR INC_fecha >= @fechaInicio) AND  
    (@fechaFin IS NULL OR INC_fecha <= @fechaFin) AND     
    (@area IS NULL OR a.ARE_codigo = @area)     
  ORDER BY 
    -- Extraer el año de INC_numero_formato y ordenar por año de forma descendente
    SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMACENADO PARA CONSULTAR INCIDENCIAS TOTALES - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarIncidenciasTotales
  @area INT = NULL,
  @codigoPatrimonial CHAR(12) = NULL,
  @fechaInicio DATE = NULL,
  @fechaFin DATE = NULL
AS
BEGIN
  SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    PRI.PRI_nombre,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS Estado,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
  LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
  WHERE 
    I.EST_codigo IN (3, 4, 5) 
    AND 
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial)
    AND (@fechaInicio IS NULL OR I.INC_fecha >= @fechaInicio)
    AND (@fechaFin IS NULL OR I.INC_fecha <= @fechaFin)
    AND (@area IS NULL OR A.ARE_codigo = @area)
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
    PRI.PRI_nombre,
    p.PER_nombres,
    p.PER_apellidoPaterno
  ORDER BY 
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) DESC,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR INCIDENCIAS - USUARIO
CREATE PROCEDURE sp_ConsultarIncidenciasUsuario
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
    (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
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
    END AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
  FROM INCIDENCIA I
  INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
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
    AND (I.EST_codigo IN (3, 4, 5) OR EC.EST_codigo IN (3, 4, 5))
  ORDER BY 
    SUBSTRING(I.INC_numero_formato, CHARINDEX('-', I.INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC;
END
GO

-- PROCEDIMIENTO ALMANCENADO PARA CONSULTAR CIERRES - ADMINISTRADOR
CREATE PROCEDURE sp_ConsultarCierres
  @area INT,
  @codigoPatrimonial CHAR(12),
  @fechaInicio DATE,
  @fechaFin DATE
AS
BEGIN
  SELECT
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10),INC_fecha,103) + ' - '+   STUFF(RIGHT('0' + CONVERT(VarChar(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
    A.ARE_nombre,
    CAT.CAT_nombre,
    I.INC_asunto,
    I.INC_documento,
    PRI.PRI_nombre,
    I.INC_codigoPatrimonial,
    (CONVERT(VARCHAR(10),CIE_fecha,103)) AS fechaCierreFormateada,
    C.CIE_asunto,
    C.CIE_documento,
    O.CON_descripcion,
    PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    CASE
    WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
      ELSE E.EST_descripcion
    END AS Estado
  FROM RECEPCION R
  INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
  RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
  INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
  INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
  INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
  WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5 AND
    (@codigoPatrimonial IS NULL OR I.INC_codigoPatrimonial = @codigoPatrimonial) AND 
    (@fechaInicio IS NULL OR CIE_fecha >= @fechaInicio) AND  
    (@fechaFin IS NULL OR CIE_fecha <= @fechaFin) AND        
    (@area IS NULL OR a.ARE_codigo = @area)    
  ORDER BY C.CIE_numero DESC
END
GO

---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR RECEPCION
CREATE PROCEDURE sp_eliminarRecepcion
    @IdRecepcion INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        DECLARE @NumeroIncidencia INT;

        -- Obtener el número de incidencia basado en el ID de recepción
        SELECT @NumeroIncidencia = INC_numero
        FROM RECEPCION
        WHERE REC_numero = @IdRecepcion;

        -- Actualizar el estado de la incidencia a 3
        UPDATE INCIDENCIA
        SET EST_codigo = 3
        WHERE INC_numero = @NumeroIncidencia;

        -- Eliminar la recepción basada en el ID de recepción
        DELETE FROM RECEPCION
        WHERE REC_numero = @IdRecepcion;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO

---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR CIERRE
CREATE PROCEDURE sp_eliminarCierre
    @IdCierre INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        DECLARE @NumeroRecepcion INT;

        -- Obtener el número de incidencia basado en el ID de recepción
        SELECT @NumeroRecepcion = REC_numero
        FROM CIERRE
        WHERE CIE_numero = @IdCierre;

        -- Actualizar el estado de la incidencia a 3
        UPDATE RECEPCION
        SET EST_codigo = 4
        WHERE REC_numero = @NumeroRecepcion;

        -- Eliminar la recepción basada en el ID de cierre
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

-------------------------------------------------------------------------------------------------------
  -- VISTAS
-------------------------------------------------------------------------------------------------------
--Vista para listar las nuevas incidencias para el administrador
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
  LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE 
    (EC.EST_codigo = 4 OR 
    (I.EST_codigo = 4 AND C.CIE_numero IS NULL))
    AND (I.EST_codigo IN (3, 4, 5) OR EC.EST_codigo IN (3, 4, 5));
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
FROM INCIDENCIA I
INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
        WHERE C2.REC_numero = R.REC_numero
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5));
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5;
GO

-- Vista para listar usuarios
CREATE VIEW vista_usuarios AS
SELECT USU_codigo, (p.PER_nombres + ' ' + p.PER_apellidoPaterno + ' '+ p.PER_apellidoMaterno) as persona, 
a.ARE_nombre, a.ARE_estado, USU_nombre, USU_password, r.ROL_nombre, e.EST_descripcion 
FROM USUARIO u
INNER JOIN PERSONA p on p.PER_codigo = u.PER_codigo
INNER JOIN AREA a on a.ARE_codigo = u.ARE_codigo
INNER JOIN ESTADO e on e.EST_codigo = u.EST_codigo
INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo;
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
LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
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

---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR INCIDENCIA
CREATE PROCEDURE sp_eliminarIncidencia
    @NumeroIncidencia INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        -- Eliminar la incidencia basada en el número de incidencia
        DELETE FROM INCIDENCIA
        WHERE INC_numero = @NumeroIncidencia;

        -- Confirmar la transacción
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO