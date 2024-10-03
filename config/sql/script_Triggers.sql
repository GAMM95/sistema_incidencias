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
    SET @formato = RIGHT('000' + CAST(@numero AS VARCHAR(3)), 3) + '-' + @año_actual + '-MDE';
    SET @resultado = @formato;

    RETURN @resultado;
END;
GO

-- TRIGGER PARA ACTUALIZAR EL NUMERO DE INCIDENCIA FORMATEADO "INC_numero_formato"
CREATE TRIGGER trg_incrementar_numeroIncidencia
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
CREATE TRIGGER trg_incrementar_numeroRecepcion
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

-- TRIGGER PARA GENERAR EL NUMERO DE ASIGNACION AUMENTADO EN 1
CREATE TRIGGER trg_incrementar_numeroAsignacion
ON ASIGNACION
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el último número de recepción
    SELECT @ultimo_numero = ISNULL(MAX(ASI_codigo), 0) FROM ASIGNACION;

    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO ASIGNACION (ASI_codigo, ASI_fecha, ASI_hora, EST_codigo, USU_codigo, REC_numero)
    SELECT @ultimo_numero + 1, ASI_fecha, ASI_hora, EST_codigo, USU_codigo, REC_numero
    FROM inserted;
END;
GO

-- TRIGGER PARA GENERAR EL NUMERO DE MANTENIMIENTO AUMENTADO EN 1
CREATE TRIGGER trg_incrementar_numeroMantenimiento
ON MANTENIMIENTO
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el último número de recepción
    SELECT @ultimo_numero = ISNULL(MAX(MAN_codigo), 0) FROM MANTENIMIENTO;

    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO MANTENIMIENTO (MAN_codigo, MAN_fecha, MAN_hora, EST_codigo, ASI_codigo)
    SELECT @ultimo_numero + 1, MAN_fecha, MAN_hora, EST_codigo, ASI_codigo
    FROM inserted;
END;
GO


-- TRIGGER PARA GENERAR EL NUMERO DE CIERRE AUMENTADO EN 1
CREATE TRIGGER trg_incrementar_numeroCierre
ON CIERRE
INSTEAD OF INSERT
AS
BEGIN
    DECLARE @ultimo_numero SMALLINT;

    -- Obtener el último número de recepción
    SELECT @ultimo_numero = ISNULL(MAX(CIE_numero), 0) FROM CIERRE;
    -- Insertar el nuevo registro con REC_numero incrementado en 1
    INSERT INTO CIERRE (CIE_numero, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, EST_codigo, MAN_codigo, USU_codigo)
    SELECT @ultimo_numero + 1, CIE_fecha, CIE_hora, CIE_diagnostico, CIE_documento, CIE_asunto, CIE_recomendaciones, CON_codigo, EST_codigo, MAN_codigo, USU_codigo
    FROM inserted;
END;
GO
