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
// function enviarDatos(action) {
//   if (action === 'restablecerContraseña' && !validarCamposPassword()) {
//     return; // No continuar si no pasa la validación
//   }
//   var url = 'modulo-usuario.php?action=' + action;
//   var data = $('#formCambiarPassword').serialize();

//   // Realiza la petición AJAX
//   $.ajax({
//     url: url,
//     type: 'POST',
//     data: data,
//     dataType: "text", // Asegúrate de que el servidor responda con JSON
//     success: function (response) {
//       // console.log('Respuesta del servidor:', response); // Mostrar respuesta en consola
//       console.log('Data: ', data);
//       var jsonResponse = JSON.parse(response);
//       console.log('Parsed JSON:', jsonResponse);

//       if (response.success) {
//         toastr.success(jsonResponse.message, 'Éxito'); // Mensaje de éxito
//         $('#modalCambiarPasswordUser').modal('hide'); // Cierra el modal
//         $('#formCambiarPassword')[0].reset(); // Limpia el formulario
//       } else {
//         toastr.warning(jsonResponse.message, 'Advertencia'); // Mensaje de advertencia
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error('Error en la solicitud AJAX:', {
//         status: status,
//         error: error,
//         responseText: xhr.responseText
//       });
//       toastr.error('Error en la solicitud. Por favor, intenta nuevamente.', 'Error');
//     }
//   });
// }

// Función principal para enviar datos del formulario
// function enviarDatos(action) {
//   if (action === 'restablecerContraseña' && !validarCamposPassword()) {
//     return; // No continuar si no pasa la validación
//   }

//   var url = 'modulo-usuario.php?action=' + action;
//   var data = $('#formCambiarPassword').serialize();

//   // // Realiza la petición AJAX
//   // $.ajax({
//   //   url: url,
//   //   type: 'POST',
//   //   data: data,
//   //   dataType: "json", // Asegúrate de que el servidor responda con JSON
//   //   success: function (response) {
//   //     // Mostrar respuesta en consola para depurar
//   //     console.log('Data:', data);
//   //     console.log('Respuesta cruda:', response);

//   //     try {
//   //       var jsonResponse = JSON.parse(response); // Parseamos la respuesta a JSON
//   //       console.log('Respuesta parseada:', jsonResponse);

//   //       if (jsonResponse.success) {  // Utilizar jsonResponse en lugar de response
//   //         toastr.success(jsonResponse.message, 'Éxito'); // Mensaje de éxito
//   //         $('#modalCambiarPasswordUser').modal('hide'); // Cierra el modal
//   //         $('#formCambiarPassword')[0].reset(); // Limpia el formulario
//   //       } else {
//   //         toastr.warning(jsonResponse.message, 'Advertencia'); // Mensaje de advertencia
//   //       }
//   //     } catch (e) {
//   //       console.error('Error al parsear la respuesta:', e);
//   //       toastr.error('Error al procesar la respuesta. La respuesta no es un JSON válido.', 'Error');
//   //     }
//   //   },
//   //   error: function (xhr, status, error) {
//   //     console.error('Error en la solicitud AJAX:', {
//   //       status: status,
//   //       error: error,
//   //       responseText: xhr.responseText
//   //     });
//   //     toastr.error('Error en la solicitud. Por favor, intenta nuevamente.', 'Error');
//   //   }
//   // });








//   $.ajax({
//     url: url,
//     type: 'POST',
//     dataType: "text", // Asegúrate de que el servidor responda con JSON
//     success: function (response) {
//       var jsonResponse = JSON.parse(response); // Parseamos la respuesta a JSON
//       console.log('Respuesta parseada:', jsonResponse);
//       // Verificar si la respuesta es un objeto JSON válido
//       if (jsonResponse && typeof response === 'object') {

//         if (response.success) {
//           toastr.success(jsonResponse.message, 'Éxito');
//           $('#modalCambiarPasswordUser').modal('hide');
//           $('#formCambiarPassword')[0].reset();
//         } else {
//           toastr.warning(jsonResponse.message, 'Advertencia');
//         }
//       } else {
//         toastr.error('Error al procesar la respuesta. La respuesta no es un JSON válido.', 'Error');
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error('Error en la solicitud AJAX:', {
//         status: status,
//         error: error,
//         responseText: xhr.responseText
//       });
//       toastr.error('Error en la solicitud. Por favor, intenta nuevamente.', 'Error');
//     }
//   });

// }


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













function enviarDatos(action) {
  if (action === 'restablecerContraseña' && !validarCamposPassword()) {
    return; // No continuar si no pasa la validación
  }

  var url = 'modulo-usuario.php?action=restablecerContraseña';
  var data = $('#formCambiarPassword').serialize();  // Obtener los datos del formulario

  // Mostrar los datos que se van a enviar
  console.log('URL de la solicitud:', url); // Mostrar la URL
  console.log('Datos enviados:', data); // Mostrar los datos del formulario

  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: "text", // Cambiar esto si el servidor devuelve JSON
    success: function (response) {
      try {
        var jsonResponse = JSON.parse(response); // Parseamos la respuesta a JSON
        console.log('Respuesta parseada:', jsonResponse); // Mostrar la respuesta parseada
        // Verificar si la respuesta es un objeto JSON válido
        // if (jsonResponse && typeof jsonResponse === 'object') {
        if (jsonResponse.success) {
          toastr.success(jsonResponse.message, 'Éxito');
          $('#modalCambiarPasswordUser').modal('hide');
          // $('#formCambiarPassword')[0].reset();
        } else {
          toastr.warning(jsonResponse.message, 'Advertencia');
        }
        // } else {
        //   toastr.error('Error al procesar la respuesta. La respuesta no es un JSON válido.', 'Error');
        // }
      } catch (e) {
        console.error('Error al procesar la respuesta:', e);
        toastr.error('Error al procesar la respuesta. La respuesta no es un JSON válido.', 'Error');
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
  }
  // Mostrar mensaje si no es válido
  if (!valido) {
    toastr.warning(mensajeError, 'Advertencia');
  }
  return valido;
}