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

USE BD_INCIDENCIAS;
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
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Informática y Sistemas', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia Municipal', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Contabilidad', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Alcaldía', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Tesorería', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Sección de Almacén', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Abastecimiento y Control Patrimonial', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Control Patrimonial', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Caja General', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Recursos Humanos', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Desarrollo Económico Local', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Área de Liquidación de Obras', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Habilitación Urbana y Catastro', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Escalafón', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Secretaría General', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Programa de Vaso de Leche', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('DEMUNA', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('OMAPED', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Salud', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Administración Tributaria', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Servicio Social', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Relaciones Públicas y Comunicaciones', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Gestión Ambiental', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Asesoría Jurídica', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Planificación y Modernización Institucional', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Gestión y Desarrollo de RR.HH.', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Desarrollo Social y Promoción de la Familia', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Educación', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Programas Sociales e Inclusión', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Licencias', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Policía Municipal', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Registro Civil', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Mantenimiento de Obras Públicas', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Desarrollo Urbano y Planeamiento Territorial', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Ejecución Coactiva', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Estudios y Proyectos', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Obras', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Procuradoría Pública Municipal', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Administración y Finanzas', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Defensa Civil', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Juventud, Deporte y Cultura', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Áreas Verdes', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Seguridad Ciudadana', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Órgano de Control Institucional', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad Local de Empadronamiento - ULE', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Atención al Usuario y Trámite Documentario', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Seguridad Ciudadana, Defensa Civil y Tránsito', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Abastecimiento', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Unidad de Participación Vecinal', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Gerencia de Planeamiento, Presupuesto y Modernización', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo)  VALUES ('Subgerencia de Transporte, Tránsito y Seguridad Vial', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Archivo Central', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Equipo Mecánico y Maestranza', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo)  VALUES ('Subgerencia de Limpieza Pública', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Subgerencia de Bienestar Social', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Orientación Tributaria', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Servicios Generales', 1);
INSERT INTO AREA(ARE_nombre, EST_codigo) VALUES ('Secretaría Técnica de Procesos Administrativos Disciplinarios', 1);
GO

-- VOLCADO DE DATOS PARA LA TABLA ESTADO
INSERT INTO ESTADO (EST_descripcion) VALUES ('ACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('INACTIVO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('ABIERTO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('RECEPCIONADO');
INSERT INTO ESTADO (EST_descripcion) VALUES ('EN ESPERA');
INSERT INTO ESTADO (EST_descripcion) VALUES ('ATENDIDO');
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
go