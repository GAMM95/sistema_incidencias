$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento para restablecer contraseña
  $('#restablecerContraseña').on('click', function (e) {
    e.preventDefault();
    enviarDatos($('#form-action-restablecerContraseña').val()); // Obtén el valor correcto del input oculto
  });
});

// Función principal para enviar datos del formulario
function enviarDatos(action) {
  if (action === 'restablecerContraseña' && !validarCamposPassword()) {
    return; // No continuar si no pasa la validación
  }

  var url = 'modulo-usuario.php?action=' + action;
  var data = $('#formCambiarPassword').serialize();

  // Mostrar los datos en consola antes de enviar
  console.log('URL de envío:', url);
  console.log('Datos enviados:', data);

  // Realiza la petición AJAX
  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: "json", // Asegúrate de que el servidor responda con JSON
    success: function (response) {
      console.log('Respuesta del servidor:', response); // Mostrar respuesta en consola
      if (response.success) {
        toastr.success(response.message, 'Éxito'); // Mensaje de éxito
        $('#modalCambiarPasswordUser').modal('hide'); // Cierra el modal
        $('#formCambiarPassword')[0].reset(); // Limpia el formulario
      } else {
        toastr.warning(response.message, 'Advertencia'); // Mensaje de advertencia
      }
    },
    error: function (xhr, status, error) {
      console.error('Error en la solicitud AJAX:', {
        status: status,
        error: error,
        responseText: xhr.responseText
      });
      toastr.error('Error en la solicitud. Por favor, intenta nuevamente.', 'Error');
    }
  });
}

// Función para validar campos antes de enviar
function validarCamposPassword() {
  var valido = true;
  var mensajeError = '';

  // Validar campos
  var codigoUsuario = $('#codigoUsuarioModal').val();
  var contrasenaNueva = $('#passwordNuevo').val();
  var confirmarContrasena = $('#passwordConfirm').val();

  if (!contrasenaNueva && !confirmarContrasena) {
    mensajeError = 'Debe completar todos los campos.';
    valido = false;
  } else if (!contrasenaNueva) {
    mensajeError = 'Debe ingresar una nueva contrase&ntilde;a.';
    valido = false;
  } else if (!confirmarContrasena) {
    mensajeError = 'Debe confirmar la contrase&ntilde;a.';
    valido = false;
  } else if (contrasenaNueva !== confirmarContrasena) {
    mensajeError = 'Las contraseñas no coinciden.';
    valido = false;
  } else if (!codigoUsuario) {
    mensajeError = "Falta el código de usuario.";
    valido = false;
  }

  // Mostrar mensaje si no es válido
  if (!valido) {
    toastr.warning(mensajeError, 'Advertencia');
  }

  return valido;
}


document.addEventListener("DOMContentLoaded", function () {
  // Referencias a los campos e iconos
  const passwordNuevo = document.getElementById("passwordNuevo");
  const togglePasswordNuevo = document.getElementById("togglePasswordNuevo");

  const passwordConfirm = document.getElementById("passwordConfirm");
  const togglePasswordConfirm = document.getElementById("togglePasswordConfirm");

  // Función para alternar visibilidad de la contraseña
  function togglePasswordVisibility(inputField, toggleIcon) {
    if (inputField.type === "password") {
      inputField.type = "text";
      toggleIcon.innerHTML = "<i class='feather text-gray-400 icon-eye-off'></i>";
    } else {
      inputField.type = "password";
      toggleIcon.innerHTML = "<i class='feather text-gray-400 icon-eye'></i>";
    }
  }

  // Agregar event listeners para cada campo
  togglePasswordNuevo.addEventListener("click", () => {
    togglePasswordVisibility(passwordNuevo, togglePasswordNuevo);
  });

  togglePasswordConfirm.addEventListener("click", () => {
    togglePasswordVisibility(passwordConfirm, togglePasswordConfirm);
  });
});
