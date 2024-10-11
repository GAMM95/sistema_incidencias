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
  -- PROCEDIMIENTOS ALMACENADOS
-------------------------------------------------------------------------------------------------------

-- PROCEDIMIENTO ALMACENADO PARA INICIAR SESION 
CREATE PROCEDURE sp_login (
    @USU_usuario VARCHAR(20),
    @USU_password VARCHAR(10)
) 
AS 
BEGIN
    SET NOCOUNT ON;

    DECLARE @USU_codigo SMALLINT;
    DECLARE @EST_codigo SMALLINT;

    -- Intento de login: Asignar el c�digo de usuario y estado
    SELECT @USU_codigo = u.USU_codigo, @EST_codigo = u.EST_codigo
    FROM USUARIO u
    WHERE u.USU_nombre = @USU_usuario;

    -- Verificar si el usuario existe
    IF @USU_codigo IS NOT NULL
    BEGIN
        -- Verificar si el usuario est� activo
        IF @EST_codigo = 1
        BEGIN
            -- Verificar si las credenciales son correctas
            IF EXISTS (
                SELECT 1 
                FROM USUARIO u
                WHERE u.USU_nombre = @USU_usuario 
                AND u.USU_password = @USU_password
            )
            BEGIN
                -- Devolver datos del usuario para la sesi�n
                SELECT 
					u.USU_codigo,
                    u.USU_nombre, 
                    p.PER_nombres, 
                    p.PER_apellidoPaterno, 
                    r.ROL_codigo, 
                    r.ROL_nombre, 
                    a.ARE_codigo, 
                    a.ARE_nombre, 
                    u.EST_codigo
                FROM USUARIO u
                INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
                INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
                INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
                WHERE u.USU_codigo = @USU_codigo;
            END
            ELSE
            BEGIN
                -- Credenciales incorrectas
                SELECT 'Credenciales incorrectas' AS MensajeError;
            END
        END
        ELSE
        BEGIN
            -- Usuario inactivo, devolver mensaje de error
            SELECT 'Usuario inactivo. Por favor, contacte al administrador.' AS MensajeError;
        END
    END
    ELSE
    BEGIN
        -- Si el usuario no existe
        SELECT 'Usuario no encontrado' AS MensajeError;
    END
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR PERSONA
CREATE PROCEDURE sp_registrar_persona (
	@PER_dni CHAR(8),
	@PER_nombres VARCHAR(20),
	@PER_apellidoPaterno VARCHAR(15),
	@PER_apellidoMaterno VARCHAR(15),
	@PER_celular CHAR(9),
	@PER_email VARCHAR(45)
)
AS
BEGIN
	--Insertar la nueva persona
	INSERT INTO PERSONA (PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno, PER_celular, PER_email)
	VALUES (@PER_dni, @PER_nombres, @PER_apellidoPaterno, @PER_apellidoMaterno, @PER_celular, @PER_email);
END
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR USUARIO
CREATE PROCEDURE sp_registrar_usuario (
	@USU_nombre VARCHAR(20),
	@USU_password VARCHAR(10),
	@PER_codigo SMALLINT,
	@ROL_codigo SMALLINT,
	@ARE_codigo SMALLINT
)
AS
BEGIN
	-- Verificar si la persona ya tiene un usuario registrado
	IF EXISTS (SELECT 1 FROM USUARIO WHERE PER_codigo = @PER_codigo)
	BEGIN
		-- Si la persona ya tiene un usuario, retornar un mensaje de error o un c�digo de error
		RAISERROR('La persona ya tiene un usuario registrado.', 16, 1);
		RETURN;
	END

	-- Insertar el nuevo usuario con EST_codigo siempre igual a 1
	INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
	VALUES (@USU_nombre, @USU_password, @PER_codigo, @ROL_codigo, @ARE_codigo, 1);
END;
GO

--PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR DATOS DE USUARIO
CREATE PROCEDURE sp_editar_usuario
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
            RAISERROR('El nombre de usuario ya est� en uso.', 16, 1);
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



--PROCEDIMIENTO ALMACENADO PARA EDITAR PERFIL  
CREATE PROCEDURE sp_editar_perfil
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
    BEGIN TRANSACTION; -- Inicia una transacci�n para asegurar la consistencia de los datos
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

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR USUARIO
CREATE PROCEDURE sp_habilitar_usuario
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
CREATE PROCEDURE sp_deshabilitar_usuario
	@codigoUsuario SMALLINT
AS
BEGIN
    -- Actualizar el estado del usuario
	UPDATE USUARIO SET EST_codigo = 2 
    WHERE EST_codigo = 1 AND  USU_codigo = @codigoUsuario;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR AREAS
CREATE PROCEDURE sp_registrar_area
    @NombreArea VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;

        -- Inserta el �rea con ARE_estado siempre en 1
        INSERT INTO AREA (ARE_nombre, EST_codigo)
        VALUES (@NombreArea, 1);

        -- Confirmar la transacci�n si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacci�n en caso de error
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
CREATE PROCEDURE sp_deshabilitar_area
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR AREA
CREATE PROCEDURE sp_habilitar_area
	@codigoArea SMALLINT
AS
BEGIN
	UPDATE AREA SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  ARE_codigo = @codigoArea;
END;
GO

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR BIEN
CREATE PROCEDURE sp_registrar_bien
    @codigoIdentificador VARCHAR(12),
    @NombreBien VARCHAR(100)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el �rea con ARE_estado siempre en 1
        INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo)
        VALUES (@codigoIdentificador, @NombreBien, 1);

        -- Confirmar la transacci�n si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacci�n en caso de error
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

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE PROCEDURE sp_habilitar_bien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  BIE_codigo = @codigoBien;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA DESHABILITAR BIEN
CREATE PROCEDURE sp_deshabilitar_bien
	@codigoBien SMALLINT
