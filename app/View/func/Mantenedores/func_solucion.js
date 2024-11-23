$(document).ready(function () {
  // Configurar la posición de Toastr
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


  $('#guardar-solucion').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-solucion').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});


// Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
$(document).on('click', '#tablaSoluciones tbody tr', function () {
  // Desmarcar las filas anteriores
  $('#tablaSoluciones tbody tr').removeClass('bg-blue-200 font-semibold');
  // Marcar la fila actual
  $(this).addClass('bg-blue-200 font-semibold');

  // Obtener las celdas de la fila seleccionada
  const celdas = $(this).find('td');

  // Asegúrate de que 'codigoSolucion' esté correctamente asignado
  const codigoSolucion = $(this).find('th').text().trim();
  const descripcionSolucion = celdas[0].innerText.trim();

  // Establecer valores en los inputs
  $('#codigoSolucion').val(codigoSolucion);
  $('#descripcionSolucion').val(descripcionSolucion);

  // Cambiar el estado de los botones
  $('#form-action').val('editar'); // Cambiar la acción a editar
  $('#guardar-solucion').prop('disabled', true);
  $('#editar-solucion').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);

  // Cambiar la acción a editar
  $('#form-action').val('editar');
});

// Funcion para limpiar los campos del formulario 
function nuevoRegistro() {
  const form = document.getElementById('formSolucion');
  form.reset();
  // Limpiar los valores especificos de los inputs
  $('#codigoSolucion').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Cambiar la acción del formulario a registrar
  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#guardar-solucion').prop('disabled', false);
  $('#editar-solucion').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Vaciar y resetear los valores de los inputs
  $('#codigoSolucion').val('');
  $('#descripcionSolucion').val('');
}

// Funcion para las validaciones de campos vacios y registro - actualizacion de incidencias
function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'modulo-solucion.php?action=' + action;
  var data = $('#formSolucion').serialize();

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

  var faltaDescripcion = ($('#descripcionSolucion').val() === null || $('#descripcionSolucion').val() === '');

  if (faltaDescripcion) {
    mensajeError += 'Debe ingresar una nueva soluci&oacute;n.';
    valido = false;
  }

  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}


$(document).ready(function () {
  // Manejar el cambio de estado de los switches
  $('.switch-solucion').on('change', function () {
    var isChecked = $(this).is(':checked');
    var codigoSolucion = $(this).data('id');
    var url = isChecked ? 'modulo-solucion.php?action=habilitar' : 'modulo-solucion.php?action=deshabilitar';

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        codigoSolucion: codigoSolucion
      },
      dataType: 'json',
      success: function (response) {
        console.log('Estado: ', codigoSolucion);
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
        toastr.success('Estado de soluci&oacute;n actualizado.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});