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
  if (action === 'restablecerContraseña') {
    if (!validarCamposPassword()) {
      return;
    }
  }
  var url = 'modulo-usuario.php?action=' + action;
  var data = $('#formCambiarPassword').serialize();  // Obtener los datos del formulario
  console.log('Datos enviados:', data); // Mostrar los datos del formulario

  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: "text",
    success: function (response) {
      try {
        var jsonResponse = JSON.parse(response);
        console.log('Respuesta parseada:', jsonResponse); 
        
        if (jsonResponse.success) {
          if (action === 'restablecerContraseña') {
            toastr.success(jsonResponse.message, 'Mensaje');
            $('#modalCambiarPasswordUser').modal('hide');

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
      toastr.error('Error en la solicitud AJAX.', 'Mensaje de error');
    }
  });
}

// Función para validar campos antes de enviar
function validarCamposPassword() {
  var valido = true;
  var mensajeError = '';
  // Validar campos
  var faltaContrasenaNueva = ($('#passwordNuevo').val() === null || $('#passwordNuevo').val() === '');
  var faltaConfirmarContrasena = ($('#passwordConfirm').val() === null || $('#passwordConfirm').val() === '');

  var contrasenaNueva = $('#passwordNuevo').val();
  var contrasenaConfirmada = $('#passwordConfirm').val();

  if (faltaContrasenaNueva || faltaConfirmarContrasena) { // Verificar si faltan alguno de los campos requeridos      
    mensajeError += 'Debe completar todos los campos.';
    valido = false;
  } else if (faltaContrasenaNueva) {
    mensajeError += 'Debe ingresar una nueva contrase&ntilde;a.';
    valido = false;
  } else if (faltaConfirmarContrasena) {
    mensajeError += 'Debe confirmar la contrase&ntilde;a.';
    valido = false;
  } else if (contrasenaNueva !== contrasenaConfirmada) {
    mensajeError += 'Las contraseñas no coinciden.';
    valido = false;
  }
  // Mostrar mensaje si no es válido
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// // Función para validar campos antes de enviar
// function validarCamposPassword() {
//   var valido = true;
//   var mensajeError = '';
//   // Validar campos
//   var contrasenaNueva = $('#passwordNuevo').val();
//   var confirmarContrasena = $('#passwordConfirm').val();

//   if (!contrasenaNueva && !confirmarContrasena) {
//     mensajeError = 'Debe completar todos los campos.';
//     valido = false;
//   } else if (!contrasenaNueva) {
//     mensajeError = 'Debe ingresar una nueva contrase&ntilde;a.';
//     valido = false;
//   } else if (!confirmarContrasena) {
//     mensajeError = 'Debe confirmar la contrase&ntilde;a.';
//     valido = false;
//   } else if (contrasenaNueva !== confirmarContrasena) {
//     mensajeError = 'Las contraseñas no coinciden.';
//     valido = false;
//   }
//   // Mostrar mensaje si no es válido
//   if (!valido) {
//     toastr.warning(mensajeError, 'Advertencia');
//   }
//   return valido;
// }