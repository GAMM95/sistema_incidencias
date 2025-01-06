// Funcion para la grfica de barras de las incidencias
$(function () {
  var options1 = {
    chart: {
      type: 'area',
      height: 300
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
      data: incidenciasDataUser
    }],
    xaxis: {
      categories: ['Nuevas', 'Recepcionadas', 'Cerradas'], // Etiquetas de categorías
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

  // Función para renderizar el gráfico
  function renderChart() {
    new ApexCharts(document.querySelector("#support-chart3"), options1).render();
  }

  // Renderiza el gráfico al cargar la página
  renderChart();

  // Establece el intervalo para recargar la página (30000 ms = 30 segundos)
  setInterval(function () {
    window.location.reload();
  }, 60000); // = 1 minuto
});

// Funcion para cargar las incidencias basado en la fecha seleccionada o la fecha por defecto
document.addEventListener('DOMContentLoaded', function () {
  // Función para cargar las incidencias basado en la fecha seleccionada o la fecha por defecto
  function cargarIncidencias(fecha, codigoArea) {
    console.log("Fecha seleccionada:", fecha);
    console.log("Código de Área:", codigoArea);

    // Construir la URL con los parámetros de fecha y área
    const url = `ajax/getListarIncidenciasFechaUser.php?fecha=${encodeURIComponent(fecha)}&codigoArea=${encodeURIComponent(codigoArea)}`;
    // Realizar la solicitud AJAX
    fetch(url)
      .then(response => response.json())  // Espera la respuesta en formato JSON
      .then(data => {
        console.log("Respuesta del servidor:", data);  // Muestra la respuesta completa

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
              <label class="badge ${getBadgeClass(incidencia.Estado)}">${incidencia.Estado}</label>
            </td>`;
          tbody.appendChild(row);
        });

        // Mensaje si no se encuentran incidencias
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

  // Inicialización
  var area = '<?= $area; ?>'; // Esto debe tener el valor del área desde la sesión

  var fechaInput = document.getElementById('fechaInput');
  var areaInput = document.getElementById('codigoArea');

  var fechaInicial = fechaInput ? fechaInput.value : '';
  // Si el área no está definido, se usa el área desde la sesión
  area = areaInput && areaInput.value ? areaInput.value : area;

  // Cargar las incidencias iniciales
  cargarIncidencias(fechaInicial, area);

  // Evento para cargar incidencias cuando cambia la fecha
  fechaInput.addEventListener('change', function () {
    var fechaSeleccionada = this.value;
    var areaSeleccionada = areaInput && areaInput.value ? areaInput.value : area;
    cargarIncidencias(fechaSeleccionada, areaSeleccionada);
  });

  // Evento para cargar incidencias cuando cambia el área
  areaInput.addEventListener('change', function () {
    var fechaSeleccionada = fechaInput.value;
    var areaSeleccionada = this.value;
    cargarIncidencias(fechaSeleccionada, areaSeleccionada);
  });
});