AS
BEGIN
	UPDATE BIEN SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  BIE_codigo = @codigoBien;
END;
GO


--PROCEDIMIENTO ALMACENADO PARA REGISTRAR CATEGORIAS
CREATE PROCEDURE sp_registrar_categoria
    @NombreCategoria VARCHAR(60)
AS
BEGIN
    -- Manejo de errores y transacciones
    BEGIN TRY
        BEGIN TRANSACTION;
	        -- Inserta el �rea con ARE_estado siempre en 1
        INSERT INTO CATEGORIA (CAT_nombre, EST_codigo)
        VALUES (@NombreCategoria, 1);

        -- Confirmar la transacci�n si todo sale bien
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Revertir la transacci�n en caso de error
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
CREATE PROCEDURE sp_deshabilitar_categoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET EST_codigo = 2 
   WHERE (EST_codigo = 1 OR  EST_codigo = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR BIEN
CREATE PROCEDURE sp_habilitar_categoria
	@codigoCategoria SMALLINT
AS
BEGIN
	UPDATE CATEGORIA SET EST_codigo = 1
    WHERE (EST_codigo = 2 OR  EST_codigo = '')
	AND  CAT_codigo = @codigoCategoria;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR INCIDENCIA - ADMINISTRADOR / USUARIO
CREATE PROCEDURE sp_registrar_incidencia
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
      -- Generar el n�mero de incidencia formateado
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
CREATE PROCEDURE sp_actualizar_incidencia
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
CREATE PROCEDURE sp_actualizar_incidencia_usuario
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

---- PROCEDIMIENTO ALMACENADO PARA ELIMINAR INCIDENCIA
CREATE PROCEDURE sp_eliminar_incidencia
    @NumeroIncidencia INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        -- Eliminar la incidencia basada en el n�mero de incidencia
        DELETE FROM INCIDENCIA
        WHERE INC_numero = @NumeroIncidencia;

        -- Confirmar la transacci�n
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA INSERTAR LA RECEPCION Y ACTUALIZAR ESTADO DE INCIDENCIA
CREATE PROCEDURE sp_insertar_recepcion (
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

        -- Verificar si ya existe una recepci�n con los mismos valores
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
            -- Insertar la nueva recepci�n
            INSERT INTO RECEPCION (REC_fecha, REC_hora, INC_numero, PRI_codigo, IMP_codigo, USU_codigo, EST_codigo)
            VALUES (@REC_fecha, @REC_hora, @INC_numero, @PRI_codigo, @IMP_codigo, @USU_codigo, 4);
            
            -- Actualizar el estado de la incidencia
            UPDATE INCIDENCIA 
            SET EST_codigo = 4
            WHERE INC_numero = @INC_numero;
        END
        ELSE
        BEGIN
            -- Mensaje que la recepci�n ya existe
            PRINT 'La recepci�n ya existe y no se puede registrar nuevamente.';
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
CREATE PROCEDURE sp_actualizar_recepcion
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
CREATE PROCEDURE sp_eliminar_recepcion
    @IdRecepcion INT
AS
BEGIN
    BEGIN TRANSACTION;
    BEGIN TRY
        DECLARE @NumeroIncidencia INT;

        -- Obtener el n�mero de incidencia basado en el ID de recepci�n
        SELECT @NumeroIncidencia = INC_numero
        FROM RECEPCION
        WHERE REC_numero = @IdRecepcion;

        -- Actualizar el estado de la incidencia a 3
        UPDATE INCIDENCIA
        SET EST_codigo = 3
        WHERE INC_numero = @NumeroIncidencia;

        -- Eliminar la recepci�n basada en el ID de recepci�n
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

-- PROCEDIMIENTO ALMACENADO PARA ISNERTAR MANTENIMIENTO
CREATE PROCEDURE sp_registrar_mantenimiento
    @EST_codigo SMALLINT,
    @ASI_codigo SMALLINT
AS 
BEGIN
    -- Declarar variables para almacenar la fecha y la hora del sistema
    DECLARE @FechaSistema DATE = CONVERT(DATE, GETDATE());
    DECLARE @HoraSistema TIME(0) = CONVERT(TIME(0), GETDATE()); -- Hora en formato hh:mm:ss

    SET NOCOUNT ON;

    BEGIN TRY 
        BEGIN TRANSACTION;

        -- Verificar si ya existe una recepción con los mismos valores
        IF NOT EXISTS (
            SELECT 1 
            FROM MANTENIMIENTO 
            WHERE 
                MAN_fecha = @FechaSistema 
                AND MAN_hora = @HoraSistema 
                AND EST_codigo = @EST_codigo 
                AND ASI_codigo = @ASI_codigo
        )
        BEGIN
            -- Insertar la nueva recepción
            INSERT INTO MANTENIMIENTO (MAN_fecha, MAN_hora, EST_codigo, ASI_codigo)
            VALUES (@FechaSistema, @HoraSistema, @EST_codigo, @ASI_codigo); -- ESTADO "EN ESPERA"         
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


-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR ASINACION - ADMINISTRADOR / USUARIO
CREATE PROCEDURE sp_registrar_asignacion
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

        -- Verificar si ya existe una asignación con los mismos valores
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
        END;

        -- Confirmar la transacción
        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        -- Hacer rollback en caso de error
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;

        -- Devolver el mensaje de error
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();

        RAISERROR(@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
GO

-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR ASIGNACION
CREATE PROCEDURE sp_actualizar_asignacion
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


-- PROCEDIMIENTO ALMAENADO PARA INSERTAR CIERRES Y ACTUALIZAR ESTADO DE ASIGNACION
CREATE PROCEDURE sp_registrar_cierre
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