$(document).ready(function () {
  // Configuracion de toastr
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

  // Seteo del combo usuarios asignados
  $.ajax({
    url: 'ajax/getUsuarioAsignado.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#usuarioAsignado');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un usuario</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.USU_codigo + '">' + value.usuarioAsignado + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Setear campos del área seleccionada
  $('#usuarioAsignado').change(function () {
    var selectedOption = $(this).find('option:selected');
    var codigoUsuario = selectedOption.val();
    var areaNombre = selectedOption.text();
    $('#codigoUsuario').val(codigoUsuario);
    // $('#usuarioAsignado').val(areaNombre);
  });

  // Buscador para el combo prioridad e impacto con ancho fijo
  $('#usuarioAsignado').select2({
    allowClear: true,
    width: '200px',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para guardar la recepcion
  $('#guardar-recepcion').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para editar la recepcion
  $('#editar-recepcion').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// Funcion para las validaciones de campos vacios y registro - actualizacion de recepcion
function enviarFormulario(action) {
  if (action === 'registrar') {
    if (!validarCamposRegistro()) {
      return; // Si la validación de registro falla, salimos
    }
  } else if (action === 'editar') {
    if (!validarCamposActualizacion()) {
      return; // Si la validación de actualización falla, salimos
    }
  }

  var url = 'registro-recepcion.php?action=' + action;
  var data = $('#formRecepcion').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      try {
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

        if (jsonResponse.success) {
          if (action === 'registrar') {
            toastr.success(jsonResponse.message, 'Mensaje');
          } else if (action === 'editar') {
            toastr.success(jsonResponse.message, 'Mensaje');
          }
          // toastr.success(action === 'registrar' ? 'Incidencia recepcionada.', 'Mensaje' : 'Incidencia recepcionada actualizada.');
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

// Validar campos de registro de recepcion antes de enviar el formulario
function validarCamposRegistro() {
  var valido = true;
  var mensajeError = '';

  // Validar campo de número de incidencia
  if ($('#incidencia').val() === '') {
    mensajeError += 'Debe seleccionar una incidencia.';
    valido = false;
  }

  // Solo validamos los otros campos si la incidencia es valida
  if (valido) {
    // Validar campo de prioridad e impacto
    var faltaPrioridad = ($('#prioridad').val() === null || $('#prioridad').val() === '');
    var faltaImpacto = ($('#impacto').val() === null || $('#impacto').val() === '');

    if (faltaPrioridad && faltaImpacto) {
      mensajeError += 'Debe seleccionar una prioridad y un impacto.';
      valido = false;
    } else if (faltaPrioridad) {
      mensajeError += 'Debe seleccionar una prioridad.';
      valido = false;
    } else if (faltaImpacto) {
      mensajeError += 'Debe seleccionar un impacto.';
      valido = false;
    }
  }

  // Mostrar el mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Validar campos antes de enviar el formulario
function validarCamposActualizacion() {
  var valido = true;
  var mensajeError = '';

  // Validar campo de número de incidencia recepcionada
  if ($('#num_recepcion').val().trim() === '') {
    mensajeError += 'Debe seleccionar una incidencia recepcionada. ';
    valido = false;
  }

  // Validar otros campos solo si el número de recepción es válido
  if (valido) {
    // Validar campo de prioridad e impacto
    var faltaPrioridad = ($('#prioridad').val() === null || $('#prioridad').val() === '');
    var faltaImpacto = ($('#impacto').val() === null || $('#impacto').val() === '');

    if (faltaPrioridad && faltaImpacto) {
      mensajeError += 'Debe seleccionar una prioridad y un impacto.';
      valido = false;
    } else if (faltaPrioridad) {
      mensajeError += 'Debe seleccionar una prioridad.';
      valido = false;
    } else if (faltaImpacto) {
      mensajeError += 'Debe seleccionar un impacto.';
      valido = false;
    }
  }

  // Mostrar el mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Funcion para eliminar recepcion
$(document).ready(function () {
  // Agregar funcionalidad para seleccionar una fila (al hacer clic)
  $('#tablaIncidenciasRecepcionadas').on('click', 'tr', function () {
    $('#tablaIncidenciasRecepcionadas tr').removeClass('selected');
    $(this).addClass('selected');
  });

  // Evento para eliminar recepción
  $('body').on('click', '.eliminar-recepcion', function (e) {
    e.preventDefault();

    // Obtener el número de recepción de la fila seleccionada
    const selectedRow = $(this).closest('tr');
    const numeroRecepcion = selectedRow.data('id');
    // Confirmar eliminación
    $.ajax({
      url: 'registro-recepcion.php?action=eliminar',
      type: 'POST',
      data: {
        num_recepcion: numeroRecepcion
      },
      dataType: 'json',
      success: function (response) {
        try {
          if (response.success) {
            toastr.success(response.message, 'Mensaje');
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
        toastr.error('Hubo un problema al eliminar la recepci&oacute;n. Int&eacute;ntalo de nuevo.', 'Mensaje de error');
      }
    });
  });
});

// Evento de clic en las filas de la tabla de incidencias sin recepcionar
$(document).on('click', '#tablaIncidenciasSinRecepcionar tbody tr', function () {
  var id = $(this).find('th').html();
  $('#tablaIncidenciasSinRecepcionar tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#incidencia').val(id);

  var incidenciaSeleccionada = $(this).find('td').eq(0).html();
  $('#incidenciaSeleccionada').val(incidenciaSeleccionada);

  // Bloquear la tabla de incidencias recepcionadas
  $('#tablaIncidenciasRecepcionadas tbody tr').addClass('pointer-events-none opacity-50');
  document.getElementById('guardar-recepcion').disabled = false;
  document.getElementById('nuevo-registro').disabled = false;

  // Reactivar el botón "Nuevo"
  $('#nuevo-registro').prop('disabled', false);
});

// Evento de clic en las filas de la tabla de incidencias recepcionadas
$(document).on('click', '#tablaIncidenciasRecepcionadas tbody tr', function () {
  var numRecepcion = $(this).attr('data-id');
  $('#tablaIncidenciasRecepcionadas tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#num_recepcion').val(numRecepcion);

  var incidenciaSeleccionada = $(this).find('td').eq(0).html();
  $('#incidenciaSeleccionada').val(incidenciaSeleccionada);

  // Bloquear la tabla de incidencias sin recepcionar
  $('#tablaIncidenciasSinRecepcionar tbody tr').addClass('pointer-events-none opacity-50');

  // Reactivar el botón "Nuevo"
  $('#nuevo-registro').prop('disabled', false);
});


// TODO: Manejo de la paginacion de la tabla de incidencias sin recepcionar
$(document).on('click', '.pagination-link', function (e) {
  e.preventDefault();
  var page = $(this).attr('href').split('page=')[1];
  changePageTablaSinRecepcionar(page);
});

// Ocultar tabla y buscador superior si no hay registros
document.addEventListener("DOMContentLoaded", function () {
  const tablaContainer = document.getElementById("tablaContainer");
  const noIncidencias = document.getElementById("noIncidencias");

  if (parseInt(document.getElementById("incidenciaCount").value) === 0) {
    tablaContainer.classList.add("hidden");
    noIncidencias.classList.add("hidden");
  } else {
    tablaContainer.classList.remove("hidden");
    noIncidencias.classList.remove("hidden");
  }
});

// Función para cambiar de página en la tabla de incidencias sin recepcionar
function changePageTablaSinRecepcionar(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaIncidenciasSinRecepcionar');
      const newPagination = newDocument.querySelector('#paginadorNuevasIncidencias');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaIncidenciasSinRecepcionar');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorNuevasIncidencias');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// TODO: FUNCION PARA CAMBIAR PAGINAS DE LA TABLA DE RECEPCIONES 
function changePageTablaRecepciones(page) {
  fetch(`?pageRecepciones=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaIncidenciasRecepcionadas');
      const newPagination = newDocument.querySelector('#paginadorRecepciones');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaIncidenciasRecepcionadas');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorRecepciones');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// TODO: Verificar la cantidad de registros y ocultar/ mostrar elementos
document.addEventListener("DOMContentLoaded", function () {
  const tablaContainer = document.getElementById("tablaContainer");
  const noIncidencias = document.getElementById("noIncidencias");

  // Ocultar tabla y buscador superior si no hay registros
  if (parseInt(document.getElementById("incidenciaCount").value) === 0) {
    // if (<? php echo count($incidencias); ?> === 0) {

    tablaContainer.classList.add("hidden");
    noIncidencias.classList.add("hidden");
  } else {
    tablaContainer.classList.remove("hidden");
    noIncidencias.classList.remove("hidden");
  }
});


// TODO: Seteo de los valores de los inputs y combos
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtener todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaIncidenciasRecepcionadas tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtener los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapeo de los valores de las celdas a los inputs del formulario
      const codRecepcion = fila.querySelector('th').innerText.trim();
      const prioridadValue = celdas[5].innerText.trim();
      const impactoValue = celdas[6].innerText.trim();

      // Seteo de valores en los inputs
      document.getElementById('num_recepcion').value = codRecepcion;

      // Seteo de los valores en los combos
      setComboValue('prioridad', prioridadValue);
      setComboValue('impacto', impactoValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-recepcion').disabled = true;
      document.getElementById('editar-recepcion').disabled = false;
      document.getElementById('nuevo-registro').disabled = false;
    });
  });
});

// seteo de los valores de los combos
function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  // Verificar si el valor esta en el combo
  let valueFound = false;
  for (let i = 0; i < options.length; i++) {
    if (options[i].text.trim() === value) {
      select.value = options[i].value;
      valueFound = true;
      break;
    }
  }
  if (!valueFound) {
    select.value = '';
  }

  // Forzar actualización del select2 para mostrar el valor seleccionado
  $(select).trigger('change');
};


// Función para limpiar los campos del formulario y reactivar tablas
function nuevoRegistro() {
  document.getElementById('formRecepcion').reset(); // Resetear el formulario completo

  // Limpiar los valores específicos de inputs y combos
  $('#rec_numero').val('');
  $('#incidencia').val('');
  $('#incidenciaSeleccionada').val('');

  // Limpiar los combos y forzar la actualización con Select2
  $('#prioridad').val('').trigger('change');  // Limpiar y actualizar combo de prioridad
  $('#impacto').val('').trigger('change');    // Limpiar y actualizar combo de impacto

  // Remover clases de selección y estilos de todas las filas de ambas tablas
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Reactivar ambas tablas
  $('#tablaIncidenciasRecepcionadas tbody tr').removeClass('pointer-events-none opacity-50');
  $('#tablaIncidenciasSinRecepcionar tbody tr').removeClass('pointer-events-none opacity-50');

  // Configurar los botones en su estado inicial
  $('#form-action').val('registrar');  // Cambiar la acción a registrar
  $('#guardar-recepcion').prop('disabled', false);  // Activar el botón de guardar
  $('#editar-recepcion').prop('disabled', true);    // Desactivar el botón de editar
  $('#nuevo-registro').prop('disabled', false);     // Asegurarse que el botón de nuevo registro está activo
}