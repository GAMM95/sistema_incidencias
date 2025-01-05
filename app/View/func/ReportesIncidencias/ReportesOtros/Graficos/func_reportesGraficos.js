$(function () {
  // Variable global para almacenar el gráfico
  var chart;

  // Opciones iniciales del gráfico
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
      data: [] // Inicialmente vacío, se llenará dinámicamente
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

  // Función para renderizar o actualizar el gráfico
  function renderChart(data) {
    if (!chart) {
      // Si el gráfico no existe, lo crea
      chart = new ApexCharts(document.querySelector("#support-chart_report"), options);
      chart.render();
    } else {
      // Si ya existe, actualiza los datos del gráfico
      chart.updateSeries([{
        name: 'Incidencias',
        data: data // Actualizar con los nuevos datos
      }]);
    }
  }

  // Renderiza el gráfico al cargar la página con datos vacíos
  renderChart([]);

  // Función para cargar incidencias por mes para un año específico
  function cargarIncidenciasPorMes(anio) {
    fetch('ajax/getIncidenciasMensualesAnio.php?anioSeleccionado=' + anio)
      .then(response => response.json())
      .then(data => {
        console.log('Datos de incidencias por mes:', data);

        if (data.success) {
          // Actualiza el gráfico con los nuevos datos
          const incidenciasPorMes = [
            data.incidencias_enero,
            data.incidencias_febrero,
            data.incidencias_marzo,
            data.incidencias_abril,
            data.incidencias_mayo,
            data.incidencias_junio,
            data.incidencias_julio,
            data.incidencias_agosto,
            data.incidencias_setiembre,
            data.incidencias_octubre,
            data.incidencias_noviembre,
            data.incidencias_diciembre
          ];

          renderChart(incidenciasPorMes); // Actualiza la gráfica con los nuevos datos
        } else {
          console.error('No se encontraron incidencias para el año seleccionado.');
        }
      })
      .catch(error => {
        console.error('Error al obtener incidencias por mes:', error);
      });
  }

  // Evento para capturar el cambio de año en el select
  $('#anioSeleccionado').on('change', function () {
    var anioSeleccionado = $(this).val();
    cargarTotalIncidenciasAnio(anioSeleccionado);  // Llamar a la función para actualizar el total de incidencias
    cargarIncidenciasPorMes(anioSeleccionado);     // Llamar a la función para actualizar las incidencias mensuales
  });

  // Función para cargar el total de incidencias por año
  function cargarTotalIncidenciasAnio(anio) {
    console.log('Año enviado al servidor:', anio); // Muestra el valor del año enviado

    if (anio) {
      fetch('ajax/getTotalIncidenciasAnio.php?anioSeleccionado=' + anio)
        .then(response => response.json())
        .then(data => {
          console.log('Respuesta recibida del servidor:', data);

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
      $('#totalIncidencias').text('Por favor selecciona un año.');
    }
  }

  // Inicializa Select2
  $('#anioSeleccionado').select2({
    allowClear: true,
    width: '100px',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

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

      // Selecciona el primer año y carga las incidencias
      select.trigger('change');
    },
    error: function (error) {
      console.error('Error al cargar años:', error);
    }
  });
});
