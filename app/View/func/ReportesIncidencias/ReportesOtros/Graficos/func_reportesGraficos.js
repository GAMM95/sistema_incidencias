$(function () {
  var options = {
    chart: {
      type: 'line',
      height: 350
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
    colors: ["#1abc9c", "#3498db", "#e74c3c"],
    series: [{
      name: 'Incidencias',
      data: incidenciasPorMes
    }],
    xaxis: {
      categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
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
  function renderChart() {
    new ApexCharts(document.querySelector("#support-chart_report"), options).render();
  }
  // Renderiza el gráfico al cargar la página
  renderChart();
});

// Funcionalidades 
$(document).ready(function () {
  // Cargar los años disponibles mediante AJAX
  $.ajax({
    url: 'ajax/getAnio.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#anioSeleccionado');
      select.empty();

      $.each(data, function (index, value) {
        select.append('<option value="' + value.YEAR + '">' + value.YEAR + '</option>');
      });

      // Dispara el cambio para seleccionar el primer año y cargar las incidencias
      select.trigger('change');
    },
    error: function (error) {
      console.error('Error al cargar años:', error);
    }
  });

  // Función para cargar el total de incidencias por año
  function cargarTotalIncidenciasAnio(anio) {
    console.log('Año enviado al servidor:', anio); // Muestra el valor del año enviado
  
    // Verifica que se haya seleccionado un año antes de hacer la petición
    if (anio) {
      fetch('ajax/getTotalIncidenciasAnio.php?anioSeleccionado=' + anio)
        .then(response => response.json())
        .then(data => {
          console.log('Respuesta recibida del servidor:', data);
  
          // Actualiza el contenido del párrafo con el ID 'totalIncidencias'
          if (data.success) {
            $('#totalIncidencias').text('Total de incidencias: ' + data.total_incidencias_anio);
          } else {
            $('#totalIncidencias').text('No se encontraron incidencias para el año seleccionado.');
          }
        })
        .catch(error => {
          console.error('Error al obtener incidencias:', error);
          $('#totalIncidencias').text('Error al obtener el total de incidencias.');
        });
    } else {
      console.log('Ningún año seleccionado.');
      $('#totalIncidencias').text('Por favor selecciona un año.');
    }
  }
  
  // Evento para capturar el cambio de año
  $('#anioSeleccionado').on('change', function () {
    var anioSeleccionado = $(this).val();
    cargarTotalIncidenciasAnio(anioSeleccionado);  // Llamar a la función con el año seleccionado
  });

  // Inicializa Select2
  $('#anioSeleccionado').select2({
    allowClear: true,
    width: '100px',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });
});
