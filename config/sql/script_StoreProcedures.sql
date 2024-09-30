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

USE SISTEMA_HELPDESK;
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

    DECLARE @USU_codigo SMALLINT;
    DECLARE @EST_codigo SMALLINT;
    DECLARE @AUD_ip VARCHAR(50);
    DECLARE @AUD_nombreEquipo VARCHAR(50);

    -- Obtener la dirección IP del cliente
    SET @AUD_ip = HOST_NAME(); 

    -- Obtener el nombre del equipo
    SET @AUD_nombreEquipo = HOST_NAME();

    -- Intento de login: Asignar el código de usuario y estado
    SELECT @USU_codigo = u.USU_codigo, @EST_codigo = u.EST_codigo
    FROM USUARIO u
    WHERE u.USU_nombre = @USU_usuario;

    -- Verificar si el usuario existe
    IF @USU_codigo IS NOT NULL
    BEGIN
        -- Verificar si el usuario está activo
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
                -- Credenciales correctas, registrar inicio de sesión
                INSERT INTO AUDITORIA (
                    AUD_fecha, 
                    AUD_hora, 
                    AUD_usuario, 
                    AUD_tabla, 
                    AUD_operacion, 
                    AUD_ip, 
                    AUD_nombreEquipo
                )
                VALUES (
                    GETDATE(),             -- Fecha actual
                    CONVERT(TIME, GETDATE()), -- Hora actual
                    @USU_codigo,           -- Código de usuario que inició sesión
                    'USUARIO',             -- Tabla afectada
                    'Inicio de sesión',    -- Operación de inicio de sesión
                    @AUD_ip,               -- Dirección IP del cliente
                    @AUD_nombreEquipo      -- Nombre del equipo del cliente
                );

                -- Devolver datos del usuario para la sesión
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
			 -- Si las credenciales no son correctas, registrar intento fallido en auditoría
				INSERT INTO AUDITORIA (
					AUD_fecha, 
					AUD_hora, 
					AUD_usuario, 
					AUD_tabla, 
					AUD_operacion, 
					AUD_ip, 
					AUD_nombreEquipo
				)
				VALUES (
					GETDATE(),
					CONVERT(TIME, GETDATE()),
					NULL,               -- Usuario no identificado
					'USUARIO',
					'Inicio de sesión fallido',     -- Intento fallido de inicio de sesión
					@AUD_ip,           -- Dirección IP del cliente
					@AUD_nombreEquipo   -- Nombre del equipo del cliente
				);

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

-- PROCEDIMIENTO ALMACENADO PARA HABILITAR USUARIO
--CREATE PROCEDURE sp_habilitar_usuario
--	@codigoUsuario SMALLINT
--AS
--BEGIN
--	UPDATE USUARIO SET EST_codigo = 1
--    WHERE EST_codigo = 2 AND  USU_codigo = @codigoUsuario;
--END;
--GO



--CREATE PROCEDURE sp_habilitar_usuario
--    @codigoUsuario SMALLINT
--AS
--BEGIN
--    -- Actualizar el estado del usuario
--    UPDATE USUARIO 
--    SET EST_codigo = 1
--    WHERE EST_codigo = 2 AND USU_codigo = @codigoUsuario;

--    -- Insertar en la tabla AUDITORIA después de actualizar al usuario
--    INSERT INTO AUDITORIA (AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo)
--    VALUES (GETDATE(), CONVERT(TIME, GETDATE()), @codigoUsuario, 'USUARIO', 'Habilitar Usuario', HOST_NAME(), HOST_NAME());
--END;
--GO

CREATE PROCEDURE sp_habilitar_usuario
    @codigoUsuario SMALLINT
AS
BEGIN
    -- Actualizar el estado del usuario
    UPDATE USUARIO 
    SET EST_codigo = 1
    WHERE EST_codigo = 2 AND USU_codigo = @codigoUsuario;

    -- Capturar la dirección IP del cliente con conversión a NVARCHAR
    DECLARE @ipCliente NVARCHAR(50);
    SET @ipCliente = CONVERT(NVARCHAR(50), CONNECTIONPROPERTY('client_net_address'));

    -- Insertar en la tabla AUDITORIA después de actualizar al usuario
    INSERT INTO AUDITORIA (AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo)
    VALUES (GETDATE(), CONVERT(TIME, GETDATE()), @codigoUsuario, 'USUARIO', 'Habilitar Usuario', @ipCliente, HOST_NAME());
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

	-- Capturar la dirección IP del cliente con conversión a NVARCHAR
	DECLARE @ipCliente NVARCHAR(50);
    SET @ipCliente = CONVERT(NVARCHAR(50), CONNECTIONPROPERTY('client_net_address'));

    -- Insertar en la tabla AUDITORIA después de actualizar al usuario
    INSERT INTO AUDITORIA (AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo)
    VALUES (GETDATE(), CONVERT(TIME, GETDATE()), @codigoUsuario, 'USUARIO', 'Deshabilitar Usuario', @ipCliente, HOST_NAME());
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

        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO AREA (ARE_nombre, EST_codigo)
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
	        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre, EST_codigo)
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
	        -- Inserta el área con ARE_estado siempre en 1
        INSERT INTO CATEGORIA (CAT_nombre, EST_codigo)
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

