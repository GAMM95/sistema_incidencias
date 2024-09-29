$(function () {
  var options1 = {
    chart: {
      type: 'bar',
      height: 200
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%'
      },
    },
    dataLabels: {
      enabled: true
    },
    colors: ["#1abc9c", "#3498db", "#e74c3c"], // Colores para cada barra
    series: [{
      name: 'Incidencias',
      data: incidenciasData
    }],
    xaxis: {
      categories: ['Abiertas', 'Recepcionadas', 'Cerradas'], // Etiquetas de categorías
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: true
      },
      y: {
        title: {
          formatter: function (seriesName) {
            return 'Cantidad ';
          }
        }
      },
      marker: {
        show: true
      }
    }
  };

  new ApexCharts(document.querySelector("#support-chart"), options1).render();
});

// Establece el intervalo para recargar la página (30000 ms = 30 segundos)
// setTimeout(function () {
//   window.location.reload();
// }, 30000); // 30000 ms = 30 segundos


document.addEventListener('DOMContentLoaded', function () {
  // Función para cargar las incidencias basado en la fecha seleccionada o la fecha por defecto
  function cargarIncidencias(fecha) {
    // Realizar la solicitud AJAX
    fetch('ajax/getListarIncidenciasFechaAdmin.php?fecha=' + encodeURIComponent(fecha))
      .then(response => response.json())
      .then(data => {
        // Selecciona el cuerpo de la tabla
        var tbody = document.getElementById('incidenciasBody');
        tbody.innerHTML = ''; // Limpia el contenido actual

        // Rellena la tabla con los nuevos datos
        data.forEach(incidencia => {
          var row = document.createElement('tr');
          row.innerHTML = `
            <td class="text-center text-xs align-middle">${incidencia.INC_numero_formato}</td>
            <td class="w-1/4 max-w-[150px] break-words whitespace-normal">
              <div class="flex items-center">
                <img class="rounded-full w-10 h-10 mr-4" src="dist/assets/images/user/avatar.png" alt="User-Profile-Image">
                <div class="break-words whitespace-normal">
                  <h6 class="text-xs">${incidencia.Usuario}</h6>
                  <p class="text-muted text-xs">${incidencia.ARE_nombre}</p>
                </div>
              </div>
            </td>
            <td class="text-center text-xs align-middle">${incidencia.fechaIncidenciaFormateada}</td>
            <td class="text-center text-xs align-middle w-1/4 max-w-[200px] break-words whitespace-normal">
              <div class="break-words whitespace-normal">
                <p class="text-muted text-xs"> ${incidencia.INC_asunto}</p>
              </div>
            </td>
            <td class="text-center text-xs align-middle">${incidencia.INC_documento}</td>
            <td class="text-center text-xs align-middle">
              <label class="badge ${getBadgeClass(incidencia.ESTADO)}">${incidencia.ESTADO}</label>
            </td>
          `;
          tbody.appendChild(row);
        });

        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3">No hay incidencias para la fecha seleccionada.</td></tr>';
        }
      })
      .catch(error => console.error('Error:', error));
  }

  // Función para obtener la clase del badge basado en el estado
  function getBadgeClass(estado) {
    switch (estado) {
      case 'ABIERTO':
        return 'badge-light-danger';
      case 'RECEPCIONADO':
        return 'badge-light-success';
      case 'CERRADO':
        return 'badge-light-primary';
      default:
        return 'badge-light-secondary';
    }
  }

  // Llamada inicial con la fecha por defecto
  var fechaInicial = document.getElementById('fechaInput').value;
  cargarIncidencias(fechaInicial);

  // Evento para cargar incidencias cuando cambia la fecha
  document.getElementById('fechaInput').addEventListener('change', function () {
    var fechaSeleccionada = this.value;
    cargarIncidencias(fechaSeleccionada);
  });
});
