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
    colors: ["#1abc9c", "#3498db", "#e74c3c"], 
    series: [{
      name: 'Incidencias',
      data: incidenciasMensuales 
    }],
    xaxis: {
      categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'], // Etiquetas de categorías
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
