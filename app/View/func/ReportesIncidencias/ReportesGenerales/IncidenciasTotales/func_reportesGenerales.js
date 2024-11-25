$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Cargar opciones de áreas
$(document).ready(function () {
  // console.log("FETCHING");
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un &aacute;rea</option>');
      $.each(data, function (index, value) {
        // console.log("Codigo: " + index + ", Area: ", value); // Mostrar índice y valor en la consola
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching areas:", error);
    }
  });

  // Setear campos del área seleccionada
  $('#area').change(function () {
    var selectedOption = $(this).find('option:selected');
    var areaCodigo = selectedOption.val();
    var areaNombre = selectedOption.text();
    $('#codigoArea').val(areaCodigo);
    $('#nombreArea').val(areaNombre);
  });
});


// BUSCADOR PARA EL COMBO PERSONA AREA
$(document).ready(function () {
  $('#area').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });
});

$(document).ready(function () {
  // Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
  $(document).on('click', '#tablaIncidenciasDetalle tbody tr', function () {
    $('#tablaIncidenciasDetalle tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    const celdas = $(this).find('td');
    const numIncidencia = $(this).find('th').text().trim();
    const numCierre = celdas.eq(1).text().trim();
    // Seteamos los valores en los inputs correspondientes
    $('#num_incidencia').val(numIncidencia);
    $('#num_cierre').val(numCierre);
  });
});


// función para filtrar la tabla de incidencias
function filtrarTablaIncidenciasDetalle() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaIncidenciasDetalle');
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

// Habilitar y deshabilitar botones de impresión de cierre en el detalle de reporte
document.querySelectorAll('#tablaIncidenciasDetalle tbody tr').forEach(row => {
  const estadoCell = row.querySelector('td:nth-child(13) label'); // Asumiendo que la columna 13 es la de Estado
  const estado = estadoCell.textContent.trim(); // Obtenemos el texto del estado

  const botonImprimirCierre = row.querySelector('#imprimir-cierre');
  if (estado !== 'CERRADO') {
    botonImprimirCierre.disabled = true;
  } else {
    botonImprimirCierre.disabled = false;
  }
});