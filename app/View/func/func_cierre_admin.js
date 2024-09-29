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

  // Seteo del combo condicion
  $.ajax({
    url: 'ajax/getOperatividad.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#operatividad');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una condici&oacute;n</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CON_codigo + '">' + value.CON_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error('Error en la carga de condiciones:', error);
    }
  });

  // Buscador para el combo Condicion
  $('#operatividad').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para guardar el cierre
  $('#guardar-cierre').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para editar el cierre
  $('#editar-cierre').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// funcion para las validaciones de campos vacios y registro - actualizacion del cierre
function enviarFormulario(action) {
  if (action === 'registrar') {
    if (!validarCamposRegistroCierre()) {
      return; // Salir si la validacion de registro falla
    }
  } else if (action === 'editar') {
    if (!validarCamposActualizacionCierre()) {
      return; // Salir si la actualizacion falla
    }
  }

  var url = 'registro-cierre.php?action=' + action;
  var data = $('#formCierre').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      try {
        // Convertir la respuesta en json
        console.log('DATA: ', data);
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
        toastr.error('Error al procesar la respuesta.');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX Error:', error);
      toastr.error('Error en la solicitud AJAX.');
    }
  });
}

// Validar campos de registro de cierre antes de enviar formulario
function validarCamposRegistroCierre() {
  var valido = true;
  var mensajeError = '';

  // validar campo de numero de recepcion
  if ($('#recepcion').val() === '') {
    mensajeError += 'Debe seleccionar una incidencia pendiente de cierre.';
    valido = false;
  }

  // Solo validamos los otros campos si se ha seleccionado la incidencia recepcionada
  if (valido) {
    // Validacion de campos
    var faltaOperatividad = ($('#operatividad').val() === null || $('#operatividad').val() === '');
    var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
    var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');

    if (faltaOperatividad && faltaAsunto && faltaDocumento) {
      mensajeError += 'Ingrese campos requeridos.';
      valido = false;
    } else if (faltaOperatividad) {
      mensajeError += 'Debe seleccionar condici&oacute;n. ';
      valido = false;
    } else if (faltaAsunto) {
      mensajeError += 'Debe ingresar asunto de cierre. ';
      valido = false;
    } else if (faltaDocumento) {
      mensajeError += 'Debe ingresar documento de cierre. ';
      valido = false;
    }
  }

  // Mostrar el mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

// Validar campos de actualizacion antes de enviar fomrulario
function validarCamposActualizacionCierre() {
  var valido = true;
  var mensajeError = '';

  // Validar que se haya seleccionado el numero de cierre
  if ($('#num_cierre').val().trim() === '') {
    mensajeError += 'Debe seleccionar una incidencia cerrada. ';
    valido = false;
  }

  // Solo validamos los otros campos si se ha seleccionado la incidencia cerrada
  if (valido) {
    // Validacion de campos
    var faltaOperatividad = ($('#operatividad').val() === null || $('#operatividad').val() === '');
    var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
    var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');

    if (faltaOperatividad && faltaAsunto && faltaDocumento) {
      mensajeError += 'Ingrese campos requeridos.';
      valido = false;
    } else if (faltaOperatividad) {
      mensajeError += 'Debe seleccionar condici&oacute;n. ';
      valido = false;
    } else if (faltaAsunto) {
      mensajeError += 'Debe ingresar asunto de cierre. ';
      valido = false;
    } else if (faltaDocumento) {
      mensajeError += 'Debe ingresar documento de cierre. ';
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
  $('#tablaIncidenciasCerradas').on('click', 'tr', function () {
    $('#tablaIncidenciasCerradas tr').removeClass('selected');
    $(this).addClass('selected');
  });

  // Evento para eliminar recepción
  $('body').on('click', '.eliminar-cierre', function (e) {
    e.preventDefault();

    // Obtener el número de recepción de la fila seleccionada
    const selectedRow = $(this).closest('tr');
    const numeroCierre = selectedRow.data('id');
    // Confirmar eliminación
    $.ajax({
      url: 'registro-cierre.php?action=eliminar',
      type: 'POST',
      data: {
        num_cierre: numeroCierre
      },
      dataType: 'json',
      success: function (response) {
        try {
          if (response.success) {
            toastr.success(response.message, 'Mensaje');
            setTimeout(function () {
              selectedRow.remove(); // Eliminar la fila seleccionada
              location.reload(); // Recargar la pagina
            }, 2000);
          } else {
            toastr.warning(jsonResponse.message, 'Advertencia');
          }
        } catch (e) {
          toastr.error('Error al procesar la respuesta.');
        }
      },
      error: function (xhr, status, error) {
        toastr.error('Hubo un problema al eliminar la incidencia cerrada. Inténtalo de nuevo.', 'Error');
      }
    });
  });
});

//Evento de clic en las filas de la tabla de recepciones sin cerrar
$(document).on('click', '#tablaRecepcionesSinCerrar tbody tr', function () {
  // seteo del numero de recepcion
  var id = $(this).find('th').html();
  $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#recepcion').val(id);

  // Seteo del codigo de incidencia
  var numIncidencia = $(this).find('th').eq(1).html().trim();
  $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#num_incidencia').val(numIncidencia);

  // Seteo del numero formateado de la incidencia
  var incidenciaSeleccionada = $(this).find('td').eq(0).html().trim();
  $('#incidenciaSeleccionada').val(incidenciaSeleccionada);

  // Bloquear la tabla de cierres
  $('#tablaIncidenciasCerradas tbody tr').addClass('pointer-events-none opacity-50');
  document.getElementById('guardar-cierre').disabled = false;
  document.getElementById('nuevo-registro').disabled = false;

  // Reactivar el botón "Nuevo"
  $('#nuevo-registro').prop('disabled', false);
});

// Evento de click en las filas de la tabla de incidencias cerradas
$(document).on('click', '#tablaIncidenciasCerradas tbody tr', function () {
  var numCierre = $(this).find('th').html();
  $('#tablaIncidenciasCerradas tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');
  $('#num_cierre').val(numCierre);

  // Seteo del numero formateado de la incidencia
  var incidenciaSeleccionada = $(this).find('td').eq(0).html();
  $('#incidenciaSeleccionada').val(incidenciaSeleccionada);

  // Bloquear la tabla de cierres
  $('#tablaRecepcionesSinCerrar tbody tr').addClass('pointer-events-none opacity-50');
  // Reactivar el botón "Nuevo"
  $('#nuevo-registro').prop('disabled', false);
  // Cambiar la acción a editar
  $('#form-action').val('editar');
});

// Función para cambiar páginas de la tabla de recepciones sin cerrar
function changePageTablaSinCerrar(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaRecepcionesSinCerrar');
      const newPagination = newDocument.querySelector('#paginadorRecepcionesSinCerrar');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaRecepcionesSinCerrar');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorRecepcionesSinCerrar');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// Función para cambiar páginas de la tabla de cierres
function changePageCierres(page) {
  fetch(`?pageCierres=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaIncidenciasCerradas');
      const newPagination = newDocument.querySelector('#paginadorCierres');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaIncidenciasCerradas');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorCierres');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// TODO: VERIFICAR LA CANTIDAD DE REGISTROS Y OCULTAR/MOSTRAR ELEMENTOS
document.addEventListener("DOMContentLoaded", function () {
  const tablaContainer = document.getElementById("tablaContainer");
  const noRecepcion = document.getElementById("noRecepcion");

  // OCULTAR TABLA Y BUSCADOR SUPEIOR SI NO HAY REGISTROS
  if (parseInt(document.getElementById("recepcionCount").value) === 0) {
    tablaContainer.classList.add("hidden");
    noRecepcion.classList.add("hidden");
  } else {
    tablaContainer.classList.remove("hidden");
    noRecepcion.classList.remove("hidden");
  }
})

// TODO: Seteo de los valores de los inputs y combos
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtener todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaIncidenciasCerradas tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtener los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapeo de los valores de las celdas a los inputs del formulario
      const codCierre = fila.querySelector('th').innerText.trim();
      const asuntoValue = celdas[5].innerText.trim();
      const documentoValue = celdas[6].innerText.trim();
      const operatividadValue = celdas[7].innerText.trim();
      const diagnosticoValue = celdas[8].innerText.trim();
      const recomendacionesValue = celdas[9].innerText.trim();

      // Seteo de valores en los inputs
      document.getElementById('num_cierre').value = codCierre;
      document.getElementById('asunto').value = asuntoValue;
      document.getElementById('documento').value = documentoValue;
      document.getElementById('diagnostico').value = diagnosticoValue;
      document.getElementById('recomendaciones').value = recomendacionesValue;

      // Seteo de los valores en los combos
      setComboValue('operatividad', operatividadValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-cierre').disabled = true;
      document.getElementById('editar-cierre').disabled = false;
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


// Funcion para limpiar los campos del formulario y reactivar las tablas
function nuevoRegistro() {
  document.getElementById('formCierre').reset();

  // Limpiar los valores de los inputs y combo
  $('#asunto').val('');
  $('#documento').val('');
  $('#diagnostico').val('');
  $('#recomendaciones').val('');
  $('#num_incidencia').val('');
  $('#incidenciaSeleccionada').val('');
  $('#recepcion').val('');
  $('#num_cierre').val('');

  // Limpiar los combos y forzar la actualización con Select2
  $('#operatividad').val('').trigger('change');

  // Remover clases de selección y estilos de todas las filas de ambas tablas
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Reactivar ambas tablas
  $('#tablaRecepcionesSinCerrar tbody tr').removeClass('pointer-events-none opacity-50');
  $('#tablaIncidenciasCerradas tbody tr').removeClass('pointer-events-none opacity-50');

  // Configurar los botones en su estado inicial
  $('#form-action').val('registrar');  // Cambiar la acción a registrar
  $('#guardar-cierre').prop('disabled', false);  // Activar el botón de guardar
  $('#editar-cierre').prop('disabled', true);    // Desactivar el botón de editar
  $('#nuevo-registro').prop('disabled', false);     // Asegurarse que el botón de nuevo registro está activo
}

// función para filtrar la tabla de incidencias
function filtrarTablaCierres() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaIncidenciasCerradas');
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