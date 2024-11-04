$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Manejador de eventos para la tecla Escape
  $(document).keydown(function (event) {
    // Verificar si la tecla presionada es ESC
    if (event.key === 'Escape') {
      nuevoRegistro();
    }
  });

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

  $('#guardar-categoria').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-categoria').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});


// Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
$(document).on('click', '#tablaCategorias tbody tr', function () {
  // Desmarcar las filas anteriores
  $('#tablaCategorias tbody tr').removeClass('bg-blue-200 font-semibold');
  // Marcar la fila actual
  $(this).addClass('bg-blue-200 font-semibold');

  // Obtener las celdas de la fila seleccionada
  const celdas = $(this).find('td');

  // Asegúrate de que 'codBien' esté correctamente asignado
  const codCategoria = $(this).find('th').text().trim();
  const nombreCategoria = celdas[0].innerText.trim();

  // Establecer valores en los inputs
  $('#codCategoria').val(codCategoria);
  $('#nombreCategoria').val(nombreCategoria);

  // Cambiar el estado de los botones
  $('#form-action').val('editar'); // Cambiar la acción a editar
  $('#guardar-categoria').prop('disabled', true);
  $('#editar-categoria').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);

  // Cambiar la acción a editar
  $('#form-action').val('editar');
});

// Funcion para limpiar los campos del formulario 
function nuevoRegistro() {
  const form = document.getElementById('formCategoria');
  form.reset();
  // Limpiar los valores especificos de los inputs
  $('#codCategoria').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Cambiar la acción del formulario a registrar
  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#editar-categoria').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Vaciar y resetear los valores de los inputs
  $('#codCategoria').val('');
  $('#nombreCategoria').val('');
}

// Funcion para las validaciones de campos vacios y registro - actualizacion de incidencias
function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'modulo-categoria.php?action=' + action;
  var data = $('#formCategoria').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      console.log('Raw response:', response);
      try {
        // Convertir la respuesta en un objeto JSON
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

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

// Validación de campos del formulario
function validarCampos() {
  var valido = true;
  var mensajeError = '';

  var faltaCategoria = ($('#nombreCategoria').val() === null || $('#nombreCategoria').val() === '');

  if (faltaCategoria) {
    mensajeError += 'Debe ingresar una nueva categor&iacute;a.';
    valido = false;
  }

  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Funcion para eliminar recepcion
$(document).ready(function () {
  // Agregar funcionalidad para seleccionar una fila (al hacer clic)
  $('#tablaCategorias').on('click', 'tr', function () {
    $('#tablaCategorias tr').removeClass('selected');
    $(this).addClass('selected');
  });

  // Evento para eliminar recepción
  $('body').on('click', '.eliminar-categoria', function (e) {
    e.preventDefault();

    // Obtener el número de recepción de la fila seleccionada
    const selectedRow = $(this).closest('tr');
    const codCategoria = selectedRow.data('id');
    // Confirmar eliminación
    $.ajax({
      url: 'modulo-categoria.php?action=eliminar',
      type: 'POST',
      data: {
        codCategoria: codCategoria
      },
      dataType: 'text',
      success: function (response) {
        try {
          // Convertir la respuesta en un objeto JSON
          var jsonResponse = JSON.parse(response);
          console.log('Parsed JSON:', jsonResponse);

          if (jsonResponse.success) {
            toastr.success(jsonResponse.message, 'Mensaje');
            setTimeout(function () {
              selectedRow.remove(); // Eliminar la fila seleccionada
              location.reload(); // Recargar la pagina
            }, 1500);
          } else {
            toastr.warning(jsonResponse.message, 'Advertencia');
          }
        } catch (e) {
          toastr.error('Error al procesar la respuesta.', 'Mensaje de error');
        }
      },
      error: function (xhr, status, error) {
        toastr.error('Hubo un problema al eliminar la categor&iacute;a. Int&eacute;ntalo de nuevo.', 'Mensaje de error');
      }
    });
  });
});

$(document).ready(function () {
  // Manejar el cambio de estado de los switches
  $('.switch-categoria').on('change', function () {
    var isChecked = $(this).is(':checked');
    var codCategoria = $(this).data('id');
    var url = isChecked ? 'modulo-categoria.php?action=habilitar' : 'modulo-categoria.php?action=deshabilitar';

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        codCategoria: codCategoria
      },
      dataType: 'json',
      success: function (response) {
        console.log('Estado: ', codCategoria);
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
        toastr.success('Estado de categor&iacute;a actualizado.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});