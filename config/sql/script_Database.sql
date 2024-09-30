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
	PER_codigo SMALLINT IDENTITY(1, 1),
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

-- CREACION DE LA TABLA AREA
CREATE TABLE AREA (
	ARE_codigo SMALLINT IDENTITY(1, 1),
	ARE_nombre VARCHAR(100) UNIQUE NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_area PRIMARY KEY (ARE_codigo),
);
GO

-- CREACION DE LA TABLA USUARIO
CREATE TABLE USUARIO (
	USU_codigo SMALLINT IDENTITY(1, 1),
	USU_nombre VARCHAR(20) UNIQUE NOT NULL,
	USU_password VARCHAR(50) NOT NULL,
	PER_codigo SMALLINT NOT NULL,
	ROL_codigo SMALLINT NOT NULL,
	ARE_codigo SMALLINT NOT NULL,
	EST_codigo SMALLINT NOT NULL,
	CONSTRAINT pk_usuario PRIMARY KEY (USU_codigo),
	CONSTRAINT fk_persona_usuario FOREIGN KEY (PER_codigo) REFERENCES PERSONA(PER_codigo),
	CONSTRAINT fk_rol_usuario FOREIGN KEY (ROL_codigo) REFERENCES ROL(ROL_codigo),
	CONSTRAINT fk_area_usuario FOREIGN KEY (ARE_codigo) REFERENCES AREA(ARE_codigo),
);
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
	CONSTRAINT pk_auditoria PRIMARY KEY (AUD_codigo)
);