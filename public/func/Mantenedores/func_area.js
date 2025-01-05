$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento para manejar la tecla Enter cuando una fila está seleccionada
  $(document).on('keydown', function (e) {
    // Verificar si la tecla presionada es Enter (keyCode 13)
    if (e.key === 'Enter') {
      // Si la fila está seleccionada, proceder a actualizar
      if ($('.bg-blue-200.font-semibold').length > 0) {
        e.preventDefault();
        enviarFormulario('editar');
      }
    }
  });

  // Manejador de eventos para la tecla Escape
  $(document).keydown(function (event) {
    // Verificar si la tecla presionada es ESC
    if (event.key === 'Escape') {
      nuevoRegistro();
    }
  });

  // Buscar en la tabla de trabajadores
  $('#termino').on('input', function () {
    filtrarTablaAreas();
  });

  $('#guardar-area').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-area').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// Funcion para las validaciones de campos vacios y registro - actualizacion de areas
function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'modulo-area.php?action=' + action;
  var data = $('#formArea').serialize();

  // Verificar los datos antes de enviarlos
  console.log('Datos enviados:', data);

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response, error, status) {
      try {
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON: ', jsonResponse);
        if (jsonResponse.success) {
          if (action === 'registrar') {
            toastr.success(jsonResponse.message, 'Mensaje');
          } else if (action === 'editar') {
            toastr.success(jsonResponse.message, 'Mensaje');
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          toastr.warning(jsonResponse.message, 'Advertencia');
        }
      } catch (e) {
        console.error('JSON parsing error:', e);
        toastr.error('Error al procesar la respuesta.', 'Mensaje de error');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX Error:', error);
      toastr.error('Error en la solicitud AJAX.', 'Mensaje de error');
    }
  });
}


// Función para validar campos antes de enviar
function validarCampos() {
  var valido = true;
  var mensajeError = '';

  // Validar campos
  var faltaArea = ($('#nombreArea').val() === null || $('#nombreArea').val() === '');

  if (faltaArea) {
    mensajeError += 'Debe ingresar nombre de &aacute;rea.';
    valido = false;
  }

  // Mostrar mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
$(document).on('click', '#tablaAreas tbody tr', function () {
  // Desmarcar las filas anteriores
  $('#tablaAreas tbody tr').removeClass('bg-blue-200 font-semibold');
  // Marcar la fila actual
  $(this).addClass('bg-blue-200 font-semibold');

  // Obtener las celdas de la fila seleccionada
  const celdas = $(this).find('td');

  // Asegúrate de que 'codArea' esté correctamente asignado
  const codArea = $(this).find('th').text().trim(); // Verifica que esta selección es correcta
  const nombreArea = celdas[1].innerText.trim();

  // Establecer valores en los inputs
  $('#codArea').val(codArea);
  $('#nombreArea').val(nombreArea);

  // Cambiar el estado de los botones
  $('#form-action').val('editar'); // Cambiar la acción a editar
  $('#guardar-area').prop('disabled', true);
  $('#editar-area').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);

  // Cambiar la acción a editar
  $('#form-action').val('editar');
});

// Funcionaliad boton nuevo
function nuevoRegistro() {
  const form = document.getElementById('formArea');
  form.reset();
  $('#codArea').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Cambiar la acción del formulario a registrar
  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#guardar-area').prop('disabled', false);
  $('#editar-area').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', false);

  // Limpiar el campo de búsqueda y actualizar la tabla
  document.getElementById('termino').value = '';
  filtrarTablaAreas();
}


// función para filtrar la tabla de areas
function filtrarTablaAreas() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaAreas');
  filas = tabla.getElementsByTagName('tr');

  for (i = 1; i < filas.length; i++) {
    celdas = filas[i].getElementsByTagName('td');
    match = false;
    for (j = 0; j < celdas.length; j++) {
      if (celdas[j].innerText.toUpperCase().indexOf(filtro) > -1) {
        match = true;
        break;
      }
    }
    filas[i].style.display = match ? '' : 'none';
  }
}

// // TODO: falta
// $(document).ready(function () {
//   // Manejar el cambio en los switches
//   $('input[type="checkbox"]').change(function () {
//     const checkbox = $(this);
//     const codArea = checkbox.attr('id').replace('customswitch', '');
//     const url = checkbox.is(':checked') ? 'modulo-area.php?action=habilitar' : 'modulo-area.php?action=deshabilitar';

//     $.ajax({
//       url: url,
//       type: 'POST',
//       data: {
//         codArea: codArea
//       },
//       dataType: 'json',
//       success: function (response) {
//         try {
//           var jsonResponse = JSON.parse(response);
//           console.log('Parsed JSON:', jsonResponse);

//           if (response.success) {
//             toastr.success(jsonResponse.message, 'Mensaje');
//             setTimeout(function () {
//               location.reload();
//             }, 1000);
//           } else {
//             toastr.success(jsonResponse.message, 'Mensaje de error');
//             checkbox.prop('checked', !checkbox.is(':checked'));
//           }
//         } catch (e) {
//           console.error('JSON parsing error:', e);
//           console.log('Error:', e);
//           // toastr.error('Error al procesar la respuesta.', 'Mensaje de error');
//           toastr.success('Estado actualizado.', 'Mensaje');
//         }

//       },
//       error: function (xhr, status, error) {
//         // toastr.error('Ocurrió un error al actualizar el estado del usuario.', 'Mensaje de error');
//         toastr.success('Estado actualizado', 'Mensaje');
//         setTimeout(function () {
//           location.reload();
//         }, 1000);
//         // Restaura el estado del switch en caso de error
//         checkbox.prop('checked', !checkbox.is(':checked'));
//       }
//     });
//   });
// });

$(document).ready(function () {
  // Manejar el cambio de estado de los switches
  $('.switch-area').on('change', function () {
    var isChecked = $(this).is(':checked');
    var codigoArea = $(this).data('id');
    var url = isChecked ? 'modulo-area.php?action=habilitar' : 'modulo-area.php?action=deshabilitar';

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        codArea: codigoArea
      },
      dataType: 'json',
      success: function (response) {
        console.log('Estado: ', codigoArea);
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

        if (response.success) {
          toastr.success(jsonResponse.message);
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else {
          toastr.error(jsonResponse.message);
        }
      },
      error: function (xhr, status, error) {
        toastr.success('Estado de &aacute;rea actualizado.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});