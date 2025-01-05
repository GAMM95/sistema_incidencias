$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento para manejar la tecla Enter cuando una fila está seleccionada
  $(document).on('keydown', function (e) {
    // Verificar si la tecla presionada es Enter
    if (e.key === 'Enter') {
      // Si la fila está seleccionada, proceder a actualizar
      if ($('.bg-blue-200.font-semibold').length > 0) {
        e.preventDefault();
        enviarFormulario('editar');
      }
    }
  });

  // Evento de tecla ESC
  $(document).keydown(function (event) {
    // Verificar si la tecla presionada es ESC
    if (event.key === 'Escape') {
      nuevoRegistro();
    }
  });

  // Buscar en la tabla de trabajadores
  $('#termino').on('input', function () {
    filtrarTablaBienes();
  });

  $('#guardar-bien').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-bien').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// Funcion para las validaciones de campos vacios y registro - actualizacion de bienes
function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'modulo-bien.php?action=' + action;
  var data = $('#formBienes').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      console.log('Raw response:', response);
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
  var faltaCodigoIdentificador = ($('#codigoIdentificador').val() === null || $('#codigoIdentificador').val() === '');
  var faltaNombreTipoBien = ($('#nombreTipoBien').val() === null || $('#nombreTipoBien').val() === '');

  if (faltaCodigoIdentificador && faltaNombreTipoBien) {
    mensajeError += 'Debe completar todos los campos.';
    valido = false;
  } else if (faltaCodigoIdentificador) {
    mensajeError += 'Debe ingresar un codigo identificador.';
    valido = false;
  } else if (faltaNombreTipoBien) {
    mensajeError += 'Debe ingresar un nombre para el tipo de bien.';
    valido = false;
  }

  // Mostrar mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
$(document).on('click', '#tablaListarBienes tbody tr', function () {
  // Desmarcar las filas anteriores
  $('#tablaListarBienes tbody tr').removeClass('bg-blue-200 font-semibold');
  // Marcar la fila actual
  $(this).addClass('bg-blue-200 font-semibold');

  // Obtener las celdas de la fila seleccionada
  const celdas = $(this).find('td');

  // Asegúrate de que 'codBien' esté correctamente asignado
  const codBien = $(this).find('th').text().trim();
  const codigoIdentificador = celdas[1].innerText.trim();
  const nombreTipoBien = celdas[2].innerText.trim();

  // Establecer valores en los inputs
  $('#codBien').val(codBien);
  $('#codigoIdentificador').val(codigoIdentificador);
  $('#nombreTipoBien').val(nombreTipoBien);

  // Cambiar el estado de los botones
  $('#form-action').val('editar'); // Cambiar la acción a editar
  $('#guardar-bien').prop('disabled', true);
  $('#editar-bien').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);

  // Cambiar la acción a editar
  $('#form-action').val('editar');
});



// Funcionaliad boton nuevo
function nuevoRegistro() {
  const form = document.getElementById('formBienes');
  form.reset();
  $('#codBien').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Cambiar la acción del formulario a registrar
  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#guardar-bien').prop('disabled', false);
  $('#editar-bien').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Limpiar el campo de búsqueda y actualizar la tabla
  document.getElementById('termino').value = '';
  filtrarTablaBienes();
}


// función para filtrar la tabla de bienes
function filtrarTablaBienes() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaListarBienes');
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

// Funcion para eliminar recepcion
$(document).ready(function () {
  // Agregar funcionalidad para seleccionar una fila (al hacer clic)
  $('#tablaListarBienes').on('click', 'tr', function () {
    $('#tablaListarBienes tr').removeClass('selected');
    $(this).addClass('selected');
  });

  // Evento para eliminar recepción
  $('body').on('click', '.eliminar-bien', function (e) {
    e.preventDefault();

    // Obtener el número de recepción de la fila seleccionada
    const selectedRow = $(this).closest('tr');
    const codBien = selectedRow.data('id');
    // Confirmar eliminación
    $.ajax({
      url: 'modulo-bien.php?action=eliminar',
      type: 'POST',
      data: {
        codBien: codBien
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
        toastr.error('Hubo un problema al eliminar bien. Int&eacute;ntalo de nuevo.', 'Mensaje de error');
      }
    });
  });
});

// Funcion para manejar el cambio de estado de los switches
$(document).ready(function () {
  // Manejar el cambio de estado de los switches
  $('.switch-bien').on('change', function () {
    var isChecked = $(this).is(':checked');
    var codBien = $(this).data('id');
    var url = isChecked ? 'modulo-bien.php?action=habilitar' : 'modulo-bien.php?action=deshabilitar';

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        codBien: codBien
      },
      dataType: 'json',
      success: function (response) {
        console.log('Estado: ', codBien);
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
        toastr.success('Estado del bien actualizado.', 'Mensaje');
        setTimeout(function () {
          location.reload();
        }, 1000);
      }
    });
  });
});

// Funcion para mostrar la notificacion toast
function showToast() {
  $('.toast-5s').toast('show');
}

// Funcion para Capitalizar la primera letra de un input
function uppercaseInput(element) {
  element.value = element.value.toUpperCase();
}
