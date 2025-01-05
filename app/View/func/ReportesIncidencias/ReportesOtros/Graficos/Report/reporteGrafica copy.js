$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$('#reporteGrafica').click(function () {
  const anioSeleccionado = $('#anioSeleccionado').val();
  console.log('Año seleccionado:', anioSeleccionado);

  if (!anioSeleccionado) {
    toastr.warning('Debe seleccionar un año para generar el reporte.');
    return; // Detener si no se ha seleccionado un año
  }

  // Realizar solicitud AJAX para obtener los datos del total de incidencias
  $.ajax({
    url: 'ajax/getTotalIncidenciasAnio.php',
    method: 'GET',
    data: { anioSeleccionado: anioSeleccionado },
    dataType: 'json',
    success: function (data) {
      console.log('Total incidencias por año:', data);
      
      if (data.success) {
        const totalIncidencias = data.total_incidencias_anio;

        // Verificar si hay incidencias para ese año
        if (totalIncidencias > 0) {
          try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');
            const logoUrl = './public/assets/escudo.png';

            // Encabezado del PDF
            function addHeader(doc, totalIncidencias) {
              doc.setFontSize(9);
              doc.setFont('helvetica', 'normal');
              const fechaImpresion = new Date().toLocaleDateString();
              const headerText2 = 'Subgerencia de Informática y Sistemas';
              const reportTitle = `Reporte de Incidencias para el año ${anioSeleccionado}`;

              const pageWidth = doc.internal.pageSize.width;
              const marginX = 10;
              const marginY = 5;
              const logoWidth = 25;
              const logoHeight = 25;

              // Agregar logo
              const logoImage = new Image();
              logoImage.src = logoUrl;

              logoImage.onload = function() {
                doc.addImage(logoImage, 'PNG', marginX, marginY, logoWidth, logoHeight);

                // Titulo principal
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                const titleWidth = doc.getTextWidth(reportTitle);
                const titleX = (pageWidth - titleWidth) / 2;
                const titleY = 20;
                doc.text(reportTitle, titleX, titleY);
                doc.setLineWidth(0.5);
                doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1);

                // Subtítulo: cantidad de registros
                const subtitleText = `Total de incidencias: ${totalIncidencias}`;
                doc.setFontSize(11);
                const subtitleWidth = doc.getTextWidth(subtitleText);
                const subtitleX = (pageWidth - subtitleWidth) / 2;
                doc.text(subtitleText, subtitleX, marginY + 25);

                // Fecha de impresión 
                doc.setFontSize(8);
                doc.setFont('helvetica', 'normal');
                const fechaText = `Fecha de impresión: ${fechaImpresion}`;
                const headerText2Width = doc.getTextWidth(headerText2);
                const fechaTextWidth = doc.getTextWidth(fechaText);
                const headerText2X = pageWidth - marginX - headerText2Width;
                const fechaTextX = pageWidth - marginX - fechaTextWidth;
                const headerText2Y = marginY + logoHeight / 2;
                const fechaTextY = headerText2Y + 5;

                doc.text(headerText2, headerText2X, headerText2Y);
                doc.text(fechaText, fechaTextX, fechaTextY);
              };
            }

            // Pie de página
            function addFooter(doc, pageNumber, totalPages) {
              doc.setFontSize(8);
              doc.setFont('helvetica', 'italic');
              const footerY = 200;
              doc.setLineWidth(0.5);
              doc.line(10, footerY - 5, doc.internal.pageSize.width - 10, footerY - 5);

              const footerText = 'Sistema de Gestión de Incidencias';
              const pageInfo = `Página ${pageNumber} de ${totalPages}`;
              doc.text(footerText, 10, footerY);
              doc.text(pageInfo, doc.internal.pageSize.width - 10 - doc.getTextWidth(pageInfo), footerY);
            }

            // Agregar la tabla de incidencias
            function addTable(doc, incidenciasData) {
              doc.autoTable({
                startY: 40,
                margin: { left: 200 },
                head: [['Mes', 'Incidencias']],
                body: incidenciasData.map(incidencia => [
                  incidencia.mes,
                  incidencia.totalIncidencias
                ]),
                styles: {
                  fontSize: 8.5,
                  cellPadding: 1.5,
                  halign: 'center',
                  valign: 'middle',
                  overflow: 'linebreak'  // Ajuste para manejar contenido largo
                },
                headStyles: {
                  fillColor: [44, 62, 80],
                  textColor: [255, 255, 255],
                  fontStyle: 'bold',
                  halign: 'center'
                },
                columnStyles: {
                  0: { cellWidth: 25 },
                  1: { cellWidth: 20 }
                },
                pageBreak: 'auto'  // Permite dividir la tabla en múltiples páginas si es necesario
              });
            }

            // Función para obtener los datos de incidencias por mes
            fetch('ajax/getIncidenciasMensualesAnio.php?anioSeleccionado=' + anioSeleccionado)
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const incidenciasPorMes = [
                    { mes: 'Enero', totalIncidencias: data.incidencias_enero },
                    { mes: 'Febrero', totalIncidencias: data.incidencias_febrero },
                    { mes: 'Marzo', totalIncidencias: data.incidencias_marzo },
                    { mes: 'Abril', totalIncidencias: data.incidencias_abril },
                    { mes: 'Mayo', totalIncidencias: data.incidencias_mayo },
                    { mes: 'Junio', totalIncidencias: data.incidencias_junio },
                    { mes: 'Julio', totalIncidencias: data.incidencias_julio },
                    { mes: 'Agosto', totalIncidencias: data.incidencias_agosto },
                    { mes: 'Septiembre', totalIncidencias: data.incidencias_setiembre },
                    { mes: 'Octubre', totalIncidencias: data.incidencias_octubre },
                    { mes: 'Noviembre', totalIncidencias: data.incidencias_noviembre },
                    { mes: 'Diciembre', totalIncidencias: data.incidencias_diciembre }
                  ];

                  // Agregar encabezado, tabla de incidencias y pie de página al PDF
                  addHeader(doc, totalIncidencias);
                  addTable(doc, incidenciasPorMes);

                  // Agregar pie de página en todas las páginas
                  const totalPages = doc.internal.getNumberOfPages();
                  for (let i = 1; i <= totalPages; i++) {
                    doc.setPage(i);
                    addFooter(doc, i, totalPages);
                  }

                  // Establecer las propiedades del documento
                  doc.setProperties({
                    title: "Reporte de incidencias por año.pdf"
                  });

                  // Mostrar mensaje de éxito
                  toastr.success('Reporte generado.','Mensaje');

                  // Abrir el PDF generado
                  setTimeout(() => {
                    window.open(doc.output('bloburl'), '_blank');
                  }, 2000);
                } else {
                  toastr.warning('No se encontraron incidencias para el a&ntilde;o seleccionado.,');
                }
              })
              .catch(error => {
                toastr.error('Hubo un error al generar el reporte.', 'Error');
                console.error('Error al obtener incidencias por mes:', error);
              });
          } catch (error) {
            toastr.error('Hubo un error al generar el reporte.', 'Error');
            console.error('Error al generar PDF:', error);
          }
        } else {
          toastr.warning('No se encontraron incidencias para el a&ntilde;o seleccionado.');
        }
      } else {
        toastr.warning('No se encontraron incidencias para el a&ntilde;o seleccionado.');
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Error al obtener el total de incidencias.', 'Error');
      console.error('Error en la solicitud AJAX:', error);
    }
  });
});


         // function addChartToPDF() {
            //   const chartElement = document.getElementById('support-chart_report');

            //   if (chartElement) {
            //     // Envuelve html2canvas en una promesa explícita
            //     new Promise((resolve, reject) => {
            //       html2canvas(chartElement, { useCORS: true }).then(canvas => {
            //         resolve(canvas);
            //       }).catch(err => {
            //         reject(err);
            //       });
            //     }).then((canvas) => {
            //       const imgData = canvas.toDataURL('image/png');
            //       const doc = new jsPDF('landscape');
            //       const pageWidth = doc.internal.pageSize.width;

            //       // Añadir la imagen de la gráfica a la izquierda de la tabla
            //       doc.addImage(imgData, 'PNG', 10, 50, 90, 60); // Ajusta la posición y tamaño

            //       // Ahora agregar la tabla
            //       addTable(doc, incidenciasPorMes);

            //     }).catch(error => {
            //       console.error('Error al generar la imagen de la gráfica:', error);
            //     });
            //   }
            // }