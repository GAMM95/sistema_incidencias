// $(document).ready(function () {
//   // Log para verificar los datos
//   console.log(incidenciasPorMes);

//   var options = {
//     chart: {
//       type: 'bar',
//       height: 200
//     },
//     plotOptions: {
//       bar: {
//         horizontal: false,
//         columnWidth: '50%'
//       },
//     },
//     dataLabels: {
//       enabled: true
//     },
//     colors: ["#1abc9c", "#3498db", "#e74c3c"], 
//     series: [{
//       name: 'Incidencias',
//       data: incidenciasPorMes  // Asegúrate de que esta variable esté correctamente definida
//     }],
//     xaxis: {
//       categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
//     },
//     tooltip: {
//       fixed: {
//         enabled: false
//       },
//       x: {
//         show: true
//       },
//       y: {
//         title: {
//           formatter: function (seriesName) {
//             return 'Cantidad ';
//           }
//         }
//       },
//       marker: {
//         show: true
//       }
//     }
//   };

//   // Asegurarse de que el contenedor exista en el DOM
//   if (document.querySelector("#support-chart_report")) {
//     new ApexCharts(document.querySelector("#support-chart_report"), options).render();
//   }
// });


// // $(document).ready(function() {
// //   $('#v-pills-graficos-tab').on('shown.bs.tab', function (e) {
// //     // Verifica si el contenedor existe
// //     var container = document.querySelector("#support-chart_report");
// //     if (container) {
// //       var options = {
// //         chart: {
// //           type: 'bar',
// //           height: 400,
// //           width: '100%',
// //         },
// //         series: [{
// //           name: 'Incidencias',
// //           data: incidenciasPorMes // Los datos ya formateados de PHP
// //         }],
// //         xaxis: {
// //           categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
// //         },
// //         plotOptions: {
// //           bar: {
// //             horizontal: false,
// //             columnWidth: '50%'
// //           }
// //         }
// //       };

// //       // Crea el gráfico
// //       var chart = new ApexCharts(container, options);
// //       chart.render();
// //     } else {
// //       console.error("El contenedor #support-chart_report no fue encontrado.");
// //     }
// //   });
// // });
