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

    -- Obtener la direcci�n IP del cliente
    SET @AUD_ip = HOST_NAME(); 

    -- Obtener el nombre del equipo
    SET @AUD_nombreEquipo = HOST_NAME();

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
                -- Credenciales correctas, registrar inicio de sesi�n
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
                    @USU_codigo,           -- C�digo de usuario que inici� sesi�n
                    'USUARIO',             -- Tabla afectada
                    'Inicio de sesi�n',    -- Operaci�n de inicio de sesi�n
                    @AUD_ip,               -- Direcci�n IP del cliente
                    @AUD_nombreEquipo      -- Nombre del equipo del cliente
                );

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
			 -- Si las credenciales no son correctas, registrar intento fallido en auditor�a
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
					'Inicio de sesi�n fallido',     -- Intento fallido de inicio de sesi�n
					@AUD_ip,           -- Direcci�n IP del cliente
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

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR USUARIOS
--CREATE PROCEDURE sp_registrar_usuario (
--	@USU_nombre VARCHAR(20),
--	@USU_password VARCHAR(10),
--	@PER_codigo SMALLINT,
--	@ROL_codigo SMALLINT,
--	@ARE_codigo SMALLINT
--)
--AS
--BEGIN
--	-- Verificar si la persona ya tiene un usuario registrado
--	IF EXISTS (SELECT 1 FROM USUARIO WHERE PER_codigo = @PER_codigo)
--	BEGIN
--		-- Si la persona ya tiene un usuario, retornar un mensaje de error o un c�digo de error
--		RAISERROR('La persona ya tiene un usuario registrado.', 16, 1);
--		RETURN;
--	END

--	-- Insertar el nuevo usuario con EST_codigo siempre igual a 1
--	INSERT INTO USUARIO (USU_nombre, USU_password, PER_codigo, ROL_codigo, ARE_codigo, EST_codigo)
--	VALUES (@USU_nombre, @USU_password, @PER_codigo, @ROL_codigo, @ARE_codigo, 1);

--	-- Registrar la operaci�n en la tabla AUDITORIA
--	INSERT INTO AUDITORIA (
--		AUD_fecha, 
--		AUD_hora, 
--		AUD_usuario, 
--		AUD_tabla, 
--		AUD_operacion, 
--		AUD_ip, 
--		AUD_nombreEquipo
--	)
--	VALUES (
--		GETDATE(),             -- Fecha actual
--		CONVERT(TIME, GETDATE()), -- Hora actual
--		NULL,                   -- Usuario que realiz� la operaci�n (puedes dejar NULL o cambiar seg�n necesidad)
--		'USUARIO',              -- Tabla afectada
--		'Registro de usuario',  -- Operaci�n de registro de usuario
--		NULL,                   -- Direcci�n IP (puedes calcularla desde la aplicaci�n si es necesario)
--		HOST_NAME()             -- Nombre del equipo del cliente
--	);
--END;
--GO


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
	INSERT INTO PERSONA (PER_dni, @PER_nombres, @PER_apellidoPaterno, @PER_apellidoMaterno, @PER_celular, @PER_email)
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

--PROCEDIMIENTO ALMACENADO PARA REGISTRAR PERSONA
CREATE PROCEDURE sp_registrar_persona (
	@PER_DNI, 
	@PER_nombres, 
	@PER_apellidoPaterno, 
    @PER_apellidoMaterno, 
	@PER_celular, 
	@PER_email
)
AS
BEGIN













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
  -- Declarar variables para la auditor�a
  DECLARE @AUD_ip VARCHAR(50), @AUD_nombreEquipo VARCHAR(200);
  
  -- Asignar valores a las variables de auditor�a
  SET @AUD_ip = HOST_NAME();  -- Obtener IP del cliente (puedes ajustar seg�n tu entorno)
  SET @AUD_nombreEquipo = HOST_NAME();  -- Obtener nombre del equipo (tambi�n puedes ajustar)

  BEGIN TRY
    BEGIN TRANSACTION; -- Inicia una transacci�n para asegurar la consistencia de los datos

      -- Actualiza los datos del usuario
      UPDATE USUARIO
      SET 
          USU_nombre = @USU_nombre,
          USU_password = @USU_password
      WHERE USU_codigo = @USU_codigo;

      -- Registrar la operaci�n en la tabla AUDITORIA para la tabla USUARIO
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
          GETDATE(),                    -- Fecha actual
          CONVERT(TIME, GETDATE()),     -- Hora actual
          @USU_codigo,                 -- C�digo del usuario que realiz� la operaci�n
          'USUARIO',                   -- Tabla afectada
          'Actualizaci�n de datos de usuario', -- Descripci�n de la operaci�n
          @AUD_ip,                      -- IP del cliente
          @AUD_nombreEquipo             -- Nombre del equipo del cliente
      );

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

      -- Registrar la operaci�n en la tabla AUDITORIA para la tabla PERSONA
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
          GETDATE(),                    -- Fecha actual
          CONVERT(TIME, GETDATE()),     -- Hora actual
          @USU_codigo,                 -- C�digo del usuario que realiz� la operaci�n
          'PERSONA',                   -- Tabla afectada
          'Actualizaci�n de datos de persona', -- Descripci�n de la operaci�n
          @AUD_ip,                      -- IP del cliente
          @AUD_nombreEquipo             -- Nombre del equipo del cliente
      );

    COMMIT TRANSACTION; 
  END TRY
  BEGIN CATCH   
    -- Si ocurre un error, deshacer la transacci�n
    ROLLBACK TRANSACTION; 

    -- Capturar el mensaje de error y lanzarlo
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

