// Metodo para mostrar y ocultar contraseñas
document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencia a cada campo de entrada de contraseña y sus respectivos iconos de alternar
  const passwordActualInput = document.getElementById("passwordActual");
  const togglePasswordActual = document.getElementById("togglePasswordActual");

  const passwordNuevoInput = document.getElementById("passwordNuevo");
  const togglePasswordNuevo = document.getElementById("togglePasswordNuevo");

  const passwordConfirmInput = document.getElementById("passwordConfirm");
  const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");

  // Función para alternar el tipo de entrada entre "password" y "text"
  function togglePasswordVisibility(inputField, toggleIcon) {
    if (inputField.type === "password") {
      inputField.type = "text";
      toggleIcon.innerHTML = "<i class='feather text-gray-400 icon-eye-off'></i>";
    } else {
      inputField.type = "password";
      toggleIcon.innerHTML = "<i class='feather text-gray-400 icon-eye'></i>";
    }
  }

  // Event listeners para alternar la visibilidad de cada campo de contraseña
  togglePasswordActual.addEventListener("click", function () {
    togglePasswordVisibility(passwordActualInput, togglePasswordActual);
  });

  togglePasswordNuevo.addEventListener("click", function () {
    togglePasswordVisibility(passwordNuevoInput, togglePasswordNuevo);
  });

  togglePasswordConfirm.addEventListener("click", function () {
    togglePasswordVisibility(passwordConfirmInput, togglePasswordConfirm);
  });
});

$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
  // Evento para restablecer contraseña
  $('#cambiarPasswordPerfil').on('click', function (e) {
    e.preventDefault();
    enviarDatos($('#form-action-cambiar').val());
  });
});

// Funcion principal para enviar datos del formulario
function enviarDatos(action) {
  if (action === 'cambiar') {
    if (!validarCamposPasswordPerfil()) {
      return;
    }
  }

  var url = 'mi-perfil.php?action=' + action;
  var data = $('#formPerfil').serialize(); // Obtener los datos del formulario
  console.log('Datos enviados:', data); // Mostrar los datos del formulario

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: "text",
    success: function (response) {
      try {
        var jsonResponse = JSON.parse(response);
        console.log('Respuesta parseada:', jsonResponse);

        if (jsonResponse.success) {
          if (action === 'cambiar') {
            toastr.success(jsonResponse.message, 'Mensaje');
            $('#modalCambiarPassword').modal('hide');
            // Limpiar los campos de contraseña
            $('#passwordActual').val('');
            $('#passwordNuevo').val('');
            $('#passwordConfirm').val('');
          }
        } else {
          toastr.warning(jsonResponse.message, 'Advertencia');
        }
      } catch (e) {
        console.error('Error al procesar la respuesta:', e);
        toastr.error('Error al procesar la respuesta.', 'Mensaje de error');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error en la solicitud AJAX:', {
        status: status,
        error: error,
        responseText: xhr.responseText
      });
      toastr.success('Hubo un problema al cambiar la contrase&ntilde;a.', 'Mensaje de error');
    }
  });
}

// Función para validar campos antes de enviar
function validarCamposPasswordPerfil() {
  var valido = true;
  var mensajeError = '';

  // Validar campos
  var faltaConstrasenaActual = ($('#passwordActual').val() === null || $('#passwordActual').val() === '');
  var faltaContrasenaNueva = ($('#passwordNuevo').val() === null || $('#passwordNuevo').val() === '');
  var faltaConfirmarContrasena = ($('#passwordConfirm').val() === null || $('#passwordConfirm').val() === '');

  if (faltaConstrasenaActual || faltaContrasenaNueva || faltaConfirmarContrasena) { // Verificar si faltan alguno de los campos requeridos
    mensajeError += 'Debe completar todos los campos.';
    valido = false;
  } else if (faltaConstrasenaActual) {
    mensajeError += 'Debe ingresar la contrase&ntilde;a actual.';
    valido = false;
  } else if (faltaContrasenaNueva) {
    mensajeError += 'Debe ingresar una nueva contrase&ntilde;a.';
    valido = false;
  } else if (faltaConfirmarContrasena) {
    mensajeError += 'Debe confirmar la contrase&ntilde;a.';
    valido = false;
  }
  // Mostrar mensaje si no es válido
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencia a los campos de contraseña
  const passwordNuevoInput = document.getElementById("passwordNuevo");
  const passwordConfirmInput = document.getElementById("passwordConfirm");
  const mensajeConfirmacion = document.getElementById("mensajeConfirmacion");

  // Función para verificar si las contraseñas coinciden
  function verificarCoincidencia() {
    const contrasenaNueva = passwordNuevoInput.value;
    const contrasenaConfirmada = passwordConfirmInput.value;

    if (contrasenaNueva !== "" && contrasenaConfirmada !== "") {
      if (contrasenaNueva === contrasenaConfirmada) {
        mensajeConfirmacion.style.display = "block";
        mensajeConfirmacion.style.color = "green";
        mensajeConfirmacion.textContent = "Las contraseñas coinciden.";
      } else {
        mensajeConfirmacion.style.display = "block";
        mensajeConfirmacion.style.color = "red";
        mensajeConfirmacion.textContent = "Las contraseñas no coinciden.";
      }
    } else {
      mensajeConfirmacion.style.display = "none"; // Ocultar mensaje si los campos están vacíos
    }
  }

  // Agregar eventos de escucha a los inputs de contraseña y confirmación
  passwordNuevoInput.addEventListener("input", verificarCoincidencia);
  passwordConfirmInput.addEventListener("input", verificarCoincidencia);
});