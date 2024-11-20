// $(document).ready(function () {
//   // Configuración de Toastr
//   toastr.options = {
//     "positionClass": "toast-bottom-right",
//     "progressBar": true,
//     "timeOut": "2000"
//   };


//   // Evento para restablecer contraseña
//   $('#restablecerContraseña').on('click', function (e) {
//     e.preventDefault();
//     enviarDatos($('#form-action').val());
//   });
// });

// // Metodo para restablecer contraseña
// function enviarDatos(action) {
//   if (action === 'restablecerContraseña') {
//     if (!validarCamposPassword()) {
//       return;
//     }
//   }

//   var url = 'modulo-usuario.php?action=' + action;
//   var data = $('#formCambiarPassword').serialize();
//   console.log('Datos enviados:', data); // Añadir esta línea para depuración

//   $.ajax({
//     url: url,
//     type: 'POST',
//     data: data,
//     dataType: "text",
//     success: function (response) {
//       try {
//         var jsonResponse = JSON.parse(response);
//         if (action === "restablecerContraseña") {
//           toastr.success(jsonResponse.message, 'Mensaje');  // Muestra mensaje de éxito
//           $('#modalCambiarPasswordUser').modal('hide');  // Cierra el modal
//         } else {
//           toastr.warning(jsonResponse.message, 'Advertencia');  // Muestra mensaje de advertencia
//         }
//       } catch (e) {
//         console.error('Json parsing error:', e);
//         toastr.error('Error al procesar la respuesta.', 'Mensaje de error');
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error('AJAX Error:', error);
//       toastr.error('Error en la solicitud AJAX.', 'Mensaje de error');
//     }
//   });

//   // Función para validar campos antes de enviar
//   function validarCamposPassword() {
//     var valido = true;
//     var mensajeError = '';

//     // Validar campos
//     var faltaConstrasenaNueva = ($('#passwordNuevo').val() === null || $('#passwordNuevo').val() === '');
//     var faltaConfirmarContrasena = ($('#passwordConfirm').val() === null || $('#passwordConfirm').val() === '');

//     if (faltaConfirmarContrasena && faltaConstrasenaNueva) {
//       mensajeError += 'Debe completar todos los campos.';
//       valido = false;
//     } else if (faltaConfirmarContrasena) {
//       mensajeError += 'Debe ingresar nuevamente la contrase&ntilde;a.';
//       valido = false;
//     } else if (faltaConstrasenaNueva) {
//       mensajeError += 'Debe ingresar una nueva contrase&ntilde;a.';
//       valido = false;
//     }

//     // Mostrar mensaje de error si hay
//     if (!valido) {
//       toastr.warning(mensajeError.trim(), 'Advertencia');
//     }
//     return valido;
//   }
// }



// --------------------------------------------------------------------

// var result = JSON.parse(response);
//     if (result.success) {
//       toastr.success(result.message);  // Muestra mensaje de éxito
//       $('#modalCambiarPasswordUser').modal('hide');  // Cierra el modal
//     } else {
//       toastr.error(result.message);  // Muestra mensaje de error
//     }
//   },
//   error: function (xhr, status, error) {
//     toastr.error('Ocurrió un error al procesar la solicitud. Inténtelo nuevamente.');
//   }
// });


// var codigoUsuario = $('#codigoUsuarioModal').val();
// $(document).ready(function () {
//   $('#restablecerContraseña').on('click', function () {
//     var codigoUsuario = $('#codigoUsuarioModal').val();
//     var passwordNuevo = $('#passwordNuevo').val();
//     var passwordConfirm = $('#passwordConfirm').val();

//     var url = 'modulo-usuario.php?action=' + action;
//     var data = $('#formUsuario').serialize();
//     console.log('Datos enviados:', data); // Añadir esta línea para depuración

//     // Validación del lado del cliente
//     if (passwordNuevo === '' || passwordConfirm === '') {
//       toastr.warning('Debe completar ambos campos de contraseña.');
//       return;
//     }
//     if (passwordNuevo !== passwordConfirm) {
//       toastr.warning('La nueva contraseña y la confirmación no coinciden.');
//       return;
//     }

//     // Envío de datos mediante AJAX
//     $.ajax({
//       url: 'modulo-usuario.php?action=restablecerContraseña',
//       type: 'POST',
//       data: {
//         codigoUsuarioModal: codigoUsuario,
//         passwordNuevo: passwordNuevo,
//         passwordConfirm: passwordConfirm
//       },
//       dataType: "json",
//       success: function (response) {
//         var result = JSON.parse(response);
//         if (result.success) {
//           toastr.success(result.message);  // Muestra mensaje de éxito
//           $('#modalCambiarPasswordUser').modal('hide');  // Cierra el modal
//         } else {
//           toastr.error(result.message);  // Muestra mensaje de error
//         }
//       },
//       error: function (xhr, status, error) {
//         toastr.error('Ocurrió un error al procesar la solicitud. Inténtelo nuevamente.');
//       }
//     });
//   });
// });














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
