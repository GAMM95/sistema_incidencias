$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
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