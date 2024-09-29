$(document).ready(function () {
  // Configuracion del toastr
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

  // Evento de tecla ESC
  $(document).keydown(function (event) {
    // Verificar si la tecla presionada es ESC
    if (event.key === 'Escape') {
      nuevoRegistro();
    }
  });

  // Buscar en la tabla de trabajadores
  $('#termino').on('input', function () {
    filtrarTablaTrabajador();
  });

  // Evento para guardar persona
  $('#guardar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para editar persona
  $('#editar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});


// Funcion para las validaciones de campos vacios y registro - actualizacion de personas
function enviarFormulario(action) {
  if (action === 'registrar') {
    if (!validarCamposRegistroPersona()) {
      return;
    }
  }

  var url = 'modulo-persona.php?action=' + action;
  var data = $('#formPersona').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response, error, status) {
      try {
        console.log('Raw response: ', response); // Para verificar la respuesta cruda
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
function validarCamposRegistroPersona() {
  var valido = true;
  var mensajeError = '';

  // Validar campos
  var faltaDni = ($('#dni').val() === null || $('#dni').val() === '');
  var faltaNombres = ($('#nombres').val() === null || $('#nombres').val() === '');
  var faltaApellidoPaterno = ($('#apellidoPaterno').val() === null || $('#apellidoPaterno').val() === '');
  var faltaApellidoMaterno = ($('#apellidoMaterno').val() === null || $('#apellidoMaterno').val() === '');

  if (faltaDni || faltaNombres || faltaApellidoPaterno || faltaApellidoMaterno) {
    mensajeError += 'Debe completar todos los campos requeridos.';
    valido = false;
  } else if (faltaDni) {
    mensajeError += 'Debe ingresar DNI del trabajador.';
    valido = false;
  } else if (faltaNombres) {
    mensajeError += 'Debe ingresar nombres del trabajador.';
    valido = false;
  } else if (faltaApellidoPaterno) {
    mensajeError += 'Debe ingresar apellido paterno del trabajador.';
    valido = false;
  } else if (faltaApellidoMaterno) {
    mensajeError += 'Debe ingresar apellido materno del trabajador.';
    valido = false;
  }

  // Mostrar mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim(), 'Advertencia');
  }
  return valido;
}

$(document).ready(function () {
  // Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
  $(document).on('click', '#tablaTrabajadores tbody tr', function () {
    // Desmarcar las filas anteriores
    $('#tablaTrabajadores tbody tr').removeClass('bg-blue-200 font-semibold');
    // Marcar la fila actual
    $(this).addClass('bg-blue-200 font-semibold');

    // Obtener las celdas de la fila seleccionada
    const celdas = $(this).find('td');

    // Establecer valores en los inputs según las celdas seleccionadas
    const codPersona = $(this).find('th').text().trim();
    const dni = celdas[0].innerText.trim();
    const nombreCompleto = celdas[1].innerText.trim();
    const celular = celdas[2].innerText.trim();
    const email = celdas[3].innerText.trim();

    // Dividir el nombre completo en partes para extraer apellidos y nombre
    const partesNombre = nombreCompleto.split(' ');
    const apellidoMaterno = partesNombre.pop();
    const apellidoPaterno = partesNombre.pop();
    const nombre = partesNombre.join(' ');

    // Establecer valores en los inputs
    $('#CodPersona').val(codPersona);
    $('#dni').val(dni);
    $('#nombres').val(nombre);
    $('#apellidoPaterno').val(apellidoPaterno);
    $('#apellidoMaterno').val(apellidoMaterno);
    $('#celular').val(celular);
    $('#email').val(email);

    // Cambiar el estado de los botones
    $('#form-action').val('editar'); // Cambiar la acción a editar
    $('#guardar-persona').prop('disabled', true);
    $('#editar-persona').prop('disabled', false);
    $('#nuevo-registro').prop('disabled', false);
  });
});


// Función para manejar el nuevo registro
function nuevoRegistro() {
  const form = document.getElementById('formPersona');
  form.reset();
  $('#CodPersona').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#guardar-persona').prop('disabled', false);
  $('#editar-persona').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Limpiar el campo de búsqueda y actualizar la tabla
  document.getElementById('termino').value = '';
  filtrarTablaTrabajador();
}

// // función para cambiar de página en la tabla de trabajadores
// function cambiarPaginaTablaTrabajadores(page) {
//   const terminoBusqueda = document.getElementById('termino').value; // Captura el término de búsqueda actual

//   // Realiza la solicitud incluyendo el término de búsqueda
//   fetch(`?page=${page}&search=${encodeURIComponent(terminoBusqueda)}`)
//     .then(response => response.text())
//     .then(data => {
//       const parser = new DOMParser();
//       const newDocument = parser.parseFromString(data, 'text/html');
//       const newTable = newDocument.querySelector('#tablaTrabajadores');
//       const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

//       // Reemplazar la tabla actual con la nueva tabla obtenida
//       document.querySelector('#tablaTrabajadores').parentNode.replaceChild(newTable, document.querySelector('#tablaTrabajadores'));

//       // Reemplazar la paginación actual con la nueva paginación obtenida
//       const currentPagination = document.querySelector('.flex.justify-end.items-center.mt-1');
//       if (currentPagination && newPagination) {
//         currentPagination.parentNode.replaceChild(newPagination, currentPagination);
//       }

//       // Reaplicar el filtro en el cliente si necesario
//       filtrarTablaTrabajador();
//     })
//     .catch(error => {
//       console.error('Error al cambiar de página:', error);
//     });
// }

// // Manejo de la paginación
// $(document).on('click', '.pagination-link', function (e) {
//   e.preventDefault();
//   var page = $(this).data('page');
//   cambiarPaginaTablaTrabajadores(page);
// });

// función para filtrar la tabla de trabajadores
function filtrarTablaTrabajador() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaTrabajadores');
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
